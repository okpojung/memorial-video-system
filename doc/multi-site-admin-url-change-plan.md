# 다중 사이트/단말기 및 관리자 URL 체계 수정 계획

## 1. 요구사항 정리

이번 변경의 핵심 요구사항은 다음과 같습니다.

1. **MVS 재생 화면 우측 상단 UI 제거**
   - 오른쪽 상단의 관리자 페이지 아이콘 제거
   - 오른쪽 상단의 수동 전화번호 입력 기능 제거
   - 단말기 화면은 전화/DTMF/Socket 이벤트에 의해 재생만 수행

2. **사이트별 관리자 URL 통일**
   - 사이트 관리자 URL:
     - `https://mvs.baro.me/XXX/admin`
     - `XXX`는 알파뉴메릭 3자리 사이트 코드
     - 예: `https://mvs.baro.me/A01/admin`
   - 사이트 관리자는 자기 사이트의 고객/영상/단말기 정보만 관리

3. **슈퍼 관리자 URL 통일**
   - 슈퍼 관리자 URL:
     - `https://mvs.baro.me/admin`
   - 슈퍼 관리자는 전체 사이트의 고객/영상 등록, 수정, 삭제 가능
   - 슈퍼 관리자는 사이트 관리자 계정 추가, 수정, 삭제 가능

4. **단말기 URL 체계**
   - 재생 단말기 URL:
     - `https://mvs.baro.me/XXX-N`
   - `XXX`: 사이트 코드 3자리
   - `N`: 사이트 내 단말기 번호 1자리
   - 예:
     - `https://mvs.baro.me/A01-1`
     - `https://mvs.baro.me/A01-2`
     - `https://mvs.baro.me/A01-3`

5. **전화/DTMF 처리**
   - 사이트별 대표 전화번호를 부여
   - 전화 연결 후 DTMF 1자리로 사이트 내 단말기 번호를 수신
   - Asterisk가 Laravel API 호출 시 `site_code`, `terminal_no`를 함께 전달

---

## 2. 현재 코드 기준 영향 범위

현재 저장소는 사이트 구분 없이 아래처럼 전역 라우트가 구성되어 있습니다.

| 현재 경로 | 현재 역할 | 문제점 |
| --- | --- | --- |
| `/` | MVS 재생 화면 | 사이트/단말기 구분 없음 |
| `/pc` | PC 재생 화면 | 사이트/단말기 구분 없음 |
| `/login` | 관리자 로그인 | 사이트 관리자/슈퍼관리자 구분 없음 |
| `/dashboard` | 관리자 대시보드 | 사이트별 데이터 권한 없음 |
| `/customers` | 고객 관리 | 전체 고객 조회 가능 |
| `/videos` | 영상 관리 | 전체 영상 조회 가능 |
| `/users` | 사용자 등록 | 사이트 관리자 관리 구조 없음 |
| `/api/video` | Asterisk 이벤트 수신 | 전화번호만 기준으로 Redis broadcast |

현재 우측 상단 UI는 아래 파일에 분산되어 있습니다.

| 파일 | 현재 역할 |
| --- | --- |
| `resources/views/layouts/mvsHeader.blade.php` | 모바일/기본 재생 화면 우측 상단 아이콘 출력 |
| `resources/views/layouts/mvsPcHeader.blade.php` | PC 재생 화면 우측 상단 아이콘 출력 |
| `resources/views/mvs/index.blade.php` | 관리자 페이지 링크 패널, 수동 전화번호 입력 패널, Socket 재생 로직 |
| `resources/views/mvs/pc.blade.php` | PC용 관리자 링크 패널, 수동 전화번호 입력 패널, Socket 재생 로직 |

---

## 3. 권장 데이터 모델 변경

사이트별 관리자와 슈퍼관리자를 분리하려면 DB에 사이트 소유권 정보가 필요합니다.

### 3.1 신규 테이블: `mvs_sites`

사이트 기본 정보를 관리합니다.

```php
Schema::create('mvs_sites', function (Blueprint $table) {
    $table->id();
    $table->string('code', 3)->unique()->comment('사이트 코드: A01 등');
    $table->string('name', 100)->comment('사이트명');
    $table->string('phone', 20)->nullable()->comment('사이트 대표/ARS 전화번호');
    $table->boolean('active')->default(true)->comment('사용 여부');
    $table->timestamps();
});
```

예시 데이터:

| code | name | phone |
| --- | --- | --- |
| `A01` | 서울강남보각사 | `070-7540-1488` |
| `B01` | 부산보각사 | 사이트 전화번호 |

### 3.2 신규 테이블: `mvs_terminals`

사이트 내 단말기 정보를 관리합니다.

```php
Schema::create('mvs_terminals', function (Blueprint $table) {
    $table->id();
    $table->foreignId('site_id')->constrained('mvs_sites')->onDelete('cascade');
    $table->unsignedTinyInteger('terminal_no')->comment('사이트 내 단말기 번호: 1~9');
    $table->string('name', 100)->nullable()->comment('단말기명: 1층 TV 등');
    $table->boolean('active')->default(true)->comment('사용 여부');
    $table->timestamps();

    $table->unique(['site_id', 'terminal_no']);
});
```

