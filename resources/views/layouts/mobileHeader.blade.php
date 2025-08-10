<div class="flex flex-col">
    <!-- Search header -->
    <div class="top-0 flex h-16 flex-shrink-0 border-b border-gray-200 bg-white lg:hidden">
        <!-- Sidebar toggle, controls the 'sidebarOpen' sidebar state. -->
        <button type="button" onclick="menuClick()"
                class=" z-40 border-gray-200 px-4 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-purple-500 lg:hidden">
            <span class="sr-only">Open sidebar</span>
            <!-- Heroicon name: outline/bars-3-center-left -->
            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M3.75 6.75h16.5M3.75 12H12m-8.25 5.25h16.5"/>
            </svg>
        </button>
        <div class="absolute w-full justify-between px-4 sm:px-6 lg:mx-auto lg:max-w-6xl lg:px-8 mt-4">
            <div class="flex">
                <a href="{{route('dashboard')}}" class="flex w-full" >
                    <div class="flex w-full h-full items-center justify-center">
                        <img class="h-8 w-auto" src="/images/logo/simple_logo.svg" alt="Your Company">
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="w-full mobile-header">

    <!-- Off-canvas menu for mobile, show/hide based on off-canvas menu state. -->
    <div id="mobile-wrap" class="relative lg:hidden" role="dialog" aria-modal="true">
        <!--
          Off-canvas menu backdrop, show/hide based on off-canvas menu state.

          Entering: "transition-opacity ease-linear duration-300"
            From: "opacity-0"
            To: "opacity-100"
          Leaving: "transition-opacity ease-linear duration-300"
            From: "opacity-100"
            To: "opacity-0"
        -->
        <div>

        </div>
        <div id="mobile-one" onclick="menuClick()"
             class="opacity-0 transition-opacity ease-linear duration-300 fixed inset-0 bg-gray-600 bg-opacity-75">
        </div>
        <div class="fixed inset-0 flex">
            <div onclick="" id="mobile-two"
                 class="-translate-x-full transition ease-in-out duration-300 transform relative flex w-full max-w-xs flex-1 flex-col bg-gray-800 pt-5 pb-4">
                <!--
                  Close button, show/hide based on off-canvas menu state.

                  Entering: "ease-in-out duration-300"
                    From: "opacity-0"
                    To: "opacity-100"
                  Leaving: "ease-in-out duration-300"
                    From: "opacity-100"
                    To: "opacity-0"
                -->
                <div onclick="menuClick()" id="mobile-three" class="opacity-0 ease-in-out duration-300 absolute top-0 right-0 -mr-12 pt-2">
                    <button type="button"
                            class="ml-1 flex h-10 w-10 items-center justify-center rounded-full outline-none ring-2 ring-inset ring-white">
                        <span class="sr-only">Close sidebar</span>
                        <!-- Heroicon name: outline/x-mark -->
                        <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="flex flex-shrink-0 items-center px-4">
                    <img class="h-8 w-auto" src="/images/logo/simple_logo.svg" alt="Your Company">
                </div>
                <nav class="mt-5 flex-1 bg-gray-800" aria-label="Sidebar">
                    <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
                    <a href="/dashboard"
                       class="@php echo $route == 'dashboard'? 'border-indigo-600  border-l-8 bg-gray-900 text-white' : 'text-gray-300 pl-4' @endphp
                           group flex items-center px-2 py-2 text-sm font-medium">
                        <!--
                          Heroicon name: outline/home

                          Current: "text-gray-300", Default: "text-gray-400 group-hover:text-gray-300"
                        -->
                        <svg class="
                             @php echo $route == 'dashboard'? 'text-white' : 'text-gray-400' @endphp
                            mr-3 flex-shrink-0 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>
                        <span class="flex-1">대시보드</span>
                    </a>
                    <a href="{{route('customers.index')}}" class="
                            @php echo $route == 'customers'? 'border-indigo-600  border-l-8 bg-gray-900 text-white' : 'text-gray-300 pl-4' @endphp
                        hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium">
                        <!-- Heroicon name: outline/users -->
                        <svg class="
                            @php echo $route == 'customers'? 'text-white' : 'text-gray-400' @endphp
                            group-hover:text-gray-300 mr-3 flex-shrink-0 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                        <span class="flex-1">고객</span>
                        @isset($nav['customerCount'])
                            <span class="bg-gray-600 group-hover:bg-gray-800 ml-3 inline-block py-0.5 px-3 text-xs font-medium rounded-full text-white">
                                        {{$nav['customerCount']}}
                                </span>
                        @endisset
                    </a>

                    <a href="{{route('videos.index')}}" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium
                            @php echo $route == 'videos'? 'border-indigo-600  border-l-8 bg-gray-900 text-white' : 'text-gray-300 pl-4' @endphp
                        ">
                        <svg class="
                            @php echo $route == 'videos'? 'text-white' : 'text-gray-400' @endphp
                            text-gray-400 group-hover:text-gray-300 mr-3 flex-shrink-0 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z" />
                        </svg>
                        <span class="flex-1">영상</span>
                        @isset($nav['videoCount'])
                            <span class="bg-gray-600 group-hover:bg-gray-800 ml-3 inline-block py-0.5 px-3 text-xs font-medium rounded-full text-white">
                                        {{$nav['videoCount']}}
                                </span>
                        @endisset

                    </a>

