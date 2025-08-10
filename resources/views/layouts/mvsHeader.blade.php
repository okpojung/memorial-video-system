<div id="header" class="relative bg-gray-900 border-b border-gray-500" style="background: #121929;">
    <div class="relative mx-auto px-4 lg:px-10">
        <div class="header-mvs-wrap flex items-center justify-between border-gray-400md:justify-start md:space-x-10" >
            <img class="absolute left-0 bottom-0 w-auto h-16" src="/images/landing/flower1.png" alt="">
{{--            <img class="absolute right-1/4 top-0 w-auto h-16" src="/images/landing/flower2.png" alt="">--}}
            <img class="absolute right-0 top-0 w-auto h-16" src="/images/landing/flower2.png" alt="">
            <div class="flex-1">
                <div class="text-left md:flex hidden items-center">
                    @if($socket)
{{--                        <button class="header-070 main-color text-left rounded-md w-full leading-7 text-white shadow-sm  focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-400">{{env('APP_070')}}</button>--}}
                    @else
                        <span class="inline-block flex items-center justify-center">
                            <span class="h-2 w-2 mr-3 rounded-full bg-red-600" aria-hidden="true"></span>
                        </span>
                        <button class="header-ment main-color text-left rounded-md w-full leading-7 text-white shadow-sm  focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-400">
                        키패드로 영상을 재생하세요
                        </button>
                    @endif

                </div>
                @if($socket)
{{--                    <div class="text-left md:flex hidden items-center mvs-header-minus">--}}
{{--                         <span class="inline-block flex flex-shrink-0">--}}
{{--                            <span class="mvs-h-2 mvs-w-2 mr-3 rounded-full bg-green-500" aria-hidden="true"></span>--}}
{{--                        </span>--}}
{{--                        <button class="header-ment text-gray-200 text-center leading-7">전화를 걸면 영상이 재생됩니다</button>--}}
{{--                    </div>--}}
                @endif
            </div>

            <div class="md:flex items-center justify-center flex-1 hidden">
{{--                <img class="simple-logo inline-block w-auto mr-3" src="/images/logo/simple_logo.svg" alt="">--}}
{{--                <img class="simple-text inline-block w-auto " src="/images/logo/simple_text.svg" alt="">--}}
                <img class="inline-block w-auto h-16" src="/images/landing/title.png" alt="">
            </div>
            <div class="flex-1 cursor-pointer text-right flex justify-end items-center z-30">
                <svg onclick="customerListClick()" class="mvs-icon mr-3 text-gray-400 text-right" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 20.25h12m-7.5-3v3m3-3v3m-10.125-3h17.25c.621 0 1.125-.504 1.125-1.125V4.875c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125z" />
                </svg>
                <svg onclick="videoListClick()" class="mvs-icon ml-3 text-gray-400 text-right" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                </svg>
            </div>
        </div>
    </div>
</div>

<style>
    .header-070{
        font-size:2vw;
    }
    .header-ment{
        font-size:0.7vw;
    }
    .simple-logo {
        font-size:1.5vw;
        height:2.5vw;
    }
    .simple-text {
        font-size:0.7vw;
        height:2.5vw;
    }
    .mvs-icon{
        width: 1.5vw;
        height: 1.5vw;
    }
    .header-mvs-wrap{
        padding: 0.5vw 0;
    }
    .mvs-header-minus{
        margin-top:-0.5vw;
    }
    .mvs-h-2 {
        height:0.4vw;
    }
    .mvs-w-2 {
        width:0.4vw;
    }
</style>