### 3.3 `users` 테이블 변경

현재 `users`는 `role_id`만 있고 사이트 정보가 없습니다.

권장 변경:

```php
Schema::table('users', function (Blueprint $table) {
    $table->foreignId('site_id')->nullable()->after('id')->constrained('mvs_sites')->nullOnDelete();
    $table->string('role', 30)->default('site_admin')->after('role_id')->comment('super_admin 또는 site_admin');
    $table->index(['site_id', 'role']);
});
```

권한 기준:

| role | site_id | 권한 |
| --- | --- | --- |
| `super_admin` | `null` | 모든 사이트 관리 |
| `site_admin` | 사이트 ID | 해당 사이트만 관리 |

기존 `role_id`를 유지하려면 다음처럼 매핑해도 됩니다.

| role_id | 의미 |
| --- | --- |
| `1` | 슈퍼관리자 |
| `2` | 사이트관리자 |

다만 코드 가독성을 위해 `role` 컬럼 추가를 권장합니다.

### 3.4 `customers` 테이블 변경

고객은 사이트별로 분리되어야 합니다.

```php
Schema::table('customers', function (Blueprint $table) {
    $table->foreignId('site_id')->nullable()->after('id')->constrained('mvs_sites')->nullOnDelete();
    $table->index(['site_id', 'tel']);
});
```

현재 `tel`은 전역 unique로 검증됩니다. 앞으로는 **사이트별 전화번호 unique**가 되어야 합니다.

### 3.5 `videos` 테이블 변경

영상도 사이트별 관리가 필요합니다.

```php
Schema::table('videos', function (Blueprint $table) {
    $table->foreignId('site_id')->nullable()->after('id')->constrained('mvs_sites')->nullOnDelete();
    $table->index(['site_id', 'created_at']);
});
```

---

## 4. 수정해야 할 파일 목록

### 4.1 라우트

| 파일 | 수정 내용 |
| --- | --- |
| `routes/web.php` | `/admin`, `/{siteCode}/admin`, `/{siteCode}-{terminalNo}` 라우트 추가 및 기존 관리자 라우트 정리 |
| `routes/api.php` | `/api/video` 호출 파라미터에 `site_code`, `terminal_no` 반영 |

### 4.2 컨트롤러

| 파일 | 수정 내용 |
| --- | --- |
| `app/Http/Controllers/MvsController.php` | 단말기 URL 처리, API 수신 시 사이트/단말기 식별, Redis payload 변경 |
| `app/Http/Controllers/AuthController.php` | 슈퍼관리자 로그인과 사이트관리자 로그인 분리 |
| `app/Http/Controllers/CustomerController.php` | 사이트별 고객 조회/등록/수정/삭제 제한 |
| `app/Http/Controllers/VideoController.php` | 사이트별 영상 조회/등록/수정/삭제 제한 |
| `app/Http/Controllers/UserController.php` | 슈퍼관리자만 사이트 관리자 계정 관리 가능하도록 변경 |

### 4.3 모델

| 파일 | 수정 내용 |
| --- | --- |
| `app/Models/User.php` | `site()` 관계, `isSuperAdmin()`, `isSiteAdmin()` 추가 |
| `app/Models/Customer.php` | `site()` 관계 추가 |
| `app/Models/Video.php` | `site()` 관계 추가 |
| 신규 `app/Models/MvsSite.php` | 사이트 모델 추가 |
| 신규 `app/Models/MvsTerminal.php` | 단말기 모델 추가 |

### 4.4 Blade 화면

| 파일 | 수정 내용 |
| --- | --- |
| `resources/views/layouts/mvsHeader.blade.php` | 오른쪽 상단 관리자/수동입력 아이콘 제거 |
| `resources/views/layouts/mvsPcHeader.blade.php` | 오른쪽 상단 관리자/수동입력 아이콘 제거 |
| `resources/views/mvs/index.blade.php` | 관리자 링크 패널 제거, 수동 전화번호 입력 UI 제거, 단말기 필터 유지 |
| `resources/views/mvs/pc.blade.php` | 관리자 링크 패널 제거, 수동 전화번호 입력 UI 제거, 단말기 필터 유지 |
| `resources/views/auth/login.blade.php` | 사이트관리자/슈퍼관리자 로그인 action 분리 |
| `resources/views/layouts/header.blade.php` | 관리자 메뉴 URL을 `/admin/...` 또는 `/{siteCode}/admin/...`로 변경 |
| `resources/views/customers/*.blade.php` | 사이트 선택/표시/권한별 UI 처리 |
| `resources/views/videos/*.blade.php` | 사이트 선택/표시/권한별 UI 처리 |
| 신규 `resources/views/admin/sites/*.blade.php` | 슈퍼관리자 사이트 관리 화면 |
| 신규 `resources/views/admin/site-admins/*.blade.php` | 슈퍼관리자 사이트 관리자 관리 화면 |

### 4.5 Middleware

