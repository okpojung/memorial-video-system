# 파일명: doc/mvs-playback-management-implementation.md
# 버전정보: v1.0.0
# 생성일: 2026-06-12
# 작성 AI 모델명: GPT-5.5
# 관련 구현 브랜치: cursor/mvs-playback-management-04ed
# 관련 PR: https://github.com/okpojung/memorial-video-system/pull/4

# MVS 추모영상 재생관리 기능 구현 문서

## 1. 개요

사이트관리자가 `mvs.baro.me/admin`으로 로그인한 뒤 관리자 메뉴에서 **추모영상 재생관리** 화면에 접근하여, PC 모니터에서 4분할 또는 9분할로 단말기(TV) 재생 상태를 확인하고 특정 단말기에 선택한 추모영상을 재생하도록 하는 기능이다.

구현 코드는 `cursor/mvs-playback-management-04ed` 브랜치에 작성되어 있으며, main 반영 전 검토용 PR은 다음과 같다.

- PR: https://github.com/okpojung/memorial-video-system/pull/4

## 2. 구현 코드 위치

| 구분 | 파일 |
| --- | --- |
| 재생관리 컨트롤러 | `app/Http/Controllers/PlaybackManagementController.php` |
| 관리자 재생관리 화면 | `resources/views/playback-management/index.blade.php` |
| 라우트 | `routes/web.php` |
| PC 관리자 메뉴 | `resources/views/layouts/header.blade.php` |
| 모바일 관리자 메뉴 | `resources/views/layouts/mobileHeader.blade.php` |
| 단말기 컨트롤러 보강 | `app/Http/Controllers/MvsController.php` |
| 기본 단말기 화면 | `resources/views/mvs/index.blade.php` |
| PC 단말기 화면 | `resources/views/mvs/pc.blade.php` |

## 3. 주요 기능

### 3.1 관리자 재생관리 화면

추가 화면:

```text
resources/views/playback-management/index.blade.php
```

기능:

- TV 1~9 단말기 상태 카드 표시
- 4분할/9분할 전환
- 단말기별 현재 재생 상태 표시
- 단말기별 현재 재생 영상명 표시
- 단말기별 상태 갱신 시간 표시
- 선택한 단말기에 영상 재생 명령 전송

### 3.2 영상 검색

검색 대상:

- 유족 휴대폰번호
- 유족명
- 고인명

검색은 `customers`, `customer_video`, `videos`를 join하여 수행한다.

관련 컨트롤러 메서드:

```php
PlaybackManagementController::search()
```

라우트:

```php
GET /playback-management/search
```

### 3.3 선택 단말기 재생 명령

관리자가 검색된 영상을 선택하고 특정 TV를 선택한 뒤 “선택 TV에 재생” 버튼을 누르면 Redis `videos` 채널로 재생 명령을 publish한다.

관련 컨트롤러 메서드:

```php
PlaybackManagementController::play()
```

라우트:

```php
POST /playback-management/play
```

Redis payload 주요 값:

```json
{
  "eventType": "playbackCommand",
  "statusCode": 200,
  "terminalKey": "terminal-1",
  "terminalNo": 1,
  "channel": "videos"
}
```

### 3.4 단말기별 재생 필터링

단말기 화면은 URL query string의 `terminal` 값을 기준으로 자기 단말기 key를 가진다.

예:

```text
/pc?terminal=1
/?terminal=1
```

위 URL은 내부적으로 다음 단말기 key로 처리된다.

```text
terminal-1
```

단말기 화면은 Redis/Socket `videos` 이벤트를 수신하더라도 `terminalKey`가 자기 값과 다르면 재생하지 않는다.

### 3.5 단말기 상태 송신

단말기 화면은 다음 상태를 `/terminal/status`로 전송한다.

| 상태 | 의미 |
| --- | --- |
| `online` | 단말기 화면 최초 접속 |
| `heartbeat` | 주기적 생존 신호 |
| `playing` | 영상 재생 시작 |
| `ended` | 영상 재생 종료 |

관련 컨트롤러 메서드:

```php
PlaybackManagementController::terminalStatus()
```

라우트:

```php
POST /terminal/status
```

상태는 Redis `videos` 채널에 다시 publish되고, 재생관리 화면은 이 이벤트를 수신해 모니터링 카드에 반영한다.

## 4. 추가/변경 라우트

구현 브랜치 기준 추가/변경된 라우트는 다음과 같다.

```php
Route::GET('/admin', [AuthController::class, 'index'])->name('login');

Route::GET('/playback-management', [PlaybackManagementController::class, 'index'])
    ->middleware('auth')
    ->name('playback-management.index');

Route::GET('/playback-management/search', [PlaybackManagementController::class, 'search'])
    ->middleware('auth')
    ->name('playback-management.search');

Route::POST('/playback-management/play', [PlaybackManagementController::class, 'play'])
    ->middleware('auth')
    ->name('playback-management.play');

Route::POST('/terminal/status', [PlaybackManagementController::class, 'terminalStatus'])
    ->name('terminal.status');
```

## 5. 관리자 메뉴

다음 레이아웃에 **추모영상 재생관리** 메뉴가 추가되었다.

- `resources/views/layouts/header.blade.php`
- `resources/views/layouts/mobileHeader.blade.php`

메뉴 URL:

```php
route('playback-management.index')
```

## 6. 사용 방법

### 6.1 관리자 접속

```text
https://mvs.baro.me/admin
```

로그인 후 관리자 메뉴에서 **추모영상 재생관리**를 선택한다.

### 6.2 단말기 접속

각 TV 또는 단말기 브라우저에서 다음처럼 접속한다.

```text
https://mvs.baro.me/pc?terminal=1
https://mvs.baro.me/pc?terminal=2
...
https://mvs.baro.me/pc?terminal=9
```

기본 모바일/전체화면 단말기 화면을 사용할 경우:

```text
https://mvs.baro.me/?terminal=1
```

### 6.3 영상 재생

1. 관리자 화면에서 4분할 또는 9분할 모니터링을 선택한다.
2. 재생할 TV를 선택한다.
3. 유족 휴대폰번호, 유족명, 고인명 중 하나로 검색한다.
4. 조회된 영상을 선택한다.
5. “선택 TV에 재생” 버튼을 클릭한다.
6. 해당 `terminalKey`를 가진 단말기에서만 영상이 재생된다.

## 7. 검증 상태

구현 브랜치에서 수행한 검증:

```text
git diff main --check
```

결과:

```text
통과
```

제한 사항:

- 현재 Cloud Agent 환경에는 `php` 실행 파일이 없어 `php -l`, `php artisan route:list` 검증은 수행하지 못했다.

## 8. 후속 보완 사항

다중 사이트/단말기 DB 구조가 도입되면 현재 `terminal-1`~`terminal-9` 방식은 다음 구조로 확장하는 것이 좋다.

```text
{siteCode}-{terminalNo}
예: A01-1, A01-2
```

후속 보완 대상:

- `mvs_sites`, `mvs_terminals` 기반 단말기 목록 조회
- 사이트관리자는 자기 사이트 단말기만 모니터링
- 슈퍼관리자는 전체 사이트 단말기 모니터링
- `/terminal/status` 인증 또는 내부망 제한
- Redis/Socket bridge 장애 시 UI 표시
- 재생 명령 이력 저장
- 단말기별 마지막 재생 영상 DB 저장
