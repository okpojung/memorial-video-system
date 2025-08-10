@extends ('layouts.index')

@section('content')

    <div class="mx-auto mt-5 max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="">
            <div class="sm:flex sm:items-center">
                <div class="sm:flex-auto">
                    <h1 class="text-xl font-semibold text-gray-900">등록된 영상</h1>
                </div>
            </div>
            <div class="-mx-4 mt-4 overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:-mx-6 md:mx-0 md:rounded-lg">
                <table class="min-w-full divide-y divide-gray-300 text-center">
                    <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="text-center hidden md:table-cell px-4 py-3.5 text-left text-sm font-semibold text-gray-900">영상</th>
                        <th scope="col" class="text-center md-4 py-3.5 text-left text-sm font-semibold text-gray-900">제목</th>
                        <th scope="col" class="text-center px-4 py-3.5 text-left text-sm font-semibold text-gray-900">영상시간</th>
                        <th scope="col" class="text-center hidden md:table-cell px-4 py-3.5 text-left text-sm font-semibold text-gray-900">고인명</th>
                        <th scope="col" class="text-center px-3 py-3.5 text-left text-sm font-semibold text-gray-900">삭제</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                    @if(count($videos) == 0)
                        <tr>
                            <td colspan="9" class="w-full text-left px-9 py-3.5 text-sm font-medium text-gray-900 sm:w-auto sm:max-w-none">
                                등록된 영상이 없습니다
                            </td>
                        </tr>
                    @endif
                    @foreach($customerVideos as $customerVideo)
                        <tr>
                            <td class="w-40 px-4 py-4 text-sm text-gray-500 relative">
                                <a target="_blank" href="/{{$customerVideo->video_url}}" class="hidden md:table-cell">
                                    <img class="w-full" style="" src="/{{$customerVideo->thumbnail_url}}" alt="">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="opacity-50 w-8 h-8 text-white absolute top-1/2 left-1/2 -ml-4 -mt-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.91 11.672a.375.375 0 010 .656l-5.603 3.113a.375.375 0 01-.557-.328V8.887c0-.286.307-.466.557-.327l5.603 3.112z" />
                                    </svg>
                                </a>
                                <dl class="font-normal md:hidden">
                                    <dd class="mt-1 truncate text-gray-700">{{$customerVideo->title}}</dd>
                                </dl>
                            </td>
                            <td class="hidden md:table-cell px-4 py-4 text-sm text-gray-500">{{$customerVideo->title}}</td>
                            <td class="px-4 py-4 text-sm text-gray-500">{{$customerVideo->playtime_string}}</td>
                            <td class="hidden md:table-cell px-4 py-4 text-sm text-gray-500">{{$customerVideo->deceased}}</td>
                            <td class="text-center py-4 pl-3 text-sm font-medium">
                                <form id="delete-{{$customerVideo->id}}" class="inline-block" method="POST" action="/customers/video/{{$customerVideo->id}}">
                                    @method('delete')
                                    @csrf
                                    <button onclick="deleteHandle({{$customerVideo->id}})" type="button" class="bg-red-600 py-2 px-3 rounded-md text-white hover:text-white">해제</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    <!-- More people... -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{--    {{dump($paginator)}}--}}
    <div class="">
        <div class="mt-8">
            <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
                <div class="">
                    <div class="sm:flex sm:items-center">
                        <div class="sm:flex-auto">
                            <h1 class="text-xl font-semibold text-gray-900">영상 목록</h1>
                        </div>
                    </div>
                    <div class="justify-between flex">
                        <form method="GET" action="/customers/{{$id}}/video" class="mt-3 sm:flex sm:max-w-lg lg:mt-0 ">
                            <select name="sfl" id="sfl" class="mr-3 w-full min-w-0 appearance-none rounded-md border-gray-300 bg-white px-[calc(theme(spacing.3)-1px)] py-[calc(theme(spacing[1.5])-1px)] text-base leading-7 text-gray-900 placeholder-gray-400 shadow-sm focus:border-indigo-600 focus:ring-indigo-600 sm:w-56 sm:text-sm sm:leading-6" >
                                <option value="title" @if($sfl == 'title') selected  @endif>제목</option>
                                <option value="deceased" @if($sfl == 'deceased') selected  @endif>고인명</option>
                                <option value="video_tel" @if($sfl == 'video_tel') selected  @endif>등록된 전화번호</option>
                            </select>
                            <input type="text"
                                   value="@if($stx){{$stx}}@endif"
                                   name="stx" id="stx" class="mt-2 lg:mt-0 w-full min-w-0 appearance-none rounded-md border-gray-300 bg-white px-[calc(theme(spacing.3)-1px)] py-[calc(theme(spacing[1.5])-1px)] text-base leading-7 text-gray-900 placeholder-gray-400 shadow-sm focus:border-indigo-600 focus:ring-indigo-600 sm:w-100 sm:text-sm sm:leading-6" placeholder="검색어를 입력하세요">
                            <div class="mt-4 rounded-md sm:mt-0 sm:ml-4 sm:flex-shrink-0">
                                <button type="submit" class="flex w-full items-center justify-center rounded-md bg-gray-600 py-1.5 px-6 text-base  leading-7 text-white hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 sm:text-sm sm:leading-6">검색</button>
                            </div>
                        </form>
                    </div>

                    <div class="-mx-4 mt-8 overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:-mx-6 md:mx-0 md:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-300 text-center">
                            <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="text-center px-4 py-3.5 text-left text-sm font-semibold text-gray-900 md:table-cell">영상</th>
                                <th scope="col" class="text-center hidden md:table-cell px-4 py-3.5 text-left text-sm font-semibold text-gray-900 md:table-cell">제목</th>
                                <th scope="col" class="text-center hidden md:table-cell px-4 py-3.5 text-left text-sm font-semibold text-gray-900 md:table-cell">영상시간</th>
                                <th scope="col" class="text-center hidden md:table-cell px-4 py-3.5 text-left text-sm font-semibold text-gray-900 md:table-cell">고인명</th>
                                <th scope="col" class="text-center hidden md:table-cell px-4 py-3.5 text-left text-sm font-semibold text-gray-900 md:table-cell">전화번호</th>
                                <th scope="col" class="text-center px-4 py-3.5 text-left text-sm font-semibold text-gray-900">등록일</th>
                                <th scope="col" class="text-center px-3 py-3.5 text-left text-sm font-semibold text-gray-900">등록</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                            @if(count($videos) == 0)
                                <tr>
                                    <td colspan="9" class="w-full text-left px-9 py-3.5 text-sm font-medium text-gray-900 sm:w-auto sm:max-w-none">
                                        등록된 영상이 없습니다
                                    </td>
                                </tr>
                            @endif
                            @foreach($videos as $video)
                                <tr>
                                    <td class="w-40 px-4 py-4 text-sm text-gray-500 relative">
                                        <a target="_blank" href="/{{$video->video_url}}" class="hidden md:table-cell">
                                            <img class="w-full" style="" src="/{{$video->thumbnail_url}}" alt="">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="opacity-50 w-8 h-8 text-white absolute top-1/2 left-1/2 -ml-4 -mt-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.91 11.672a.375.375 0 010 .656l-5.603 3.113a.375.375 0 01-.557-.328V8.887c0-.286.307-.466.557-.327l5.603 3.112z" />
                                            </svg>
                                        </a>
                                        <dl class="font-normal md:hidden">
                                            <dd class="truncate text-gray-700">{{$video->title}}</dd>
                                            <dd class="mt-1 truncate text-gray-500">{{$video->playtime_string}}</dd>
                                            <dd class="mt-1 truncate text-gray-500">{{substr($video->created_at,0,10)}}</dd>
                                        </dl>
                                    </td>
                                    <td class="px-4 py-4 hidden md:table-cell text-sm text-gray-500 md:table-cell">{{$video->title}}</td>
                                    <td class="px-4 py-4 hidden md:table-cell text-sm text-gray-500 md:table-cell">{{$video->playtime_string}}</td>
                                    <td class="px-4 py-4 hidden md:table-cell text-sm text-gray-500 md:table-cell">{{$video->deceased}}</td>
                                    <td class="px-4 py-4 hidden md:table-cell text-sm text-gray-500 md:table-cell">{{$video->video_tel}}</td>
                                    <td class="px-4 py-4 text-sm text-gray-500">{{substr($video->created_at,0,10)}}</td>
                                    <td class="text-center py-4 pl-3 text-sm font-medium">
                                        <form method="POST" action="{{route('customers.videoStore')}}">
                                            @csrf
                                            <input type="hidden" name="customer_id" value="{{$id}}">
                                            <input type="hidden" name="video_id" value="{{$video->id}}">
                                            <button type="submit" class="bg-blue-600 py-2 px-3 rounded-md mr-3 text-white hover:text-white">선택</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            <!-- More people... -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="pt-10 flex items-center justify-center">
                    {{ $videos->links() }}

                </div>


            </div>
        </div>
    </div>
    <script>
        function deleteHandle(id) {
            if (confirm("해제 하시겠습니까?")) {
                document.querySelector('#delete-'+id).submit();
            }
        }
    </script>
    <style>

    </style>
@endsection