| 신규 파일 | 역할 |
| --- | --- |
| `app/Http/Middleware/EnsureSuperAdmin.php` | 슈퍼관리자만 접근 허용 |
| `app/Http/Middleware/EnsureSiteAdmin.php` | 사이트관리자 또는 슈퍼관리자 접근 허용 |
| `app/Http/Middleware/BindSiteFromRoute.php` | URL의 `siteCode`를 실제 사이트 모델로 변환/검증 |

### 4.6 Asterisk

| 파일 | 수정 내용 |
| --- | --- |
| `asterisk/mvs_GetUserInform.php` | 사이트 전화번호로 site_code 식별, DTMF 1자리로 terminal_no 수신, API 호출에 전달 |
| `asterisk/mvs_GetVideosInform.php` | 필요한 경우 영상 선택 DTMF와 단말기 DTMF 파라미터 분리 |

---

## 5. `routes/web.php` 수정 예시

기존 전역 관리자 라우트는 사이트/슈퍼관리자 그룹으로 분리하는 것이 좋습니다.

```php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\MvsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| MVS terminal screens
|--------------------------------------------------------------------------
*/
Route::GET('/{siteCode}-{terminalNo}', [MvsController::class, 'terminal'])
    ->where([
        'siteCode' => '[A-Za-z0-9]{3}',
        'terminalNo' => '[0-9]',
    ])
    ->name('mvs.terminal');

/*
|--------------------------------------------------------------------------
| Super admin
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('super.')->group(function () {
    Route::GET('/', [AuthController::class, 'superLoginForm'])->name('login');
    Route::POST('/login', [AuthController::class, 'superLogin'])->name('login.submit');
    Route::POST('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware(['auth', 'super.admin'])->group(function () {
        Route::GET('/dashboard', [MvsController::class, 'superDashboard'])->name('dashboard');

        Route::resource('/sites', SiteController::class);
        Route::resource('/site-admins', SiteAdminController::class);

        Route::GET('/customers', [CustomerController::class, 'index'])->name('customers.index');
        Route::POST('/customers', [CustomerController::class, 'store'])->name('customers.store');
        Route::GET('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
        Route::GET('/customers/{id}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
        Route::PATCH('/customers/{id}', [CustomerController::class, 'update'])->name('customers.update');
        Route::DELETE('/customers/{id}', [CustomerController::class, 'destroy'])->name('customers.destroy');

        Route::GET('/videos', [VideoController::class, 'index'])->name('videos.index');
        Route::POST('/videos', [VideoController::class, 'store'])->name('videos.store');
        Route::GET('/videos/create', [VideoController::class, 'create'])->name('videos.create');
        Route::GET('/videos/{id}/edit', [VideoController::class, 'edit'])->name('videos.edit');
        Route::PATCH('/videos/{id}', [VideoController::class, 'update'])->name('videos.update');
        Route::DELETE('/videos/{id}', [VideoController::class, 'destroy'])->name('videos.destroy');
    });
});

/*
|--------------------------------------------------------------------------
| Site admin
|--------------------------------------------------------------------------
*/
Route::prefix('{siteCode}/admin')
    ->where(['siteCode' => '[A-Za-z0-9]{3}'])
    ->name('site.')
    ->middleware(['bind.site'])
    ->group(function () {
        Route::GET('/', [AuthController::class, 'siteLoginForm'])->name('login');
        Route::POST('/login', [AuthController::class, 'siteLogin'])->name('login.submit');
        Route::POST('/logout', [AuthController::class, 'logout'])->name('logout');

        Route::middleware(['auth', 'site.admin'])->group(function () {
            Route::GET('/dashboard', [MvsController::class, 'siteDashboard'])->name('dashboard');

            Route::GET('/customers', [CustomerController::class, 'index'])->name('customers.index');
            Route::POST('/customers', [CustomerController::class, 'store'])->name('customers.store');
            Route::GET('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
            Route::GET('/customers/{id}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
            Route::PATCH('/customers/{id}', [CustomerController::class, 'update'])->name('customers.update');
            Route::DELETE('/customers/{id}', [CustomerController::class, 'destroy'])->name('customers.destroy');

            Route::GET('/videos', [VideoController::class, 'index'])->name('videos.index');
            Route::POST('/videos', [VideoController::class, 'store'])->name('videos.store');
            Route::GET('/videos/create', [VideoController::class, 'create'])->name('videos.create');
            Route::GET('/videos/{id}/edit', [VideoController::class, 'edit'])->name('videos.edit');
            Route::PATCH('/videos/{id}', [VideoController::class, 'update'])->name('videos.update');
            Route::DELETE('/videos/{id}', [VideoController::class, 'destroy'])->name('videos.destroy');
        });
    });
```

주의:

- `/{siteCode}/admin`과 `/{siteCode}-{terminalNo}`가 충돌하지 않도록 라우트 패턴을 명확히 해야 합니다.
- 기존 `/login`, `/dashboard`, `/customers`, `/videos`는 호환 유지가 필요하지 않다면 제거하거나 새 URL로 redirect해야 합니다.

---

## 6. 재생 화면 UI 제거

### 6.1 `resources/views/layouts/mvsHeader.blade.php`

