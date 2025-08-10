@extends ('layouts.index')

@section('content')
    {{--    @isset($nav['profile'])--}}

{{--    <div id="app">--}}

{{--    </div>--}}

    <div class="flex flex-1 flex-col mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="flex flex-shrink-0 border-gray-200 bg-white shadow px-3 mt-3">
            <div class="text-2xl text-left font-bold leading-7 text-gray-900 pt-6 pb-2 lg:px-8">
                {{env('APP_PLACE')}}
            </div>
        </div>

        <main class="flex-1 pb-2">
            <!-- Page header -->
            <div class="bg-white shadow">
                <div class="px-4 sm:px-6 lg:mx-auto lg:max-w-6xl lg:px-8">
                    <div class="py-6 md:flex md:items-center md:justify-between lg:border-t lg:border-gray-200">
                        <div class="min-w-0 flex-1">
                            <!-- Profile -->
                            <div class="flex items-center">
                                <div class="hidden h-16 w-16 rounded-full sm:block">
                                    <svg
                                        class="rounded-full bg-gray-100 overflow-hidden h-full w-full text-gray-300"
                                        fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                </div>
                                {{--                                    <img class="hidden h-16 w-16 rounded-full sm:block" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2.6&w=256&h=256&q=80" alt="">--}}
                                <div>
                                    <div class="flex items-center">

                                        <div class="h-16 w-16 rounded-full sm:hidden" alt="">
                                            <svg
                                                class="rounded-full bg-gray-100 overflow-hidden h-full w-full text-gray-300"
                                                fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                            </svg>
                                        </div>
                                        <h1 class="ml-3 text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:leading-9">{{$user->name??$user->name}}</h1>
                                    </div>
                                    <dl class="mt-6 flex flex-col sm:ml-3 sm:mt-1 sm:flex-row sm:flex-wrap">
                                        <dt class="sr-only">Company</dt>
                                        {{--                                            <dd class="flex items-center text-sm font-medium capitalize text-gray-500 sm:mr-6">--}}
                                        {{--                                                <!-- Heroicon name: mini/building-office -->--}}
                                        {{--                                                <svg class="mr-1.5 h-5 w-5 flex-shrink-0 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">--}}
                                        {{--                                                    <path fill-rule="evenodd" d="M4 16.5v-13h-.25a.75.75 0 010-1.5h12.5a.75.75 0 010 1.5H16v13h.25a.75.75 0 010 1.5h-3.5a.75.75 0 01-.75-.75v-2.5a.75.75 0 00-.75-.75h-2.5a.75.75 0 00-.75.75v2.5a.75.75 0 01-.75.75h-3.5a.75.75 0 010-1.5H4zm3-11a.5.5 0 01.5-.5h1a.5.5 0 01.5.5v1a.5.5 0 01-.5.5h-1a.5.5 0 01-.5-.5v-1zM7.5 9a.5.5 0 00-.5.5v1a.5.5 0 00.5.5h1a.5.5 0 00.5-.5v-1a.5.5 0 00-.5-.5h-1zM11 5.5a.5.5 0 01.5-.5h1a.5.5 0 01.5.5v1a.5.5 0 01-.5.5h-1a.5.5 0 01-.5-.5v-1zm.5 3.5a.5.5 0 00-.5.5v1a.5.5 0 00.5.5h1a.5.5 0 00.5-.5v-1a.5.5 0 00-.5-.5h-1z" clip-rule="evenodd" />--}}
                                        {{--                                                </svg>--}}
                                        {{--                                                Duke street studio--}}
                                        {{--                                            </dd>--}}
                                        <dt class="sr-only">Account status</dt>
                                        <dd class="mt-3 flex items-center text-sm font-medium capitalize text-gray-500 sm:mr-6 sm:mt-0">
                                            <!-- Heroicon name: mini/check-circle -->
                                            <svg class="mr-1.5 h-5 w-5 flex-shrink-0 text-green-400"
                                                 xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                 fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd"
                                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                                      clip-rule="evenodd"/>
                                            </svg>
                                            {{$user->tel??$user->tel}}
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 flex space-x-3 md:mt-0 md:ml-4">
                            <a href="/"
                               class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2">
                                영상재생 바로가기
                            </a>
                            <form method="POST" action="{{route('auth.logout')}}"
                                  class="inline-flex items-center rounded-md border border-transparent bg-cyan-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2">
                                @csrf
                                <button type="submit">
                                    로그아웃
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <div class="">
        <div class="flex flex-1 flex-col">
            <main class="flex-1 pb-8">
                <!-- Page header -->
                <div class="mt-8">
                    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
                        <h2 class="text-lg font-medium leading-6 text-gray-900">이달의 통계</h2>
                        <div class="mt-2 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                            <!-- Card -->
                            <div class="overflow-hidden rounded-lg bg-white shadow">
                                <div class="p-5">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                 stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-400">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-5 w-0 flex-1">
                                            <dl>
                                                <dt class="truncate text-sm font-medium text-gray-500">이달의 매출
                                                </dt>
                                                <dd>
                                                    <div class="text-lg font-medium text-gray-900">
                                                        $ {{$setAmount}}원
                                                    </div>
                                                </dd>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                                {{--                                <div class="bg-gray-50 px-5 py-3">--}}
                                {{--                                    <div class="text-sm">--}}
                                {{--                                        <a href="#" class="font-medium text-cyan-700 hover:text-cyan-900">View all</a>--}}
                                {{--                                    </div>--}}
                                {{--                                </div>--}}
                            </div>

                            <div class="overflow-hidden rounded-lg bg-white shadow">
                                <div class="p-5">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">

                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                 stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-400">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-5 w-0 flex-1">
                                            <dl>
                                                <dt class="truncate text-sm font-medium text-gray-500">이달의 가입자수
                                                </dt>
                                                <dd>
                                                    <div class="text-lg font-medium text-gray-900">
                                                        {{$customerCnt}}명
                                                    </div>
                                                </dd>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                                {{--                                <div class="bg-gray-50 px-5 py-3">--}}
                                {{--                                    <div class="text-sm">--}}
                                {{--                                        <a href="#" class="font-medium text-cyan-700 hover:text-cyan-900">View all</a>--}}
                                {{--                                    </div>--}}
                                {{--                                </div>--}}
                            </div>

                            <div class="overflow-hidden rounded-lg bg-white shadow">
                                <div class="p-5">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <!-- Heroicon name: outline/scale -->
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                 stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-400">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.348a1.125 1.125 0 010 1.971l-11.54 6.347a1.125 1.125 0 01-1.667-.985V5.653z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-5 w-0 flex-1">
                                            <dl>
                                                <dt class="truncate text-sm font-medium text-gray-500">이달의 영상건수
                                                </dt>
                                                <dd>
                                                    <div class="text-lg font-medium text-gray-900">{{$videoCnt}}건</div>
                                                </dd>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                                {{--                                <div class="bg-gray-50 px-5 py-3">--}}
                                {{--                                    <div class="text-sm">--}}
                                {{--                                        <a href="#" class="font-medium text-cyan-700 hover:text-cyan-900">View all</a>--}}
                                {{--                                    </div>--}}
                                {{--                                </div>--}}
                            </div>

                            <!-- More items... -->
                        </div>

                    </div>



                        <!-- Activity table (small breakpoint and up) -->
                        @if(count($customers) !== 0)
                        <div class="">
                            <h2 class="mx-auto mt-14 max-w-6xl px-4 text-lg font-medium leading-6 text-gray-900 sm:px-6 lg:px-8">
                                이달의 가입자
                            </h2>
                        <div class="">
                            <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
                                <div class="mt-2 flex flex-col">
                                    <div
                                        class="min-w-full overflow-hidden overflow-x-auto align-middle shadow sm:rounded-lg">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead>
                                            <tr>
                                                <th class="bg-gray-50 px-6 py-3 text-left text-sm font-semibold text-gray-900"
                                                    scope="col">고인명
                                                </th>
                                                <th class="bg-gray-50 px-6 py-3 text-center text-sm font-semibold text-gray-900"
                                                    scope="col">휴대폰 번호
                                                </th>
                                                <th class="hidden lg:table-cell bg-gray-50 px-6 py-3 text-center text-sm font-semibold text-gray-900"
                                                    scope="col">등록일
                                                </th>
                                                <th class="hidden lg:table-cell bg-gray-50 px-6 py-3 text-center text-sm font-semibold text-gray-900"
                                                    scope="col">상세보기
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200 bg-white">
                                                @foreach($customers as $customer)
                                                    <tr class="bg-white">
                                                        <td class="max-w-40 whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                                                            <div class="flex">
                                                                <div class="group inline-flex space-x-2 truncate text-sm">
                                                                    <svg
                                                                        class="h-5 w-5 flex-shrink-0 text-gray-400 group-hover:text-gray-500"
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        viewBox="0 0 20 20"
                                                                        fill="currentColor" aria-hidden="true">
                                                                        <path
                                                                            d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                                                    </svg>
                                                                    <p class="truncate text-gray-500 group-hover:text-gray-900">
                                                                        {{$customer->name}}</p>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="whitespace-nowrap px-6 py-4 text-center text-sm text-gray-500">
                                                        <span
                                                            class="text-center font-medium text-gray-900">{{$customer->tel}}</span>
                                                        </td>
                                                        <td class="hidden lg:table-cell whitespace-nowrap px-6 py-4 text-sm text-gray-500 text-center">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 capitalize">{{substr($customer->created_at,0,10)}}</span>
                                                        </td>
                                                        <td class="hidden lg:table-cell whitespace-nowrap px-6 py-4 text-sm text-gray-500 text-center">
                                                            <a href="/customers/{{$customer->id}}/view"
                                                               class="bg-blue-400 py-2 px-3 rounded-md text-white hover:text-gray-300">상세보기</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            <!-- More transactions... -->
                                            </tbody>
                                        </table>
                                        <!-- Pagination -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if(count($videos) !== 0)

                    <div>
                        <h2 class="mx-auto mt-14 max-w-6xl px-4 text-lg font-medium leading-6 text-gray-900 sm:px-6 lg:px-8">
                            이달의 영상</h2>
                        <div class="">
                            <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
                                <div class="mt-2 flex flex-col">
                                    <div
                                        class="min-w-full overflow-hidden overflow-x-auto align-middle shadow sm:rounded-lg">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead>
                                            <tr>
                                                <th class="bg-gray-50 px-6 py-3 text-center text-sm font-semibold text-gray-900"
                                                    scope="col">썸네일
                                                </th>
                                                <th class="bg-gray-50 px-6 py-3 text-center text-sm font-semibold text-gray-900"
                                                    scope="col">제목
                                                </th>
                                                <th class="hidden md:block bg-gray-50 px-6 py-3 text-center text-sm font-semibold text-gray-900"
                                                    scope="col">영상시간
                                                </th>
                                                <th class="bg-gray-50 px-6 py-3 text-center text-sm font-semibold text-gray-900"
                                                    scope="col">등록일
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200 bg-white">
                                                @foreach($videos as $video)
                                                    <tr class="bg-white">
                                                        <td class="w-28 px-4 py-4 text-sm text-gray-500 relative">
                                                            <a target="_blank" href="/{{$video->video_url}}" class="">
                                                                <img class="w-full" style=""
                                                                     src="/{{$video->thumbnail_url}}" alt="">
                                                                <svg xmlns="http://www.w3.org/2000/svg" Ffill="none"
                                                                     viewBox="0 0 24 24"
                                                                     stroke-width="1.5" stroke="currentColor"
                                                                     class="opacity-50 w-8 h-8 text-white absolute top-1/2 left-1/2 -ml-4 -mt-4">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                          d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                          d="M15.91 11.672a.375.375 0 010 .656l-5.603 3.113a.375.375 0 01-.557-.328V8.887c0-.286.307-.466.557-.327l5.603 3.112z"/>
                                                                </svg>
                                                            </a>
                                                        </td>
                                                        <td class="whitespace-nowrap px-6 py-4 text-center text-sm text-gray-500">
                                                            <span class="font-medium text-gray-900">{{$video->title}}</span>
                                                        </td>
                                                        <td class="hidden md:table-cell whitespace-nowrap px-6 py-4 text-center text-sm text-gray-500">
                                                        <span
                                                            class="font-medium text-gray-900">{{$video->playtime_string}}</span>
                                                        </td>

                                                        <td class="whitespace-nowrap px-6 py-4 text-center text-sm text-gray-500">
                                                         <span
                                                             class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 capitalize">{{substr($video->created_at,0,10)}}</span>
                                                        </td>
                                                    </tr>
                                                @endforeach

                                            <!-- More transactions... -->
                                            </tbody>
                                        </table>
                                        <!-- Pagination -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </main>
        </div>
    </div>
@endsection
