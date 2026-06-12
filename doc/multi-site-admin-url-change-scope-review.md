# 파일명: doc/multi-site-admin-url-change-scope-review.md
# 버전정보: v1.0.0
# 생성일: 2026-06-12
# 작성 AI 모델명: GPT-5.5
# 기준 브랜치: cursor/check-mvs-admin-url-plan-04ed

# MVS 다중 사이트/단말기 및 관리자 URL 변경 범위 검토

## 1. 목적

`doc/multi-site-admin-url-change-plan.md`에 정리된 다중 사이트/단말기 및 관리자 URL 변경 계획을 실제 코드 구조와 대조하여, 구현 시 필요한 변경 범위를 확정한다.

이번 검토는 문서 내용의 방향성이 현재 코드에 어느 정도 반영되어 있는지 확인하고, 실제 구현에 필요한 필수 작업과 후속 확장 작업을 구분하는 데 목적이 있다.

## 2. 검토 대상

주요 검토 대상은 다음과 같다.

| 구분 | 파일/영역 |
| --- | --- |
| 라우트 | `routes/web.php`, `routes/api.php` |
| 컨트롤러 | `MvsController`, `AuthController`, `CustomerController`, `VideoController`, `UserController` |
| 모델 | `User`, `Customer`, `Video` |
| DB 스키마 | `database/migrations/*` |
| 관리자 Blade | `resources/views/layouts/*`, `resources/views/customers/*`, `resources/views/videos/*`, `resources/views/auth/*` |
| 재생 Blade | `resources/views/mvs/index.blade.php`, `resources/views/mvs/pc.blade.php`, `resources/views/layouts/mvsHeader.blade.php`, `resources/views/layouts/mvsPcHeader.blade.php` |
| Asterisk 연동 | `asterisk/mvs_GetUserInform.php`, `asterisk/mvs_GetVideosInform.php` |

## 3. 실제 코드 기준 확인 결과

### 3.1 DB 구조

현재 DB 구조에는 다중 사이트/단말기 구분을 위한 핵심 테이블과 컬럼이 없다.

| 항목 | 현재 상태 |
| --- | --- |
| `mvs_sites` | 없음 |
| `mvs_terminals` | 없음 |
| `users.site_id` | 없음 |
| `users.role` | 없음 |
| `customers.site_id` | 없음 |
| `videos.site_id` | 없음 |

현재 `users`는 `role_id`만 사용한다.

| role_id | 현재 의미 |
| --- | --- |
| `1` | 최고 관리자 |
| `2` | 관리자 |

`customers.tel`은 DB migration 레벨에서 unique 제약이 걸려 있지는 않지만, `CustomerController::store()`에서 `unique:customers` 검증을 사용하므로 현재 동작은 전역 전화번호 중복 제한이다.

사이트별 고객 관리를 도입하려면 전화번호 중복 검증도 `site_id + tel` 기준으로 변경해야 한다.

### 3.2 라우트 및 인증

현재 라우트는 사이트 구분 없이 전역으로 구성되어 있다.

| 현재 URL | 현재 역할 |
| --- | --- |
| `/` | MVS 재생 화면 |
| `/pc` | PC 재생 화면 |
| `/login` | 관리자 로그인 |
| `/dashboard` | 관리자 대시보드 |
| `/customers` | 고객 관리 |
| `/videos` | 영상 관리 |
| `/api/video` | Asterisk 이벤트 수신 |

현재 관리자 라우트에는 `auth` middleware가 일관되게 적용되어 있지 않다. 따라서 관리자 URL 체계를 변경할 때 단순 URL 변경이 아니라 인증/권한 middleware 정비도 함께 필요하다.

### 3.3 재생 화면

다음 파일에 관리자/수동 입력 UI가 남아 있다.

| 파일 | 현재 상태 |
| --- | --- |
| `resources/views/layouts/mvsHeader.blade.php` | 우측 상단 관리자/수동입력 아이콘 존재 |
| `resources/views/layouts/mvsPcHeader.blade.php` | 우측 상단 관리자/수동입력 아이콘 존재 |
| `resources/views/mvs/index.blade.php` | 수동 전화번호 입력 UI, 관리자 링크, 수동 조회 JS 존재 |
| `resources/views/mvs/pc.blade.php` | 수동 전화번호 입력 UI, 관리자 링크, 수동 조회 JS 존재 |

Socket 수신 로직도 현재는 단말기 식별 없이 `videos` 이벤트를 받으면 모든 화면에서 재생 가능한 구조다. `terminalKey` 기준 필터링이 필요하다.