현재 오른쪽 상단 아이콘:

```blade
<div class="flex-1 cursor-pointer text-right flex justify-end items-center z-30">
    <svg onclick="customerListClick()" ...>
        ...
    </svg>
    <svg onclick="videoListClick()" ...>
        ...
    </svg>
</div>
```

수정:

```blade
<div class="flex-1"></div>
```

또는 우측 여백 자체가 필요 없으면 해당 `<div>` 전체를 삭제합니다.

### 6.2 `resources/views/layouts/mvsPcHeader.blade.php`

현재 오른쪽 상단 아이콘:

```blade
<div class="z-40">
    <div class="cursor-pointer flex items-center z-30">
        <svg onclick="customerListClick()" ...></svg>
        <svg onclick="videoListClick()" ...></svg>
    </div>
</div>
```

수정:

```blade
{{-- 재생 단말 화면에서는 관리자/수동 입력 UI를 노출하지 않음 --}}
```

또는 해당 블록 전체 삭제.

### 6.3 `resources/views/mvs/index.blade.php`

삭제 대상 1: 수동 전화번호 입력 패널

```blade
<div id="videoNav" class="relative z-40 " role="dialog" aria-modal="true">
    ...
    <input id="tel" ... placeholder="전화번호를 입력하세요" />
    <div onclick="telRetrieve()" ...>
    <div onclick="telRetrieveDemo()" ...>
    ...
</div>
```

삭제 대상 2: 관리자 페이지 링크 패널

```blade
<div id="customerNav" class="relative z-40" role="dialog" aria-modal="true">
    ...
    <a href="{{route('auth.index')}}">
        <span class="flex-1">관리자 페이지</span>
    </a>
    ...
</div>
```

삭제 대상 3: 수동 입력 관련 JavaScript

```javascript
function telRetrieve() { ... }
function telRetrieveDemo() { ... }
```

삭제 또는 변경 대상 4: 키보드 shortcut

```javascript
case '*' :
    telRetrieveDemo();
    break;
case '/' :
    document.querySelector('#tel').focus();
    break;
case 'Enter' :
    if(document.activeElement.id === 'tel') {
        telRetrieve();
    }
    break;
```

수정 후에도 Socket 이벤트 기반 재생 로직은 유지합니다.

```javascript
socket.on('videos', function(data) {
    if (terminalKey && data.terminalKey !== terminalKey) {
        return;
    }

    if (data.statusCode === 200) {
        videoReset();
        const videos = JSON.parse(data.videos);
        const customer = JSON.parse(data.customer);

        data.videos = videos;
        data.customer = customer;

        if (videos.length === 1) {
            singlePlay(data);
            play(videos[0]);
        } else {
            // 정책 필요:
            // 1) 첫 번째 영상 자동 재생
            // 2) 전체 영상 순차 재생
            // 3) Asterisk에서 영상 선택 DTMF까지 받은 후 play 파라미터로 특정 영상만 전달
            multiPlay(data);
        }
    }
});
```

### 6.4 `resources/views/mvs/pc.blade.php`

`index.blade.php`와 동일하게 아래 항목을 제거합니다.

- `videoNav`의 전화번호 입력 UI
- `customerNav`의 관리자 페이지 링크
- `telRetrieve()`
- `telRetrieveDemo()`
- `/`, `*`, `Enter` shortcut 중 수동 전화번호 입력 관련 처리

---

## 7. 관리자 인증 구조 변경

### 7.1 `AuthController.php`

현재 로그인은 하나입니다.

```php
public function index(Request $request)
{
    if(Auth::check()) {
        return redirect()->intended('dashboard');
    }

    return view('auth.login');
}

public function login(Request $request)
{
    $credentials = $request->validate([
        'reg_id' => ['required'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('dashboard');
    }

    return back()->withErrors(['message'=>'아이디 또는 비밀번호가 일치하지 않습니다.'])->withInput();
}
```

권장 수정:

```php
public function superLoginForm()
{
    if (Auth::check() && Auth::user()->isSuperAdmin()) {
        return redirect()->route('super.dashboard');
    }

    return view('auth.login', [
        'loginAction' => route('super.login.submit'),
        'mode' => 'super',
        'site' => null,
    ]);
}

public function siteLoginForm($siteCode)
{
    $site = request()->attributes->get('site');

    if (Auth::check() && Auth::user()->isSiteAdmin() && Auth::user()->site_id === $site->id) {
        return redirect()->route('site.dashboard', ['siteCode' => $site->code]);
    }

    return view('auth.login', [
        'loginAction' => route('site.login.submit', ['siteCode' => $site->code]),
        'mode' => 'site',
        'site' => $site,
    ]);
}

public function superLogin(Request $request)
{
    $credentials = $request->validate([
        'reg_id' => ['required'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials) && Auth::user()->isSuperAdmin()) {
        $request->session()->regenerate();
        return redirect()->route('super.dashboard');
    }

    Auth::logout();

    return back()->withErrors(['message' => '슈퍼관리자 계정이 아니거나 로그인 정보가 일치하지 않습니다.'])->withInput();
}

public function siteLogin(Request $request, $siteCode)
{
    $site = $request->attributes->get('site');

    $credentials = $request->validate([
        'reg_id' => ['required'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)
        && Auth::user()->isSiteAdmin()
        && (int) Auth::user()->site_id === (int) $site->id) {
        $request->session()->regenerate();
        return redirect()->route('site.dashboard', ['siteCode' => $site->code]);
    }

    Auth::logout();

    return back()->withErrors(['message' => '해당 사이트 관리자 계정이 아니거나 로그인 정보가 일치하지 않습니다.'])->withInput();
}
```

