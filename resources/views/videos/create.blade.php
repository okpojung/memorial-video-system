@extends ('layouts.index')

@section('content')
    <div class="">
        <div class="mt-8">
            <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
                <div class="sm:flex sm:items-center">
                    <div class="sm:flex-auto">
                        <h1 class="text-xl font-semibold text-gray-900">영상 추가</h1>
                    </div>
                </div>
                <form id="form" method="POST" enctype="multipart/form-data" action="{{route('videos.store')}}"
                      class="">
                    @csrf
                    <div class="space-y-4 lg:space-y-8 divide-y divide-gray-200 sm:space-y-5">
                        <div class="space-y-6 pt-8 sm:space-y-5 sm:pt-10">
                            <div class="space-y-6 sm:space-y-5">
                                <div
                                    class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 pt-2 sm:pt-5">
                                    <label class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">영상
                                        <span
                                            class="ml-1 text-lg align-middle required-color">*</span>
                                    </label>
                                    <div class="mt-1 sm:col-span-2 sm:mt-0">
{{--                                        <label for="video"--}}
{{--                                               class="flex max-w-sm justify-left rounded-md border border-dashed border-gray-300 bg-white px-3 pt-5 pb-6">--}}
{{--                                        </label>--}}
                                        <div class="space-y-1 text-center">

                                            <div class="flex text-sm text-gray-600">
{{--                                                <div class="flex relative cursor-pointer rounded-md bg-white font-medium text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 hover:text-indigo-500">--}}
{{--                                                    <svg class="text-center mx-auto h-12 w-12 text-indigo-600"--}}
{{--                                                         stroke="currentColor" fill="none" viewBox="0 0 48 48"--}}
{{--                                                         aria-hidden="true">--}}
{{--                                                        <path stroke-linecap="round" stroke-linejoin="round"--}}
{{--                                                              d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>--}}
{{--                                                        <path stroke-linecap="round" stroke-linejoin="round"--}}
{{--                                                              d="M15.91 11.672a.375.375 0 010 .656l-5.603 3.113a.375.375 0 01-.557-.328V8.887c0-.286.307-.466.557-.327l5.603 3.112z"/>--}}
{{--                                                    </svg>--}}
{{--                                                    <span style="padding-top:2px;" class="">영상 업로드</span>--}}
{{--                                                </div>--}}
                                                <input id="video" name="video" type="file">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
{{--                        <div--}}
{{--                            class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5">--}}
{{--                            <label for="video_tel" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">영상--}}
{{--                                옵션--}}
{{--                            </label>--}}
{{--                            <div class="mt-1 sm:col-span-2 sm:mt-0">--}}
{{--                                <fieldset class="space-y-5">--}}
{{--                                    <div class="relative flex items-start">--}}
{{--                                        <div class="flex h-5 items-center">--}}
{{--                                            <input id="repeat" aria-describedby="repeat-description" name="repeat" value="1"--}}
{{--                                                   type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">--}}
{{--                                        </div>--}}
{{--                                        <div class="ml-3 text-sm">--}}
{{--                                            <label for="repeat" class="font-medium text-gray-700">반복유무</label>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}

{{--                                </fieldset>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        <div
                            class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 pt-2 sm:pt-5">
                            <label for="title" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">제목
                                <span
                                    class="ml-1 text-lg align-middle required-color">*</span>
                            </label>
                            <div class="mt-1 sm:col-span-2 sm:mt-0">
                                <input type="text" required maxlength="30" name="title" id="title"
                                       placeholder="제목을 입력하세요"
                                       autocomplete="given-name"
                                       class="placeholder-gray-400 block w-full max-w-xl rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:max-w-sm sm:text-sm">
                            </div>
                        </div>
                        <div
                            class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 pt-2 sm:pt-5">
                            <label for="video_tel" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">연락할 전화번호
                            </label>
                            <div class="mt-1 sm:col-span-2 sm:mt-0">
                                <input type="text" maxlength="50" name="video_tel" id="video_tel"
                                       placeholder="전화번호를 입력하세요"
                                       class="placeholder-gray-400 block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:max-w-sm sm:text-sm">
                            </div>
                        </div>
                        <div
                            class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 pt-2 sm:pt-5">
                            <label for="deceased" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">고인명
                            </label>
                            <div class="mt-1 sm:col-span-2 sm:mt-0">
                                <input type="text" maxlength="50" name="deceased" id="deceased" placeholder="고인명을 입력하세요"
                                       class="placeholder-gray-400 block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:max-w-sm sm:text-sm">
                            </div>
                        </div>
                        <div
                            class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 pt-2 sm:pt-5">
                            <label for="birth" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">출생일
                            </label>
                            <div class="mt-1 sm:col-span-2 sm:mt-0">
                                <input type="text" maxlength="50" name="birth" id="birth" placeholder="출생일을 입력하세요"
                                       class="placeholder-gray-400 block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:max-w-sm sm:text-sm">
                            </div>
                        </div>
                        <div
                            class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 pt-2 sm:pt-5">
                            <label for="death" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">사망일
                            </label>
                            <div class="mt-1 sm:col-span-2 sm:mt-0">
                                <input type="text" maxlength="50" name="death" id="death" placeholder="사망일을 입력하세요"
                                       class="placeholder-gray-400 block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:max-w-sm sm:text-sm">
                            </div>
                        </div>
                    </div>
                    <div class="pt-5 mb-5">
                        <div class="flex justify-between md:justify-end">
                            <button type="submit"
                                    {{--                    onclick="handleSubmit()" type="button"--}}
                                    class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-10 text-md font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                등록
                            </button>
                            <a href="{{route('videos.index')}}"
                               class="ml-3 rounded-md border border-gray-300 bg-white py-2 px-10 text-md font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">취소</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script src="{{ URL::asset('js/postcode.v2.js') }}"></script>
    <script>

        let telCheck = false;

        function demoInit() {
            document.querySelector('#title').value = '영상 테스트';
            document.querySelector('#video_tel').value = '01037635613';
            document.querySelector('#deceased').value = '홍길동';
            document.querySelector('#birth').value = '19450505';
            document.querySelector('#death').value = '20200101';
        }

        // demoInit();

        function handleSubmit() {
            if (!document.querySelector('#name').value) {
                alert('이름을 입력해주시기 바랍니다');
                return;
            }

            if (!telCheck) {
                alert('전화번호 중복확인을 해주시기 바랍니다');
                return;
            }

            if (!document.querySelector('#tel').value) {
                alert('전화번호를 입력해주시기 바랍니다');
                return;
            }

            form.submit();
        }

        function telValidator() {

            const table = 'videos';
            const select = 'tel';
            const value = document.querySelector('#tel').value;
            const csrfToken = '{{ csrf_token() }}';

            if (!value) {
                document.querySelector('#telMessage').textContent = '';
                document.querySelector('#telMessage').style.color = '';
                document.querySelector('#telMessage').style.display = 'hidden';
                alert('휴대폰번호를 입력바랍니다.');
                return;
            }

            fetch('/find', {
                method: 'POST',
                body: JSON.stringify(
                    {
                        table: table,
                        select: select,
                        value: {"tel": value}
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
                        telCheck = true;
                        document.querySelector('#telMessage').textContent = '사용가능한 전화번호 입니다';
                        document.querySelector('#telMessage').style.color = 'green';
                        document.querySelector('#telMessage').style.display = 'block';
                    } else {
                        telCheck = false;
                        document.querySelector('#telMessage').textContent = '중복된 전화번호 입니다';
                        document.querySelector('#telMessage').style.color = 'red';
                        document.querySelector('#telMessage').style.display = 'hidden';

                    }
                })
        }

        function retrieveAddress() {
            new daum.Postcode({
                oncomplete: function (response) {
                    document.querySelector('#zoneCode').value = response.zonecode;
                    document.querySelector('#sido').value = response.sido;
                    document.querySelector('#sigungu').value = response.sigungu;
                    document.querySelector('#bname').value = response.bname;
                    document.querySelector('#roadName').value = response.roadname;
                    document.querySelector('#jibunAddr').value = response.jibunAddress;
                    document.querySelector('#roadAddr').value = response.roadAddress;
                    callLatitude(response.roadAddress);
                }
            }).open();
        }

        async function callLatitude(query) {
            await fetch(`https://dapi.kakao.com/v2/local/search/address?query=${query}`, {
                method: 'POST',
                headers: {
                    Authorization: 'KakaoAK fd5fd84910ae6fcd6c644c14d44159c0',
                },
            })
                .then(response => {
                    return response.json();
                })
                .then(result => {
                    document.querySelector('#latitude').value = result.documents[0].y;
                    document.querySelector('#longitude').value = result.documents[0].x;
                })

        }

    </script>
@endsection
