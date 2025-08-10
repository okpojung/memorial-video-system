@extends ('layouts.index')

@section('content')
    <div class="mx-auto mt-5 max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="mt-8">
            <div class="mx-auto max-w-6xl">
                <div class="sm:flex sm:items-center">
                    <div class="sm:flex-auto">
                        <h1 class="text-xl font-semibold text-gray-900">고객 정보</h1>
                    </div>
                </div>
                <div class="space-y-8 divide-y divide-gray-200 sm:space-y-5">
                    <div class="space-y-6 pt-8 sm:space-y-5 sm:pt-10">
                        <div class="space-y-6 sm:space-y-5">
                            <div
                                class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-gray-200 sm:pt-5">
                                <label for="name" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">고객명
                                </label>
                                <div class="mt-1 sm:col-span-2 sm:mt-0">
                                    <input
                                        value="@isset($customer->name) {{$customer->name}}@endisset"
                                        type="text"
                                        class="readonly placeholder-gray-400 block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:max-w-sm sm:text-sm">
                                </div>
                            </div>
                            <div
                                class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-gray-200 sm:pt-5">
                                <label for="tel"
                                       class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">휴대폰 번호</label>
                                <div class="mt-1 sm:col-span-2 sm:mt-0">
                                    <input
                                        value="@isset($customer->tel) {{$customer->tel}}@endisset"
                                        type="text"
                                        class="readonly placeholder-gray-400 block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:max-w-sm sm:text-sm">
                                </div>
                            </div>
                            <div
                                class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-gray-200 sm:pt-5">
                                <label for="tel"
                                       class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">이메일</label>
                                <div class="mt-1 sm:col-span-2 sm:mt-0">
                                    <input
                                        value="@isset($customer->email) {{$customer->email}}@endisset"
                                        type="text"
                                        class="readonly placeholder-gray-400 block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:max-w-sm sm:text-sm">
                                </div>
                            </div>
                            <div
                                class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-gray-200 sm:pt-5">
                                <label for="email"
                                       class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">주소</label>
                                <div class="sm:col-span-2">
                                    <div class="pt-3 sm:col-span-2 sm:mt-0">
                                        <input
                                            value="@isset($customer->zone_code) {{$customer->zone_code}}@endisset @isset($customer->road_address) {{$customer->road_address}} @endisset @isset($customer->detail_address) {{$customer->detail_address}} @endisset"
                                            type="text"
                                            class="readonly placeholder-gray-400 block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:max-w-sm sm:text-sm">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-20">
            <div class="sm:flex sm:items-center">
                <div class="sm:flex-auto">
                    <h1 class="text-xl font-semibold text-gray-900">등록된 영상</h1>
                </div>
            </div>
            <div
                class="-mx-4 mt-4 overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:-mx-6 md:mx-0 md:rounded-lg">
                <table class="min-w-full divide-y divide-gray-300 text-center">
                    <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="text-center hidden px-4 py-3.5 text-left text-sm font-semibold text-gray-900 sm:table-cell">
                            영상
                        </th>
                        <th scope="col"
                            class="text-center hidden px-4 py-3.5 text-left text-sm font-semibold text-gray-900 sm:table-cell">
                            제목
                        </th>
                        <th scope="col"
                            class="text-center hidden px-4 py-3.5 text-left text-sm font-semibold text-gray-900 sm:table-cell">
                            영상시간
                        </th>
                        <th scope="col"
                            class="text-center hidden px-4 py-3.5 text-left text-sm font-semibold text-gray-900 sm:table-cell">
                            고인명
                        </th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                    @if(count($customerVideos) == 0)
                        <tr>
                            <td colspan="9"
                                class="w-full text-left px-9 py-3.5 text-sm font-medium text-gray-900 sm:w-auto sm:max-w-none">
                                등록된 영상이 없습니다
                            </td>
                        </tr>
                    @endif
                    @foreach($customerVideos as $customerVideo)
                        <tr>
                            <td class="w-32 px-4 py-4 text-sm text-gray-500 relative">
                                <a target="_blank" href="/{{$customerVideo->video_url}}" class="">
                                    <img class="w-full" style="" src="/{{$customerVideo->thumbnail_url}}" alt="">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor"
                                         class="opacity-50 w-8 h-8 text-white absolute top-1/2 left-1/2 -ml-4 -mt-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M15.91 11.672a.375.375 0 010 .656l-5.603 3.113a.375.375 0 01-.557-.328V8.887c0-.286.307-.466.557-.327l5.603 3.112z"/>
                                    </svg>
                                </a>
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-500 lg:table-cell">{{$customerVideo->title}}</td>
                            <td class="px-4 py-4 text-sm text-gray-500 sm:table-cell">{{$customerVideo->playtime_string}}</td>
                            <td class="px-4 py-4 text-sm text-gray-500 sm:table-cell">{{$customerVideo->deceased}}</td>
                        </tr>
                    @endforeach
                    <!-- More people... -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{--    {{dump($paginator)}}--}}
    <script>
        function deleteHandle(id) {
            if (confirm("해제 하시겠습니까?")) {
                document.querySelector('#delete-' + id).submit();
            }
        }
    </script>
@endsection