### 7.2 `resources/views/auth/login.blade.php`

현재 form action:

```blade
<form id="form" method="POST" action="{{route('auth.login')}}" class="space-y-6">
```

수정:

```blade
<form id="form" method="POST" action="{{ $loginAction }}" class="space-y-6">
```

상단 제목 예:

```blade
@if(($mode ?? null) === 'super')
    <h1>슈퍼관리자 로그인</h1>
@else
    <h1>{{ $site->name }} 관리자 로그인</h1>
@endif
```

기존 데모 계정 버튼은 운영 환경에서 제거하는 것을 권장합니다.

---

## 8. Middleware 추가

### 8.1 `BindSiteFromRoute.php`

```php
namespace App\Http\Middleware;

use App\Models\MvsSite;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BindSiteFromRoute
{
    public function handle(Request $request, Closure $next): Response
    {
        $siteCode = strtoupper($request->route('siteCode'));

        $site = MvsSite::where('code', $siteCode)
            ->where('active', true)
            ->firstOrFail();

        $request->attributes->set('site', $site);

        return $next($request);
    }
}
```

### 8.2 `EnsureSuperAdmin.php`

```php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureSuperAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || !Auth::user()->isSuperAdmin()) {
            abort(403);
        }

        return $next($request);
    }
}
```

### 8.3 `EnsureSiteAdmin.php`

```php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureSiteAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            abort(403);
        }

        if (Auth::user()->isSuperAdmin()) {
            return $next($request);
        }

        $site = $request->attributes->get('site');

        if (!Auth::user()->isSiteAdmin() || (int) Auth::user()->site_id !== (int) $site->id) {
            abort(403);
        }

        return $next($request);
    }
}
```

`app/Http/Kernel.php`에 등록:

```php
protected $routeMiddleware = [
    // ...
    'super.admin' => \App\Http\Middleware\EnsureSuperAdmin::class,
    'site.admin' => \App\Http\Middleware\EnsureSiteAdmin::class,
    'bind.site' => \App\Http\Middleware\BindSiteFromRoute::class,
];
```

---

## 9. 모델 수정

### 9.1 `app/Models/User.php`

```php
public function site()
{
    return $this->belongsTo(MvsSite::class, 'site_id');
}

public function isSuperAdmin(): bool
{
    return $this->role === 'super_admin' || (int) $this->role_id === 1;
}

public function isSiteAdmin(): bool
{
    return $this->role === 'site_admin' || (int) $this->role_id === 2;
}
```

### 9.2 `app/Models/Customer.php`

```php
public function site()
{
    return $this->belongsTo(MvsSite::class, 'site_id');
}
```

### 9.3 `app/Models/Video.php`

```php
public function site()
{
    return $this->belongsTo(MvsSite::class, 'site_id');
}
```

---

## 10. 사이트별 데이터 필터링

### 10.1 공통 원칙

슈퍼관리자:

- 모든 사이트 조회 가능
- 필요 시 `site_id` 필터 선택 가능
- 고객/영상 등록 시 사이트 선택 필수

사이트관리자:

- 로그인 URL의 `siteCode`와 자기 `site_id`가 일치해야 함
- 자기 사이트의 고객/영상만 조회 가능
- 등록 시 `site_id`는 서버에서 강제 설정

### 10.2 `CustomerController.php`

예시:

```php
private function resolveSiteId(Request $request): ?int
{
    if (Auth::user()->isSuperAdmin()) {
        return $request->input('site_id');
    }

    return Auth::user()->site_id;
}

public function index(Request $request)
{
    $query = Customer::query();

    if (!Auth::user()->isSuperAdmin()) {
        $query->where('site_id', Auth::user()->site_id);
    } elseif ($request->filled('site_id')) {
        $query->where('site_id', $request->site_id);
    }

    // 검색 컬럼은 반드시 화이트리스트 사용
    $allowedSearchFields = ['name', 'tel', 'email'];
    $sfl = in_array($request->sfl, $allowedSearchFields, true) ? $request->sfl : 'name';

    if ($request->filled('stx')) {
        $query->where($sfl, 'LIKE', '%' . $request->stx . '%');
    }

    $customers = $query->orderBy('created_at', 'desc')->paginate($this->paginate);
}
```

등록 시:

```php
$input = $request->validate([
    'name' => ['required'],
    'tel' => ['required'],
    // ...
]);

$input['site_id'] = Auth::user()->isSuperAdmin()
    ? $request->validate(['site_id' => ['required', 'exists:mvs_sites,id']])['site_id']
    : Auth::user()->site_id;

Customer::create($input);
```