또한 `resources/views/mvs/index.blade.php`에는 Socket 서버 주소가 하드코딩되어 있고, `resources/views/mvs/pc.blade.php`도 script 로드는 `SOCKET_HOST`를 쓰지만 실제 `io.connect()`에는 하드코딩 IP가 남아 있다.

### 3.4 관리자 화면

관리자 화면은 `layouts.index`에서 공통 헤더를 include하고, 대부분 전역 route name 또는 하드코딩 URL을 사용한다.

예:

| 파일 | 현재 URL 사용 방식 |
| --- | --- |
| `resources/views/layouts/header.blade.php` | `/dashboard`, `route('customers.index')`, `route('videos.index')` |
| `resources/views/layouts/mobileHeader.blade.php` | `/dashboard`, `route('customers.index')`, `route('videos.index')` |
| `resources/views/customers/index.blade.php` | `/customers/{id}/...` 하드코딩 |
| `resources/views/videos/index.blade.php` | `/videos/{id}/edit`, `/videos/{id}` 하드코딩 |
| `resources/views/customers/video.blade.php` | `/customers/{id}/video`, `/customers/video/{id}` 하드코딩 |

따라서 `/{siteCode}/admin/...` URL을 도입하려면 라우트만 바꾸는 것으로는 부족하다. 관리자 context에 따라 URL을 생성하는 공통 헬퍼 또는 View 데이터 전달 방식이 필요하다.

### 3.5 API 및 Asterisk

현재 `/api/video`는 `tel`, `play`만 사용한다.

| 파라미터 | 현재 사용 여부 |
| --- | --- |
| `tel` | 사용 |
| `play` | 사용 |
| `site_code` | 없음 |
| `terminal_no` | 없음 |

Asterisk 스크립트도 현재는 Laravel API에 `tel`, `play`만 전달한다.

| 파일 | 현재 동작 |
| --- | --- |
| `asterisk/mvs_GetUserInform.php` | 전화번호 기준 고객 조회 후 `/api/video?tel=...` 호출 |
| `asterisk/mvs_GetVideosInform.php` | 선택 영상 번호를 `play`로 전달 |

`m_TdnNumber`로 070 번호를 읽기는 하지만 사이트 식별에는 사용하지 않는다. 단말기 번호 DTMF 입력 단계도 아직 없다.

## 4. 변경 범위 확정

### 4.1 필수 1차 구현 범위

다중 사이트/단말기 및 관리자 URL 요구사항을 실제로 만족하려면 다음 작업이 필수다.

#### 4.1.1 DB migration

- `mvs_sites` 테이블 추가
- `mvs_terminals` 테이블 추가
- `users.site_id` 추가
- `users.role` 추가
- `customers.site_id` 추가
- `videos.site_id` 추가
- 기존 데이터용 기본 사이트 생성 및 backfill 정책 추가
- 고객 전화번호 검증을 전역 unique에서 사이트별 unique로 변경

#### 4.1.2 모델

- `app/Models/MvsSite.php` 추가
- `app/Models/MvsTerminal.php` 추가
- `User::site()` 관계 추가
- `Customer::site()` 관계 추가
- `Video::site()` 관계 추가
- `User::isSuperAdmin()` 추가
- `User::isSiteAdmin()` 추가

#### 4.1.3 Middleware

- `BindSiteFromRoute`
- `EnsureSuperAdmin`
- `EnsureSiteAdmin`
- `app/Http/Kernel.php` route middleware 등록

#### 4.1.4 라우트

아래 URL 체계로 재구성한다.

| URL | 역할 |
| --- | --- |
| `/admin` | 슈퍼관리자 로그인 |
| `/admin/dashboard` | 슈퍼관리자 대시보드 |
| `/admin/customers` | 전체/사이트별 고객 관리 |
| `/admin/videos` | 전체/사이트별 영상 관리 |
| `/admin/sites` | 사이트 관리 |
| `/admin/site-admins` | 사이트 관리자 계정 관리 |
| `/{siteCode}/admin` | 사이트관리자 로그인 |
| `/{siteCode}/admin/dashboard` | 사이트관리자 대시보드 |
| `/{siteCode}/admin/customers` | 해당 사이트 고객 관리 |
| `/{siteCode}/admin/videos` | 해당 사이트 영상 관리 |
| `/{siteCode}-{terminalNo}` | 재생 단말기 화면 |

기존 URL은 제거 또는 redirect 정책을 명확히 해야 한다.

#### 4.1.5 인증/권한

- 슈퍼관리자 로그인과 사이트관리자 로그인 분리
- 슈퍼관리자만 전체 사이트/사이트 관리자 관리 가능
- 사이트관리자는 자기 `site_id`와 URL의 `siteCode`가 일치할 때만 접근 가능
- 고객/영상 수정/삭제/매칭 시에도 사이트 범위 검증 필요

