@extends ('auth.index')

@section('content')
{{--    <div id="app">--}}

{{--    </div>--}}
    <div class="h-screen 2xl:container mx-auto">
        <div class="flex min-h-full">
            <div class="flex flex-1 flex-col justify-center py-12 px-4 sm:px-6 lg:flex-none lg:px-20 xl:px-24">
                <div class="mx-auto w-full max-w-sm lg:w-96">
                    <div class="mx-auto w-20">
                        <a href="/">
                        <img class=""
                             src="/images/logo/main_logo.svg"
                             alt=""/>
                        </a>
                    </div>
                    <div class="">
                        <form id="form" method="POST" action="{{route('auth.login')}}" class="space-y-6">
                            @csrf
                            <div id="loading-height" ref="height" v-else>
                                <div class="">
                                    <label for="id" class="block text-sm font-medium text-gray-700">아이디</label>
                                    <div class="mt-1">
                                        <input id="id" name="reg_id" type="text" autocomplete="아이디" required
                                               value="{{old('reg_id')}}"
                                               minlength="2" maxlength="12" :rules="validateId" placeholder="아이디를 입력해주세요"
                                               class="block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"/>

                                    </div>
                                </div>
                                <div class="space-y-1 pt-2">
                                    <label for="password"
                                           class="block text-sm font-medium text-gray-700">비밀번호</label>
                                    <div class="mt-1">
                                        <input id="password" name="password" type="password"
                                               placeholder="******" required
                                               value="{{old('password')}}"
                                               minlength="2" maxlength="24" :rules="validatePassword" autocomplete="current-password"
                                               class="block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"/>
                                    </div>
                                </div>
                                <div class="text-red-400 mt-2 text-xs italic">
                                    @error('message')
                                    {{ $message }}
                                    @enderror
                                </div>


                                <p class="mt-2 mb-2 text-sm text-gray-600">
                                    <a onclick="demo()" class="cursor-pointer font-medium text-indigo-600 hover:text-indigo-500">데모계정 사용하기</a>
                                </p>
                                <div class="flex items-center justify-between pt-3">
                                    <div class="flex items-center cursor-pointer">
                                        <input id="remember-me" name="remember-me" type="checkbox"
                                               class="cursor-pointer h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"/>
                                        <label for="remember-me" class="cursor-pointer ml-2 block text-sm text-gray-900">로그인 상태유지</label>
                                    </div>
{{--                                    <div class="text-sm">--}}
{{--                                        <NuxtLink to="/auth/find" class="font-medium text-indigo-600 hover:text-indigo-500">아이디 / 비밀번호 찾기</NuxtLink>--}}
{{--                                    </div>--}}
                                </div>
                            </div>
                            <div class="pt-3">
                                <button
                                    class="form-btn flex w-full justify-center rounded-md border border-transparent py-3 px-4 text-sm font-medium shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    로그인
                                </button>
                            </div>
                        </form>
                        <div class="mt-5">
                            <a href="{{ route('users.create') }}"
                                 class="cursor-pointer form-btn flex w-full justify-center rounded-md border border-transparent py-3 px-4 text-sm font-medium shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                회원가입
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="relative hidden w-0 flex-1 lg:block">
                <img class="absolute inset-0 h-full w-full object-cover"
                     src="/images/main_bg_3.jpg"
                     alt=""/>
            </div>
        </div>
    </div>

    <script>
        function demo() {
            document.querySelector('#id').value = 'admin';
            document.querySelector('#password').value = '000000';
            document.querySelector('#form').submit();
        }

    </script>

@endsection
<style>
    html, body {
        height:100%;
    }

</style>