### 10.3 `VideoController.php`

영상 목록:

```php
$query = Video::query();

if (!Auth::user()->isSuperAdmin()) {
    $query->where('site_id', Auth::user()->site_id);
} elseif ($request->filled('site_id')) {
    $query->where('site_id', $request->site_id);
}
```

영상 등록:

```php
$siteId = Auth::user()->isSuperAdmin()
    ? $request->validate(['site_id' => ['required', 'exists:mvs_sites,id']])['site_id']
    : Auth::user()->site_id;

Video::create([
    'site_id' => $siteId,
    // 기존 필드 유지
]);
```

수정/삭제:

```php
$video = Video::findOrFail($id);

if (!Auth::user()->isSuperAdmin() && (int) $video->site_id !== (int) Auth::user()->site_id) {
    abort(403);
}
```

---

## 11. `MvsController.php` 수정

### 11.1 단말기 URL 처리

```php
public function terminal($siteCode, $terminalNo)
{
    $siteCode = strtoupper($siteCode);
    $terminalNo = (int) $terminalNo;

    $site = MvsSite::where('code', $siteCode)
        ->where('active', true)
        ->firstOrFail();

    $terminal = MvsTerminal::where('site_id', $site->id)
        ->where('terminal_no', $terminalNo)
        ->where('active', true)
        ->firstOrFail();

    if (!(boolean) MvsAuth::flag()) {
        echo '서비스가 중지되었습니다';
        exit();
    }

    $socket = $this->socketCheck() !== 0 ? true : false;

    return view('mvs.index', [
        'socket' => $socket,
        'site' => $site,
        'terminal' => $terminal,
        'siteCode' => $site->code,
        'terminalNo' => $terminal->terminal_no,
        'terminalKey' => "{$site->code}-{$terminal->terminal_no}",
    ]);
}
```

### 11.2 `/api/video` 수신 처리

현재는 `tel`만 사용합니다.

앞으로는 `site_code`, `terminal_no`가 필수입니다.

```php
public function receive(Request $request)
{
    try {
        $tel = $request->tel;
        $play = $request->play;
        $siteCode = strtoupper($request->site_code);
        $terminalNo = (int) $request->terminal_no;

        if (!$tel) {
            throw new Exception('전화번호 누락', Response::HTTP_UNAUTHORIZED);
        }

        if (!preg_match('/^[A-Z0-9]{3}$/', $siteCode)) {
            throw new Exception('사이트 코드가 올바르지 않습니다.', Response::HTTP_UNAUTHORIZED);
        }

        if ($terminalNo < 0 || $terminalNo > 9) {
            throw new Exception('단말기 번호가 올바르지 않습니다.', Response::HTTP_UNAUTHORIZED);
        }

        $site = MvsSite::where('code', $siteCode)->where('active', true)->firstOrFail();
        $terminal = MvsTerminal::where('site_id', $site->id)
            ->where('terminal_no', $terminalNo)
            ->where('active', true)
            ->firstOrFail();

        $customer = Customer::where('site_id', $site->id)
            ->where('tel', '=', $tel)
            ->first();

        if (empty($customer->id)) {
            throw new Exception('등록되지 않은 번호입니다', Response::HTTP_UNAUTHORIZED);
        }

        $videos = DB::table('customer_video')
            ->join('videos', 'customer_video.video_id', '=', 'videos.id')
            ->select(
                'customer_video.id',
                'videos.title',
                'videos.playtime_seconds',
                'videos.name',
                'videos.size',
                'videos.playtime_string',
                'videos.original_name',
                'videos.deceased',
                'videos.birth',
                'videos.video_tel',
                'videos.death',
                'videos.video_url',
                'videos.thumbnail_url',
                'videos.created_at'
            )
            ->where('customer_video.customer_id', '=', $customer->id)
            ->where('videos.site_id', '=', $site->id)
            ->get();

        if (count($videos) === 0) {
            throw new Exception('등록된 영상이 없습니다.', Response::HTTP_UNAUTHORIZED);
        }

        if ($play && count($videos) > 1) {
            $videoIndex = (int) $play - 1;

            if (!isset($videos[$videoIndex])) {
                throw new Exception('선택한 영상 번호가 올바르지 않습니다.', Response::HTTP_UNAUTHORIZED);
            }

            $videos = [$videos[$videoIndex]];
        }

        $rs['videos'] = json_encode($videos);
        $rs['statusCode'] = Response::HTTP_OK;
        $rs['customer'] = json_encode($customer);
        $rs['siteCode'] = $site->code;
        $rs['terminalNo'] = $terminal->terminal_no;
        $rs['terminalKey'] = "{$site->code}-{$terminal->terminal_no}";
    } catch (\Exception $e) {
        $rs['statusCode'] = $e->getCode();
        $rs['statusMessage'] = $e->getMessage();
    }

    $rs['channel'] = 'videos';
    Redis::publish('videos', json_encode($rs));
}
```

---

## 12. Socket 클라이언트 수정

`resources/views/mvs/index.blade.php`, `resources/views/mvs/pc.blade.php`에 공통 적용합니다.