{{--                    <a href="#" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">--}}
{{--                        <!-- Heroicon name: outline/chart-bar -->--}}
{{--                        <svg class="text-gray-400 group-hover:text-gray-300 mr-3 flex-shrink-0 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">--}}
{{--                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />--}}
{{--                        </svg>--}}
{{--                        <span class="flex-1">통계</span>--}}

{{--                        <span class="bg-gray-900 group-hover:bg-gray-800 ml-3 inline-block py-0.5 px-3 text-xs font-medium rounded-full">12</span>--}}
{{--                    </a>--}}
                </nav>
            </div>
            <div onclick="menuClick()" class="w-32 flex-shrink-0" aria-hidden="true">
            </div>
        </div>
    </div>

    <!-- Main column -->
</div>


<div class="breadcrumbs flex flex-1 flex-col hidden lg:block mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">

@isset($nav['breadcrumbs'])
    <!-- Breadcrumbs START -->
        <nav class="flex border border-gray-200 bg-white" aria-label="Breadcrumb">
            <ol role="list" class="mx-auto flex w-full max-w-screen-xl space-x-4 px-4 sm:px-6 lg:px-8">
                <li class="flex">
                    <div class="flex items-center">
                        <a href="{{route('dashboard')}}" class="text-gray-400 hover:text-gray-500">
                            <!-- Heroicon name: mini/home -->
                            <svg class="h-5 w-5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                 fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                      d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0117 11h-1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3a1 1 0 00-1-1H9a1 1 0 00-1 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-6H3a1 1 0 01-.707-1.707l7-7z"
                                      clip-rule="evenodd"/>
                            </svg>
                            <span class="sr-only">홈</span>
                        </a>
                    </div>
                </li>
                @foreach ($nav['breadcrumbs'] as $breadcrumbs)
                    <li class="flex">
                        <div class="flex items-center">
                            <svg class="h-full w-6 flex-shrink-0 text-gray-200" viewBox="0 0 24 44"
                                 preserveAspectRatio="none" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                                 aria-hidden="true">
                                <path d="M.293 0l22 22-22 22h1.414l22-22-22-22H.293z"/>
                            </svg>
                            <a href="{{$breadcrumbs['path']}}"
                               class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">{{$breadcrumbs['title']}}</a>
                        </div>
                    </li>
                @endforeach
            </ol>
        </nav>
        <!-- Breadcrumbs END -->
    @endisset
</div>


<script>
    let isOpen = false;

    function menuClick() {
        isOpen = !isOpen;
        menuHandle();
    }

    function menuHandle() {
        if (!isOpen) {
            document.querySelector('#mobile-wrap').style.zIndex = '0';

            document.querySelector('#mobile-wrap').classList.add('hidden');
            document.querySelector('#mobile-wrap').classList.remove('block');

            document.querySelector('#mobile-one').classList.add('opacity-0');
            document.querySelector('#mobile-one').classList.remove('opacity-100');

            document.querySelector('#mobile-two').classList.add('-translate-x-full');
            document.querySelector('#mobile-two').classList.remove('translate-x-0');

            document.querySelector('#mobile-three').classList.add('opacity-0');
            document.querySelector('#mobile-three').classList.remove('opacity-100');
        } else {
            document.querySelector('#mobile-wrap').style.zIndex = '40';

            document.querySelector('#mobile-wrap').classList.add('block');
            document.querySelector('#mobile-wrap').classList.remove('hidden');

            document.querySelector('#mobile-one').classList.remove('opacity-0');
            document.querySelector('#mobile-one').classList.add('opacity-100');

            document.querySelector('#mobile-two').classList.remove('-translate-x-full');
            document.querySelector('#mobile-two').classList.add('translate-x-0');

            document.querySelector('#mobile-three').classList.remove('opacity-0');
            document.querySelector('#mobile-three').classList.add('opacity-100');
        }
    }
    menuHandle();
</script>
