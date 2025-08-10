<div id="header" class="relative border-b border-gray-500" style="background: #121929;">
    <div class="relative mx-auto px-6 lg:px-10">
        <div class="flex items-center justify-between border-gray-400 py-6 md:py-9 md:space-x-10" >
            <img class="hidden md:inline-block absolute left-0 bottom-0 w-auto h-28" src="/images/landing/flower1.png" alt="">
            <img class="hidden md:inline-block absolute right-1/4 top-0 w-auto h-28" src="/images/landing/flower2.png" alt="">
            <img class="hidden md:inline-block absolute right-0 bottom-0 w-auto h-28" src="/images/landing/flower3.png" alt="">
{{--            <div class="flex-1">--}}
{{--                <div class="text-left md:flex hidden items-center">--}}
{{--                    @if($socket)--}}
{{--                        <button class="main-color text-left rounded-md w-full lg:text-5xl text-xl leading-7 text-white shadow-sm  focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-400">{{env('APP_070')}}</button>--}}
{{--                    @else--}}
{{--                        <span class="inline-block flex items-center justify-center">--}}
{{--                            <span class="h-2 w-2 mr-3 rounded-full bg-red-600" aria-hidden="true"></span>--}}
{{--                        </span>--}}
{{--                        <button class="main-color text-left rounded-md w-full lg:text-3xl text-xl leading-7 text-white shadow-sm  focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-400">--}}
{{--                            키패드로 영상을 재생하세요--}}
{{--                        </button>--}}
{{--                    @endif--}}

{{--                </div>--}}
{{--            </div>--}}

            <div class="md:flex items-center justify-center z-40">
                <img class="inline-block w-auto h-14 md:h-24" src="/images/landing/title.png" alt="">
            </div>
            <div class="z-40">
                <div class="cursor-pointer flex items-center z-30">
                    <svg onclick="customerListClick()" class="mr-5 h-7 w-7 md:h-9 md:w-9 text-gray-400 text-right" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 20.25h12m-7.5-3v3m3-3v3m-10.125-3h17.25c.621 0 1.125-.504 1.125-1.125V4.875c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125z" />
                    </svg>
                    <svg onclick="videoListClick()" class="ml-3 h-7 w-7 md:h-9 md:w-9 text-gray-400 text-right" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>