```javascript
const terminalKey = @json($terminalKey ?? null);
const socketHost = @json(rtrim(env('SOCKET_HOST'), '/'));

var socket = io.connect(socketHost, {});

socket.on('videos', function(data) {
    if (terminalKey && data.terminalKey !== terminalKey) {
        return;
    }

    if (data.statusCode === 200) {
        videoReset();
        const videos = JSON.parse(data.videos);
        const customer = JSON.parse(data.customer);

        data.videos = videos;
        data.customer = customer;

        if (videos.length === 1) {
            singlePlay(data);
            play(videos[0]);
        } else {
            multiPlay(data);
        }
    }
});
```

현재 하드코딩:

```html
<script src="http://192.168.94.101:6001/socket.io/socket.io.js"></script>
```

수정:

```blade
<script src="{{ rtrim(env('SOCKET_HOST'), '/') }}/socket.io/socket.io.js"></script>
```

---

## 13. Asterisk 수정

### 13.1 `asterisk/mvs_GetUserInform.php`

현재는 전화번호만 API에 전달합니다.

```php
$fields = array(
    'tel' => $caller,
);
```

수정 방향:

1. 수신된 사이트 전화번호로 `site_code` 결정
2. DTMF 1자리 입력으로 `terminal_no` 결정
3. Laravel API에 세 값을 전달

예시:

```php
$m_TdnNumber = $agi->get_variable("m_TdnNumber");
$m_mvs_070 = $m_TdnNumber['data'];
$caller = $agi->request['agi_callerid'];

// 운영에서는 DB 테이블(mvs_sites.phone) 조회 권장
$siteMap = array(
    '07075401488' => 'A01',
    '07000000000' => 'B01',
);

$site_code = $siteMap[preg_replace('/[^0-9]/', '', $m_mvs_070)] ?? null;

if (!$site_code) {
    $result_code = "8999";
    throw new Exception("ARS번호에 대한 사이트 정보 없음");
}

// 안내 멘트 후 단말기 번호 1자리 입력
$dtmf = $agi->get_data('mvs/input_terminal', 5000, 1);
$terminal_no = $dtmf['result'];

if (!preg_match('/^[0-9]$/', $terminal_no)) {
    $result_code = "1017";
    throw new Exception("단말기 번호 오류");
}

$url = 'https://mvs.baro.me/api/video';
$fields = array(
    'tel' => $caller,
    'site_code' => $site_code,
    'terminal_no' => $terminal_no,
);

$url = $url . '?' . http_build_query($fields, '', '&');
```

### 13.2 `asterisk/mvs_GetVideosInform.php`

기존 영상 선택 DTMF를 유지해야 한다면 단말기 번호와 영상 번호를 분리해야 합니다.

예:

```php
$terminal_no = $agi->request['agi_arg_1'];
$play = $agi->request['agi_arg_2'];
```

API 호출:

```php
$fields = array(
    'tel' => $caller,
    'site_code' => $site_code,
    'terminal_no' => $terminal_no,
    'play' => $play,
);
```

주의:

- 이번 요구사항에서 DTMF 1자리는 단말기 번호로 정의되어 있습니다.
- 고객에게 영상이 여러 개인 경우, 영상 선택 정책을 별도로 결정해야 합니다.
  - 첫 번째 영상 자동 재생
  - 전체 영상 순차 재생
  - 단말기 번호 입력 후 추가 DTMF로 영상 번호 입력

---

## 14. 관리자 레이아웃 URL 수정

### 14.1 `resources/views/layouts/header.blade.php`

현재:

```blade
<a href="/dashboard">
<a href="{{route('customers.index')}}">
<a href="{{route('videos.index')}}">
```

수정 방향:

슈퍼관리자:

```blade
<a href="{{ route('super.dashboard') }}">
<a href="{{ route('super.customers.index') }}">
<a href="{{ route('super.videos.index') }}">
<a href="{{ route('super.sites.index') }}">
<a href="{{ route('super.site-admins.index') }}">
```

사이트관리자:

```blade
<a href="{{ route('site.dashboard', ['siteCode' => $site->code]) }}">
<a href="{{ route('site.customers.index', ['siteCode' => $site->code]) }}">
<a href="{{ route('site.videos.index', ['siteCode' => $site->code]) }}">
```

권한별 메뉴:

```blade
@if(Auth::user()->isSuperAdmin())
    {{-- 전체 사이트, 사이트 관리자 관리 메뉴 --}}
@else
    {{-- 현재 사이트 고객/영상 관리 메뉴 --}}
@endif
```

---

## 15. 슈퍼관리자 기능 추가

신규 컨트롤러 권장:

| 컨트롤러 | 역할 |
| --- | --- |
| `SiteController` | 사이트 추가/수정/삭제/활성화 |
| `SiteAdminController` | 사이트 관리자 추가/수정/삭제 |

### 15.1 `SiteController`

필요 기능:

- 사이트 목록
- 사이트 생성
- 사이트명/코드/대표전화/사용여부 수정
- 사이트 삭제 또는 비활성화
- 단말기 개수 관리

