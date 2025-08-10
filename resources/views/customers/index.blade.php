@extends ('layouts.index')

@section('content')
{{--    {{dump($paginator)}}--}}
    <div class="">
        <div class="mt-8">
            <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
                <div class="">
                    <div class="sm:flex sm:items-center">
                        <div class="sm:flex-auto">
                            <h1 class="text-xl font-semibold text-gray-900">고객 목록</h1>
                        </div>
                    </div>
                    <div class="justify-between flex mt-5 lg:mt-6">
                        <form method="GET" action="{{route('customers.index')}}" class="mt-0 sm:flex sm:max-w-lg lg:mt-0 ">
                            <select name="sfl" id="sfl" autocomplete="email" class="mr-3 w-full min-w-0 appearance-none rounded-md border-gray-300 bg-white px-[calc(theme(spacing.3)-1px)] py-[calc(theme(spacing[1.5])-1px)] text-base leading-7 text-gray-900 placeholder-gray-400 shadow-sm focus:border-indigo-600 focus:ring-indigo-600 sm:w-56 sm:text-sm sm:leading-6" >
                                <option value="name" @if($sfl == 'name') selected  @endif>이름</option>
                                <option value="tel" @if($sfl == 'tel') selected  @endif>휴대폰번호</option>
                            </select>
                            <input type="text"
                                   value="@if($stx) {{$stx}} @endif"
                                   name="stx" id="stx" autocomplete="stx"  class="mt-2 lg:mt-0 w-full min-w-0 appearance-none rounded-md border-gray-300 bg-white px-[calc(theme(spacing.3)-1px)] py-[calc(theme(spacing[1.5])-1px)] text-base leading-7 text-gray-900 placeholder-gray-400 shadow-sm focus:border-indigo-600 focus:ring-indigo-600 sm:w-100 sm:text-sm sm:leading-6" placeholder="검색어를 입력하세요">
                            <div class="mt-4 rounded-md sm:mt-0 sm:ml-4 sm:flex-shrink-0">
                                <button type="submit" class="flex w-full items-center justify-center rounded-md bg-gray-600 py-1.5 px-6 text-base  leading-7 text-white hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 sm:text-sm sm:leading-6">검색</button>
                            </div>
                        </form>
                        <a href="{{route('customers.create')}}" class="ml-5 text-center inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-6 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">고객 추가</a>
                    </div>

                    <div class="-mx-4 mt-8 overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:-mx-6 md:mx-0 md:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-300 text-center">
                            <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="text-center hidden md:block py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">No.</th>
                                <th scope="col" class="text-center px-14 py-3.5 text-left text-sm font-semibold text-gray-900 lg:table-cell">고객명</th>
                                <th scope="col" class="text-center hidden lg:table-cell px-14 py-3.5 text-left text-sm font-semibold text-gray-900">휴대폰번호</th>
                                <th scope="col" class="text-center hidden lg:table-cell px-14 py-3.5 text-left text-sm font-semibold text-gray-900">등록일</th>
                                <th scope="col" class="text-center px-3 py-3.5 text-left text-sm font-semibold text-gray-900">편집</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach($customers as $customer)
                                <tr>
                                <td class="text-center max-w-0 py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:w-auto sm:max-w-none sm:pl-6">
                                    <div class="hidden lg:inline-block text-center flex justify-center items-center">
                                        {{$count--}}
                                    </div>
                                    <dl class="font-normal lg:hidden">
                                        <dd class="mt-1 truncate text-gray-700">{{$customer->name}}</dd>
                                        <dd class="mt-1 truncate text-gray-700">{{$customer->tel}}</dd>
                                    </dl>
                                </td>
                                <td class="hidden lg:table-cell px-14 py-4 text-sm text-gray-500">{{$customer->name}}</td>
                                <td class="hidden lg:table-cell px-14 py-4 text-sm text-gray-500">{{$customer->tel}}</td>
                                <td class="hidden lg:table-cell px-14 py-4 text-sm text-gray-500">{{substr($customer->created_at,0,10)}}</td>
                                <td class="text-right py-4 pl-3 pr-4 text-sm font-medium sm:pr-6">
                                    <div>
                                        <a href="#" onclick="openVideoList('/customers/{{$customer->id}}/video', this);" class="text-center w-full block bg-green-600 py-2 px-3 rounded-md mr-3  text-white hover:text-gray-300">영상매칭<span class="sr-only">, Lindsay Walton</span></a>
                                        <a href="/customers/{{$customer->id}}/view" class="text-center mt-2 w-full block bg-blue-400 py-2 px-3 rounded-md mr-0 lg:mr-3 text-white hover:text-gray-300">상세보기<span class="sr-only">, Lindsay Walton</span></a>
                                    </div>
                                        <a href="/customers/{{$customer->id}}/edit" class="text-center mt-2 w-full block bg-yellow-400 py-2 px-3 rounded-md mr-3  text-gray-700 hover:text-white">수정<span class="sr-only">, Lindsay Walton</span></a>
                                    @if($user->role_id == 1)
                                        <form id="delete-{{$customer->id}}" class="" method="POST" action="/customers/{{$customer->id}}">
                                            @method('delete')
                                            @csrf
                                            <button onclick="deleteHandle({{$customer->id}})" type="button" class="mt-2 w-full block bg-red-300 py-2 px-3 rounded-md text-gray-700 hover:text-white">삭제<span class="sr-only">, Lindsay Walton</span></button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            <!-- More people... -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="pt-10 flex items-center justify-center">
                    {{ $customers->links() }}

                </div>


            </div>
        </div>
    </div>
<script>
    function openVideoList(url, field) {
        var opt = "toolbar=no, resizable=yes, scrollbars=yes, location=no, resize=no, menubar=no, directories=no, copyhistory=0, width=800, height=1200, top=100, left=370";
        window.open(url, 'new_window', opt);
    }
    function deleteHandle(id) {
        if (confirm("삭제 하시겠습니까?")) {
            document.querySelector('#delete-'+id).submit();
        }
    }
</script>
@endsection
