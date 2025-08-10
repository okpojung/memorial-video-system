@extends ('layouts.index')

@section('content')
    <div class="">
        <div class="mt-8">
            <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
                    <div class="sm:flex sm:items-center">
                        <div class="sm:flex-auto">
                            <h1 class="text-xl font-semibold text-gray-900">고객 추가</h1>
                        </div>
                    </div>
                <form id="form" method="POST" action="/customers/{{$customer->id}}" class="">
                    @method('patch')
                    @csrf
                    <div class="space-y-8 divide-y divide-gray-200 sm:space-y-5">
                        <div class="space-y-6 pt-8 sm:space-y-5 sm:pt-10">
                            <div class="space-y-6 sm:space-y-5">
                                <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5">
                                    <label for="name" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">고객명
                                        <span
                                            class="ml-1 text-lg align-middle required-color">*</span>
                                    </label>
                                    <div class="mt-1 sm:col-span-2 sm:mt-0">
                                        <input type="text" value="@isset($customer->name) {{$customer->name}} @endisset" required maxlength="10" name="name" id="name" placeholder="고객명을 입력하세요" autocomplete="given-name" class="placeholder-gray-400 block w-full max-w-xl rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:max-w-sm sm:text-sm">
                                    </div>
                                </div>
                                <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5">
                                    <label for="tel" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">이메일</label>
                                    <div class="mt-1 sm:col-span-2 sm:mt-0">
                                        <input value="@isset($customer->email) {{$customer->email}} @endisset" type="text" maxlength="50" name="email" id="email" placeholder="이메일을 입력하세요" class="placeholder-gray-400 block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:max-w-sm sm:text-sm">
                                    </div>
                                </div>
                                <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5">
                                    <label for="email" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2 mb-3 lg:mb-0">주소</label>
                                    <div class="sm:col-span-2">
                                        <input type="hidden" value="@isset($customer->sido) {{$customer->sido}} @endisset" name="sido" id="sido">
                                        <input type="hidden" value="@isset($customer->sigungu) {{$customer->sigungu}} @endisset" name="sigungu" id="sigungu">
                                        <input type="hidden" value="@isset($customer->bname) {{$customer->bname}} @endisset" name="bname" id="bname">
                                        <input type="hidden" value="@isset($customer->roadname) {{$customer->roadname}} @endisset" name="roadname" id="roadName">
                                        <input type="hidden" value="@isset($customer->jibun_address) {{$customer->jibun_address}} @endisset" name="jibun_address" id="jibunAddr">
                                        <input type="hidden" value="@isset($customer->latitude) {{$customer->latitude}} @endisset" name="latitude" id="latitude">
                                        <input type="hidden" value="@isset($customer->longitude) {{$customer->longitude}} @endisset" name="longitude" id="longitude">
                                        <div class="flex flex-row">
                                            <input value="@isset($customer->zone_code) {{$customer->zone_code}} @endisset" name="zone_code" id="zoneCode" type="text" required readonly class="placeholder-gray-400 readonly basis-4/12 mr-6 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" minlength="2" maxlength="12" placeholder="우편번호">
                                            <div onclick="retrieveAddress()" id="addressBtn" class="rounded green-bg green-bg-hover green-border basis-10/12 lg:basis-2/12 h-full cursor-pointer pt-2 pb-2 w-100 w-100 text-center text-white text-sm"> 주소검색 </div>
                                        </div>
                                        <div class="pt-3 sm:col-span-2 sm:mt-0">
                                            <input value="@isset($customer->road_address) {{$customer->road_address}} @endisset" type="text" readonly placeholder="주소" maxlength="12" name="road_address" id="roadAddr"  class="placeholder-gray-400 readonly block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:max-w-sm sm:text-sm">
                                        </div>
                                        <div class="pt-3 sm:col-span-2 sm:mt-0">
                                            <input value="@isset($customer->detail_address) {{$customer->detail_address}} @endisset" type="text" placeholder="상세주소를 입력하세요" maxlength="12" name="detail_address" id="detailAddress"  class="placeholder-gray-400 block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:max-w-sm sm:text-sm">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="pt-5 mb-5">
                        <div class="flex justify-between md:justify-end">
                            <button onclick="handleSubmit()" type="button" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-10 text-md font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">수정</button>
                            <a href="{{route('customers.index')}}" class="ml-3 rounded-md border border-gray-300 bg-white py-2 px-10 text-md font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">취소</a>
                        </div>
                    </div>
                </form>



            </div>
        </div>
    </div>

    <script src="{{ URL::asset('js/postcode.v2.js') }}"></script>
    <script>

        let telCheck = false;

        // function demoInit() {
        //     document.querySelector('#name').value = '정제영';
        //     document.querySelector('#tel').value = '01037635613';
        //     document.querySelector('#email').value = 'seongs70@naver.com';
        // }
        // demoInit();
        function handleSubmit() {
            if (!document.querySelector('#name').value) {
                alert('이름을 입력해주시기 바랍니다');
                return;
            }
            form.submit();
        }

        function telValidator() {

            const table = 'customers';
            const select = 'tel';
            const value = document.querySelector('#tel').value;
            const csrfToken = '{{ csrf_token() }}';

            if(!value) {
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
                        table : table,
                        select : select,
                        value : {"tel": value}
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
                    console.log(data);
                    if(data.statusCode === 401) {
                        telCheck = true;
                        document.querySelector('#telMessage').textContent = '사용가능한 전화번호 입니다';
                        document.querySelector('#telMessage').style.color = 'green';
                        document.querySelector('#telMessage').style.display = 'block';
                    }
                    else {
                        telCheck = false;
                        document.querySelector('#telMessage').textContent = '중복된 전화번호 입니다';
                        document.querySelector('#telMessage').style.color = 'red';
                        document.querySelector('#telMessage').style.display = 'block';

                    }
                })
        }

        function retrieveAddress() {
            new daum.Postcode({
                oncomplete: function(response) {
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
