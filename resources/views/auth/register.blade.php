@extends ('auth.index')

@section('content')
    <div class="max-w-xl h-full px-4 sm:px-6 md:px-24 lg:px-24 xl:px-24 pt-16 pb-10 mx-auto max-w-xl sub-bg h-screen">
        <div class="mx-auto w-20">
            <a href="/">


                <img class=""
                     src="/images/logo/main_logo.svg"
                     alt=""/>
            </a>

        </div>

        <div class="mx-auto max-w-xl pb-10 sub-bg">
            <form method="POST" name="form" id="frm" action="{{ route('users.store') }}">
                @csrf
                <div class="">
                    <div>
                        <div v-else id="id-loading-height" class="pt-6">
                            <label for="tel" class="align-middle block text-sm font-medium text-gray-700">아이디<span
                                    class="ml-1 text-lg align-middle required-color">*</span></label>
                            <div class="mt-1 flex flex-row">

                                <input
                                    class="border-gray-300 basis-8/12 block shadow w-full appearance-none rounded-md px-3 py-2 border placeholder-gray-400 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 text-sm sm:text-sm mr-4"
                                    id="regId" type="text" name="reg_id" value="{{ old('reg_id') }}" required
                                    autocomplete="아이디" autofocus
                                    maxlength="12" placeholder="(영문 숫자, 2~20 자리)"
                                >
                                <div
                                    onclick="idValidator()"
                                    class="basis-4/12 h-full black-border black-color-bg black-color-bg-hover cursor-pointer pt-2 pb-2 w-100 w-100 text-center text-white text-sm">
                                    중복확인
                                </div>
                            </div>
                            <div
                                id="regIdMessage" class="mt-2 text-xs italic">
                            </div>
                            @error('reg_id')
                            <span class="alert alert-danger text-red-400 mt-2 text-xs italic" role="alert">
                                       {{ $message }}
                                    </span>
                            @enderror
                        </div>
                        <div class="pt-5">
                            <label for="password" class="block text-sm font-medium text-gray-700 align-middle">비밀번호<span
                                    class="ml-1 text-lg align-middle required-color">*</span></label>
                            <div class="mt-1">
                                <input
                                    value="{{ old('password') }}"
                                    id="password" name="password" type="password" autocomplete="password"
                                    placeholder="(4~20 자리)" required minlength="4" maxlength="20" @keydown.space.prevent
                                    class="border-gray-300 block shadow w-full appearance-none rounded-md px-3 py-2 border placeholder-gray-400 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 text-sm sm:text-sm"/>

                            </div>
                            @error('password')
                            <span class="alert alert-danger text-red-400 mt-2 text-xs italic" role="alert">
                                       {{ $message }}
                                    </span>
                            @enderror
                        </div>
                        <div class="pt-5">
                            <label for="passwordConfirm" class="block text-sm font-medium text-gray-700 align-middle">비밀번호
                                확인<span class="ml-1 text-lg align-middle required-color">*</span></label>
                            <div class="mt-1">
                                <input
                                    value="{{ old('password_confirm') }}" required
                                    id="password_confirm" name="password_confirm" type="password"
                                    autocomplete="passwordConfirm"
                                    placeholder="(4~20자리)" minlength="4" maxlength="30"
                                    class="border-gray-300 block shadow w-full appearance-none rounded-md px-3 py-2 border placeholder-gray-400 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 text-sm sm:text-sm"/>
                            </div>
                            @error('password_confirm')
                            <span class="alert alert-danger text-red-400 mt-2 text-xs italic" role="alert">
                                       {{ $message }}
                                    </span>
                            @enderror
                        </div>
                        <div id="" class="mt-2 text-xs italic text-red-400"></div>
                        <label for="name" class="block text-sm font-medium text-gray-700 align-middle">관리자명<span
                                class="ml-1 text-lg align-middle required-color">*</span></label>

                        <div class="mt-1 flex flex-row">
                            <input
                                value="{{ old('name') }}" required
                                class="border-gray-300 block shadow w-full appearance-none rounded-md px-3 py-2 border placeholder-gray-400 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 text-sm sm:text-sm"
                                id="name" name="name" type="text" placeholder="(2 ~ 10 자리, 공백없음)" autocomplete="name"
                                minlength="2" maxlength="10"
                            />
                        </div>
                        @error('name')
                        <span class="alert alert-danger text-red-400 mt-2 text-xs italic" role="alert">
                                   {{ $message }}
                                </span>
                        @enderror
                    </div>
                    <div class="pt-5">
                        <label for="name" class="block text-sm font-medium text-gray-700 align-middle">
                            관리자 전화번호<span
                                class="ml-1 text-lg align-middle required-color">*</span></label>
                        <div class="mt-1">
                            <input
                                minlength="9" maxlength="12"
                                id="tel" name="tel" type="text"
                                placeholder="전화번호를 입력하세요"
                                autocomplete="tel"
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                class="border-gray-300 block shadow w-full appearance-none rounded-md px-3 py-2 border placeholder-gray-400 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 text-sm sm:text-sm"/>
                        </div>
                        @error('tel')
                        <span class="alert alert-danger text-red-400 mt-2 text-xs italic" role="alert">
                                   {{ $message }}
                                </span>
                        @enderror
                    </div>
                    <div class="pt-5">
                        <label for="name" class="block text-sm font-medium text-gray-700 align-middle">이메일</label>
                        <div class="mt-1">
                            <input
                                id="email" name="email" type="email"
                                placeholder="이메일을 입력하세요" required
                                autocomplete="email"
                                class="border-gray-300 block shadow w-full appearance-none rounded-md px-3 py-2 border placeholder-gray-400 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 text-sm sm:text-sm"/>
                        </div>
                        @error('email')
                        <span class="alert alert-danger text-red-400 mt-2 text-xs italic" role="alert">
                                   {{ $message }}
                                </span>
                        @enderror
                    </div>

                    <div class="mt-10 w-full text-center">
                        <div class="mt-2 text-xs text-red-400 italic">
                        </div>
                        <button
                            type="button" onclick="handleSubmit()"
                            class="w-full main-bg main-bg-hover cursor-pointer mt-2 pt-3 pb-3 w-100 text-base w-100 text-center main-bg rounded-md text-white">
                            회원가입
                        </button>
                        <div class="mt-3">
                            <a href="{{route('auth.login')}}" class=" cursor-pointer text-center link-color">
                                뒤로
                            </a>
                        </div>

                    </div>
                    <div class="lg:mt-16 xl:mt-16 2xl:mt-16 mt-10 flex gap-2 justify-center">
                        <div v-for="index in 3">
                            <div :class="index === step ? 'bg-blue-400' :'bg-gray-400'"
                                 class="rounded-full w-2 h-2">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>

        let idCheck = false;
        // import axios from 'axios';
        // window.axios = axios;
        // const csrfToken = document.head.querySelector("[name~=csrf-token][content]").content;
        const csrfToken = '{{ csrf_token() }}';

        function idValidator() {

            console.log(regId.value);
            const table = 'users';
            const select = 'reg_id';
            const value = regId.value;

            if (!value) {
                document.querySelector('#regIdMessage').textContent = '';
                document.querySelector('#regIdMessage').style.color = '';
                alert('아이디를 입력바랍니다.');
                return;
            }

            fetch('/users/auth/find', {
                method: 'POST',
                body: JSON.stringify(
                    {
                        table: table,
                        select: select,
                        value: {"reg_id": value}
                    }
                ),
                headers: {
                    'Content-Type': 'application/json',
                    "X-CSRF-Token": csrfToken
                }
            })
                .then(response => {
                    return response.json();
                })
                .then(data => {
                    if (data.statusCode === 401) {
                        idCheck = true;
                        document.querySelector('#regIdMessage').textContent = '사용가능한 아이디입니다.';
                        document.querySelector('#regIdMessage').style.color = 'green';
                    } else {
                        idCheck = false;
                        document.querySelector('#regIdMessage').textContent = '중복된 아이디 입니다.';
                        document.querySelector('#regIdMessage').style.color = 'red';
                    }
                })
        }

        function handleSubmit() {
            if (!idCheck) {
                alert('아이디 중복확인을 해주시기 바랍니다');
                return;
            }

            if (!password.value) {
                alert('비밀번호를 입력바랍니다');
                return;
            }

            if (password.value !== password_confirm.value) {
                alert('비밀번호가 일치하지 않습니다');
                return;
            }

            if (!document.querySelector('#name').value) {
                alert('관리자명을 입력바랍니다');
                return;
            }

            if (!tel.value) {
                alert('관리자 전화번호를 입력바랍니다');
                return;
            }

            form.submit();
        }
    </script>
@endsection
