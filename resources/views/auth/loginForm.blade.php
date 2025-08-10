{{--@section('content')--}}
로그인 폼
<div class="">
    <Form @submit="handleLogin" class="space-y-6">
        <div id="loading-height" ref="height" v-else>
            <div class="">
                <label for="id" class="block text-sm font-medium text-gray-700">아이디</label>
                <div class="mt-1">
                    <input id="id" name="id" type="text" autocomplete="아이디"
                           minlength="2" maxlength="12" :rules="validateId" placeholder="ID"
                           class="block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"/>
                </div>
            </div>
            <div class="space-y-1 pt-2">
                <label for="password"
                       class="block text-sm font-medium text-gray-700">비밀번호</label>
                <div class="mt-1">
                    <input id="password" name="password" type="password"
                           placeholder="******"
                           minlength="2" maxlength="24" :rules="validatePassword" autocomplete="current-password"
                           required=""
                           class="block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"/>
                </div>
            </div>

            <div v-if="data.errorMsg" class="mt-2 text-xs text-red-400 italic"></div>
            <p class="mt-2 mb-2 text-sm text-gray-600">
                <a @click="demo" class="cursor-pointer font-medium text-indigo-600 hover:text-indigo-500">데모계정 사용하기</a>
            </p>
            <div class="flex items-center justify-between pt-3">
                <div class="flex items-center cursor-pointer">
                    <input v-model="data.remember" id="remember-me" name="remember-me" type="checkbox"
                           class="cursor-pointer h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"/>
                    <label for="remember-me" class="cursor-pointer ml-2 block text-sm text-gray-900">로그인 상태유지</label>
                </div>
{{--                <div class="text-sm">--}}
{{--                    <NuxtLink to="/auth/find" class="font-medium text-indigo-600 hover:text-indigo-500">아이디 / 비밀번호 찾기</NuxtLink>--}}
{{--                </div>--}}
            </div>
        </div>
        <div class="pt-3">
            <button
                class="form-btn flex w-full justify-center rounded-md border border-transparent py-3 px-4 text-sm font-medium shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                로그인
            </button>
        </div>
    </Form>
    <div class="mt-5">
        <div @click="handleRegister"
             class="cursor-pointer form-btn flex w-full justify-center rounded-md border border-transparent py-3 px-4 text-sm font-medium shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            회원가입
        </div>
    </div>
</div>
{{--@endsection--}}