### 15.2 `SiteAdminController`

필요 기능:

- 사이트별 관리자 목록
- 관리자 생성
- 관리자 비밀번호 초기화/변경
- 관리자 사이트 이동
- 관리자 비활성화 또는 삭제

사이트 관리자 생성 시:

```php
User::create([
    'site_id' => $request->site_id,
    'role_id' => 2,
    'role' => 'site_admin',
    'reg_id' => $request->reg_id,
    'password' => bcrypt($request->password),
    'name' => $request->name,
    'tel' => $request->tel,
    'email' => $request->email,
]);
```

---

## 16. 기존 URL 처리 정책

기존 URL:

| 기존 URL | 권장 처리 |
| --- | --- |
| `/login` | 제거 또는 `/admin`으로 redirect |
| `/dashboard` | 제거 또는 로그인 사용자 role에 따라 redirect |
| `/customers` | 제거 또는 `/admin/customers`로 redirect |
| `/videos` | 제거 또는 `/admin/videos`로 redirect |
| `/` | 랜딩/안내 페이지 또는 404 |
| `/pc` | 제거 또는 단말기 URL 사용 안내 |

예시:

```php
Route::redirect('/login', '/admin');
Route::redirect('/dashboard', '/admin/dashboard');
```

단, 사이트관리자는 `/admin/dashboard`가 아니라 `/{siteCode}/admin/dashboard`로 가야 하므로 로그인 후 role과 site에 따른 redirect 로직이 필요합니다.

---

## 17. 실행 순서 제안

1. DB migration 추가
   - `mvs_sites`
   - `mvs_terminals`
   - `users.site_id`, `users.role`
   - `customers.site_id`
   - `videos.site_id`

2. 모델 추가/수정
   - `MvsSite`
   - `MvsTerminal`
   - `User`, `Customer`, `Video` 관계 추가

3. Middleware 추가
   - `bind.site`
   - `super.admin`
   - `site.admin`

4. 라우트 재구성
   - `/admin`
   - `/{siteCode}/admin`
   - `/{siteCode}-{terminalNo}`

5. 인증 컨트롤러 분리
   - 슈퍼관리자 로그인
   - 사이트관리자 로그인
   - role/site 검증

6. 관리자 컨트롤러 사이트 필터 적용
   - 고객
   - 영상
   - 사이트 관리자

7. 재생 화면 UI 제거
   - 우측 상단 아이콘 제거
   - 수동 전화번호 입력 제거
   - 관리자 페이지 링크 제거

8. Asterisk API 호출 수정
   - 사이트 코드 전달
   - 단말기 번호 전달

9. Redis/Socket payload 수정
   - `siteCode`
   - `terminalNo`
   - `terminalKey`

10. 테스트 추가
   - 사이트 관리자 로그인 권한
   - 슈퍼관리자 전체 조회
   - 사이트관리자 타 사이트 접근 차단
   - 단말기 URL 이벤트 필터링
   - `/api/video` site/terminal 검증

---

## 18. 테스트 시나리오

### 18.1 재생 단말기

1. `https://mvs.baro.me/A01-1` 접속
2. 우측 상단 관리자/전화번호 입력 UI가 보이지 않아야 함
3. Asterisk가 `/api/video?tel=01012345678&site_code=A01&terminal_no=1` 호출
4. `A01-1` 화면에서만 영상 재생
5. `A01-2`, `B01-1` 화면에서는 재생되지 않아야 함

### 18.2 사이트관리자

1. `https://mvs.baro.me/A01/admin` 접속
2. A01 사이트관리자로 로그인
3. A01 고객/영상만 조회
4. B01 고객/영상 직접 URL 접근 시 403
5. 사이트 관리자 관리 메뉴는 보이지 않아야 함

### 18.3 슈퍼관리자

1. `https://mvs.baro.me/admin` 접속
2. 슈퍼관리자로 로그인
3. 전체 사이트 목록 조회 가능
4. 사이트 추가/수정/삭제 가능
5. 사이트 관리자 추가/수정/삭제 가능
6. 모든 사이트의 고객/영상 조회 및 수정 가능

---

## 19. 결론

이번 요구사항은 다음 세 가지 축으로 구현해야 합니다.

1. **재생 화면은 완전한 단말기 전용 화면으로 정리**
   - 우측 상단 관리자/수동 입력 UI 제거
   - URL `XXX-N` 기준으로 단말기 식별
   - Socket 이벤트도 `terminalKey` 기준으로 필터링

2. **관리자 URL과 권한을 사이트 단위로 분리**
   - 슈퍼관리자: `/admin`
   - 사이트관리자: `/{siteCode}/admin`
   - 사이트관리자는 자기 사이트 데이터만 관리

3. **DB에 사이트/단말기/권한 소유권 추가**
   - `mvs_sites`
   - `mvs_terminals`
   - `users.site_id`
   - `customers.site_id`
   - `videos.site_id`

단순히 라우트만 바꾸면 사이트별 권한 분리가 불완전합니다. 반드시 DB 소유권 컬럼과 Middleware 기반 권한 검증을 함께 적용해야 합니다.