#### 4.1.6 관리자 화면

- 전역 route name과 하드코딩 URL 제거 또는 context-aware URL로 변경
- 슈퍼관리자 메뉴와 사이트관리자 메뉴 분기
- 슈퍼관리자는 사이트 선택 UI 제공
- 사이트관리자는 현재 사이트 context 유지
- 고객/영상 등록 시 사이트관리자는 서버에서 `site_id` 강제 설정
- 슈퍼관리자는 등록 시 `site_id` 선택 필수

#### 4.1.7 재생 화면

- 우측 상단 관리자/수동 입력 아이콘 제거
- 수동 전화번호 입력 UI 제거
- 관리자 페이지 링크 제거
- `telRetrieve()`, `telRetrieveDemo()` 제거
- `/`, `*`, `Enter` shortcut 중 수동 전화번호 입력 관련 처리 제거
- Socket 이벤트 수신 시 `terminalKey` 기준 필터링
- Socket server URL 하드코딩 제거

#### 4.1.8 API

`/api/video`는 다음 파라미터를 기준으로 검증해야 한다.

| 파라미터 | 필수 여부 | 설명 |
| --- | --- | --- |
| `tel` | 필수 | 고객 전화번호 |
| `site_code` | 필수 | 3자리 사이트 코드 |
| `terminal_no` | 필수 | 사이트 내 단말기 번호 |
| `play` | 선택 | 영상 선택 번호 |

응답/Redis payload에는 다음 값을 포함해야 한다.

- `siteCode`
- `terminalNo`
- `terminalKey`

#### 4.1.9 Asterisk

- 수신 070 번호로 `site_code` 식별
- DTMF 1자리로 `terminal_no` 입력
- Laravel API 호출 시 `tel`, `site_code`, `terminal_no` 전달
- 영상 선택 DTMF가 필요한 경우 단말기 번호 DTMF와 영상 번호 DTMF를 분리

#### 4.1.10 테스트

최소 테스트 범위는 다음과 같다.

- 슈퍼관리자 로그인/접근
- 사이트관리자 로그인/접근
- 사이트관리자 타 사이트 접근 차단
- 고객 목록 사이트별 필터링
- 영상 목록 사이트별 필터링
- 고객-영상 매칭 사이트 범위 검증
- `/api/video`의 `site_code`, `terminal_no` 검증
- Redis payload의 `terminalKey` 포함 여부
- 단말기 화면의 Socket 필터링

### 4.2 후속 확장 범위

아래 작업은 1차 필수 구현 이후 안정화 단계에서 진행할 수 있다.

- 슈퍼관리자 사이트 관리 UI 고도화
- 사이트별 단말기 수 자동 생성/관리
- 사이트별 대표 전화번호를 DB 기반으로 Asterisk에서 조회
- 영상 다건 보유 고객에 대한 재생 정책 정교화
  - 첫 번째 영상 자동 재생
  - 전체 순차 재생
  - 추가 DTMF로 영상 선택
- 기존 `/`, `/pc`에 대한 안내 페이지 또는 redirect UX 개선
- `/api/video` 호출 인증/서명 또는 내부망 제한 추가
- `mysql_management.mvs_video`와의 동기화 정책 재정리
- Blade 중복 JS를 공통 asset으로 분리

## 5. 구현 순서 제안

실제 작업은 다음 순서가 안전하다.

1. DB migration 및 seed/backfill 추가
2. 모델 관계와 role helper 추가
3. Middleware 추가 및 등록
4. 라우트 재구성
5. AuthController 로그인 분리
6. 관리자 controller 사이트 필터링 적용
7. 관리자 Blade URL/context 정리
8. 재생 화면 UI 제거
9. 단말기 URL 및 Socket terminalKey 필터링 적용
10. `/api/video` site/terminal 검증 적용
11. Asterisk API 호출 파라미터 변경
12. Feature 테스트 추가

## 6. 결론

`multi-site-admin-url-change-plan.md`의 방향은 현재 요구사항과 일치한다. 다만 실제 코드에는 해당 구조가 아직 거의 반영되어 있지 않다.

따라서 이번 변경은 단순 URL 수정이 아니라 다음 세 축을 함께 적용하는 작업으로 확정해야 한다.

1. DB에 사이트/단말기/소유권 정보 추가
2. 관리자 URL과 권한을 슈퍼관리자/사이트관리자로 분리
3. 재생 단말기와 Asterisk/API/Socket 흐름을 `siteCode-terminalNo` 기준으로 분리

이 중 하나라도 빠지면 사이트별 데이터 격리 또는 단말기별 재생 격리가 불완전해진다.
