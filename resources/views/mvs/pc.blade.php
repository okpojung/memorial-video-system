@extends ('layouts.mvsPc')

@section('content')

    <div class="isolate" style="z-index: 500">
        <main class="overflow-hidden relative h-full">
            <div class="pt-0">
                <div class="mx-auto h-full">
                    <div id="video-wrap" class="hidden rounded-md bg-white/5 shadow-2xl ring-1 ring-white/10">
                        <video  id="playVideo" autoplay controls class="mx-auto shadow" style="width:100%; max-height:100vh;">
                            <source class="shadow" id="sourceVideo" src="">
                        </video>
                    </div>
                    <div id="image-wrap" class="h-screen opacity-70 shadow mx-auto rounded h-[calc(100vh-77px)] md:h-[calc(100vh-133px)]" style="
                        max-width: 100%;
                        height: 55.9vw;
                        background-size: contain;
                        background-position: center;
                        background-image: url('{{asset('/images/landing/main.png')}}');">
                    </div>
                </div>
            </div>
        </main>
    </div>
    <div class="w-full">
        <div id="videoNav" class="relative z-40 " role="dialog" aria-modal="true">
            <div id="mobile-one"   onclick="videoListClick()"
                 class="transition-opacity ease-linear duration-300 fixed inset-0" style="background: rgba(0, 0, 0, 0.5);">
                <div class="fixed inset-0 z-40 flex">
                    <div onclick="videoListClick()" id="mobile-two"
                         class="-translate-x-full transition ease-in-out duration-300 transform relative flex w-full max-w-sm flex-1 flex-col pt-3 pb-3" style="background: #fff;">
                        <div id="mobile-three" class="opacity-0 ease-in-out duration-300 absolute top-0 right-0 -mr-12 pt-2">
                            <button type="button" onclick="videoListClick()"
                                    class="border bg-gray-800 border-2 ml-1 flex h-10 w-10 items-center justify-center rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                                <span class="sr-only">Close sidebar</span>
                                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                     viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>

                            </button>
                        </div>

                        <div class="rounded-full flex block pb-3 px-3">
                            <input type="text" class="opacity-0 absolute" style="top:-9999px; left:-9999px;" id="dummy-input">
                            <input id="tel" name="id" type="number"
                                   minlength="10" maxlength="12" :rules="validateId" placeholder="전화번호를 입력하세요"
                                   class="block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"/>
                            <div onclick="telRetrieve()" class="px-2 bg-green-400 ml-2 lg:ml-5 rounded-full cursor-pointer hover:bg-indigo-500">
                                <svg class=" rounded flex justify-center items-center w-7 h-10 text-white align-middle" fill="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                                </svg>
                            </div>
                            <div onclick="telRetrieveDemo()" class="px-2 bg-gray-700 ml-2 lg:ml-5 rounded-full cursor-pointer hover:bg-indigo-500">
                                <svg class=" rounded flex justify-center items-center w-7 h-10 text-white align-middle" fill="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.348a1.125 1.125 0 010 1.971l-11.54 6.347a1.125 1.125 0 01-1.667-.985V5.653z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex flex-shrink-0 items-center px-4 border-t pt-3">
                            <div id="customerName" class="text-gray-500 text-lg font-semibold">

                            </div>
                        </div>
                        <div class="h-0 flex-1 overflow-y-auto">
                            <nav id="videoList">
                            </nav>
                        </div>
                    </div>

                    <div class="w-14 flex-shrink-0" aria-hidden="true">
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="w-full">
        <div id="customerNav" class="relative z-40" role="dialog" aria-modal="true">
            <div id="customer-mobile-one"   onclick="customerListClick()"
                 class="transition-opacity ease-linear duration-300 fixed inset-0" style="background: rgba(0, 0, 0, 0.5);">
                <div class="fixed inset-0 z-40 flex">
                    <div onclick="customerListClick()" id="customer-mobile-two"
                         class="-translate-x-full transition ease-in-out duration-300 transform relative flex w-full max-w-sm flex-1 flex-col " style="background: #fff;">
                        <div id="customer-mobile-three" class="opacity-0 ease-in-out duration-300 absolute top-0 right-0 -mr-12 pt-2">
                            <button type="button" onclick="customerListClick()"
                                    class="border bg-gray-800 border-2 ml-1 flex h-10 w-10 items-center justify-center rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                                <span class="sr-only">Close sidebar</span>
                                <!-- Heroicon name: outline/x-mark -->
                                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                     viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        <div class="border-b border-gray-300">
                            <a href="{{route('auth.index')}}"
                               class="border-indigo-600  border-l-8 text-blue-600
                                   group flex items-center px-2 py-3 text-md">
                                <svg class="text-blue-600 mr-3 flex-shrink-0 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                                </svg>
                                <span class="flex-1">관리자 페이지</span>
                            </a>
                        </div>
                        <div class="h-0 flex-1 overflow-y-auto">
                            <nav id="customerList">
                            </nav>
                        </div>
                    </div>

                    <div class="w-14 flex-shrink-0" aria-hidden="true">
                    </div>
                </div>
            </div>

        </div>
    </div>


    <style>
        html, body{
            height:100%;
        }
    </style>
    <script>
        let isOpen = false
        let customerIsOpen = false;

        const playVideo = document.querySelector('#playVideo');

        playVideo.addEventListener('ended', (event) => {
            document.querySelector('#image-wrap').style.display = 'block';
            document.querySelector('#header').style.display = 'block';
            document.querySelector('#video-wrap').style.display = 'none';
        });


        function videoListClick() {
            isOpen = !isOpen;
            customerIsOpen = false;
            customerMenuHandle();
            menuHandle();
        }

        function customerListClick() {
            customerIsOpen = !customerIsOpen;
            isOpen = false;
            menuHandle();
            customerMenuHandle();
        }
        // lg:hidden
        function customerMenuHandle() {
            if (!customerIsOpen) {
                document.querySelector('#customer-mobile-one').classList.add('opacity-0');
                document.querySelector('#customer-mobile-one').classList.remove('opacity-100');

                document.querySelector('#customer-mobile-two').classList.add('-translate-x-full');
                document.querySelector('#customer-mobile-two').classList.remove('translate-x-0');

                document.querySelector('#customer-mobile-three').classList.add('opacity-0');
                document.querySelector('#customer-mobile-three').classList.remove('opacity-100');

                document.querySelector('#customerNav').classList.remove('block');
                document.querySelector('#customerNav').classList.add('hidden');
            } else {
                document.querySelector('#customer-mobile-one').classList.remove('opacity-0');
                document.querySelector('#customer-mobile-one').classList.add('opacity-100');

                document.querySelector('#customer-mobile-two').classList.remove('-translate-x-full');
                document.querySelector('#customer-mobile-two').classList.add('translate-x-0');

                document.querySelector('#customer-mobile-three').classList.remove('opacity-0');
                document.querySelector('#customer-mobile-three').classList.add('opacity-100');

                document.querySelector('#customerNav').classList.remove('hidden');
                document.querySelector('#customerNav').classList.add('block');
            }
        }

        function menuHandle() {
            if (!isOpen) {
                document.querySelector('#mobile-one').classList.add('opacity-0');
                document.querySelector('#mobile-one').classList.remove('opacity-100');

                document.querySelector('#mobile-two').classList.add('-translate-x-full');
                document.querySelector('#mobile-two').classList.remove('translate-x-0');

                document.querySelector('#mobile-three').classList.add('opacity-0');
                document.querySelector('#mobile-three').classList.remove('opacity-100');

                document.querySelector('#videoNav').classList.remove('block');
                document.querySelector('#videoNav').classList.add('hidden');
            } else {
                document.querySelector('#mobile-one').classList.remove('opacity-0');
                document.querySelector('#mobile-one').classList.add('opacity-100');

                document.querySelector('#mobile-two').classList.remove('-translate-x-full');
                document.querySelector('#mobile-two').classList.add('translate-x-0');

                document.querySelector('#mobile-three').classList.remove('opacity-0');
                document.querySelector('#mobile-three').classList.add('opacity-100');

                document.querySelector('#videoNav').classList.remove('hidden');
                document.querySelector('#videoNav').classList.add('block');

                // document.querySelector('#tel').focus();
            }
        }
        customerMenuHandle();
        menuHandle();
    </script>


    <script src="{{env('SOCKET_HOST')}}/socket.io/socket.io.js"></script>
    <script>
        // console.dir(document.querySelector('#video'));

        const dummyData = '[{"id":10,"title":"\uc601\uc0c1 \ud14c\uc2a4\ud2b8","name":"20230209001354_\ub77c\ud750\ub9c8\ub2c8\ub178\ud504_\ubaa8\uc74c.mp4","size":447317166,"playtime_string":"48:58","original_name":"\ub77c\ud750\ub9c8\ub2c8\ub178\ud504_\ubaa8\uc74c.mp4","deceased":"\ud64d\uae38\ub3d9","birth":"19450505","video_tel":"01037635613","death":"20200101","video_url":"mvs\/20230209001354_\ub77c\ud750\ub9c8\ub2c8\ub178\ud504_\ubaa8\uc74c.mp4","thumbnail_url":"thumbnail\/20230209001354_\ub77c\ud750\ub9c8\ub2c8\ub178\ud504_\ubaa8\uc74c_thumbnail.jpg","created_at":"2023-02-09 00:13:55"},{"id":12,"title":"\uc601\uc0c1 \ud14c\uc2a4\ud2b8","name":"20230203144754_\uc9c0\ube0c\ub9ac.mp4","size":34676961,"playtime_string":"1:00:21","original_name":"\uc9c0\ube0c\ub9ac.mp4","deceased":"\ud64d\uae38\ub3d9","birth":"19450505","video_tel":"01037635613","death":"20200101","video_url":"mvs\/20230203144754_\uc9c0\ube0c\ub9ac.mp4","thumbnail_url":"thumbnail\/20230203144754_\uc9c0\ube0c\ub9ac_thumbnail.jpg","created_at":"2023-02-03 14:47:54"}]';

        let videoData;

        var socket = io.connect('192.168.94.101',{
        });
        socket.on('error', function(data) {
            console.log(data);
        });
        socket.on('videos', function(data){ //4
            if(data.statusCode === 200) {
                console.log(data);
                videoReset();
                const videos = JSON.parse(data.videos);
                const customer = JSON.parse(data.customer);

                data.videos = videos;
                data.customer = customer;
                // 영상 하나 바로 재생
                if(videos.length === 1) {
                    // result = result.videos[0];
                    singlePlay(data);
                    play(videos[0]);
                    navClose();

                    // const playTime = (result.playtime_seconds * 1000) + 3000;

                    // setTimeout(function() {
                    //     fullScreenExit();
                    // }, 5000);
                    // setTimeout(function() {
                    // window.location.href = '/';
                    // },50);
                    // document.getElementById("sourceVideo").requestFullscreen();
                    // getVideo .volume = 0.5;
                    //     video.src = result.video_url;
                    //
                    // .setAttribute("src", geturl);
                    //     getVideo .load()
                    //     getVideo .play();
                    //     getVideo .volume = 0.5;
                    //     console.log(video);
                    //     video.play();
                    // console.log(document.querySelector('#video').src);
                    // window.location.href = link;       //웹개발할때 숨쉬듯이 작성할 코드
                    // window.location.replace(link);     // 이전 페이지로 못돌아감
                    // window.open(link);
                }
                else {
                    multiPlay(data);
                }
            }
        });

        function videoReset() {
            let videoItems = document.querySelectorAll('.video-item');
            document.querySelector('#customerName').innerHTML = '';

            videoItems.forEach(videoItem => {
                videoItem.remove();
            })
        }

        function videoSelect(index) {
            // const videos = JSON.parse(dummyData);
            play(videoData[index]);
            navClose();
        }

        function navOpen() {
            isOpen = true;
            menuHandle();
        }

        function navClose() {
            isOpen = false;
            menuHandle();
        }

        function singlePlay(data) {
            const videos = data.videos;
            const customer = data.customer;
            const videoList = document.querySelector('#videoList');
            const customerName = document.querySelector('#customerName');

            videoData = videos;

            videos.forEach((video,index) => {
                const item = `<div onclick="videoSelect(${index})" class="video-item px-4 hover:bg-gray-100 space-y-1 relative border-b pb-3 pt-3"><div class="relative group flex items-center text-base leading-5 font-medium rounded-md"><img class="h-40 object-cover rounded w-screen" src="${video.thumbnail_url}" alt=""><div class="text-white text-center text-8xl font-semibold flex items-center justify-center w-full h-full absolute top-0 left-0 bg-gray-900"  style="background: rgba(0, 0, 0, 0.3);">${index+1}
</div></div><div class="text-gray-700 flex justify-between font-semibold pl-1 pr-3"><div class="text-lg">${video.title}</div><div class="text-lg">${video.playtime_string}</div></div><div class="text-gray-500 flex pl-1"><div class="text-base">${video.deceased}</div><div class="text-base">(${video.birth} ~ ${video.death})</div></div></div>`;
                videoList.insertAdjacentHTML('beforeend', item);
            })
            customerName.innerHTML = customer.name;
        }

        function multiPlay(data) {

            console.log(data);

            const videos = data.videos;
            const customer = data.customer;
            const videoList = document.querySelector('#videoList');
            const customerName = document.querySelector('#customerName');

            videoData = videos;

            videos.forEach((video,index) => {
                const item = `<div onclick="videoSelect(${index})" class="video-item px-4 hover:bg-gray-100 space-y-1 relative border-b pb-3 pt-3"><div class="relative group flex items-center text-base leading-5 font-medium rounded-md"><img class="h-40 object-cover rounded w-screen" src="${video.thumbnail_url}" alt=""><div class="text-white text-center text-8xl font-semibold flex items-center justify-center w-full h-full absolute top-0 left-0 bg-gray-900"  style="background: rgba(0, 0, 0, 0.3);">${index+1}
</div></div><div class="text-gray-700 flex justify-between font-semibold pl-1 pr-3"><div class="text-lg">${video.title == null ? '': video.title }</div><div class="text-lg">${video.playtime_string == null ? '': video.playtime_string }</div></div><div class="text-gray-500 flex pl-1"><div class="text-base">${video.deceased == null ? '': video.deceased }</div><div class="text-base">${video.birth == null ? '': '('+video.birth}  ${video.death == null ? '': ' ~ '+video.death+')'}</div></div></div>`;
                videoList.insertAdjacentHTML('beforeend', item);
            })
            customerName.innerHTML = customer.name;
            // console.log(customer);
            // console.log(videos);
            navOpen();

        }

        function play(result) {
            var getVideo = document.getElementById("playVideo");
            var getSource = document.getElementById("sourceVideo");
            getSource.setAttribute("src", result.video_url);
            getVideo.load();
            getVideo.play();
            fullScreen();

            document.querySelector('#image-wrap').style.display = 'none';
            document.querySelector('#header').style.display = 'none';
            document.querySelector('#video-wrap').style.display = 'block';

            // getVideo.requestFullscreen();

            // let time = Math.floor(result.playtime_seconds) * 1000;
            // time = time + 10000;
            // setTimeout(function() {
            //     document.querySelector('#image-wrap').style.display = 'block';
            //     document.querySelector('#video-wrap').style.display = 'none';
            //
            // }, time);



        }
        function fullScreenExit() {
            if (document.fullscreenElement) {
                document.exitFullscreen()
                    .then(() => console.log("Document Exited from Full screen mode"))
                    .catch((err) => console.error(err))
            } else {
                document.documentElement.requestFullscreen();
            }
        }

        function telRetrieve() {
            const tel = document.querySelector('#tel').value;

            if(!tel) {
                alert('전화번호를 입력해주시기 바랍니다');
                return;
            }

            if(!(tel.length == 11 || tel.length == 10)) {
                alert('올바른 전화번호 형식이 아닙니다');
                return;
            }

            const csrfToken = '{{ csrf_token() }}';

            fetch(`/video?tel=${tel}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    "X-CSRF-Token": csrfToken
                }
            })
                .then(response => {
                    return response.json();
                })
                .then(data => {
                    if (data.statusCode === 200) {
                        videoReset();
                        if(data.videos.length === 1) {
                            singlePlay(data);
                            play(data.videos[0]);
                            navClose();
                        }
                        else {
                            multiPlay(data);
                        }
                    }
                    else {
                        alert(data.statusMessage);
                    }

                    document.querySelector('#tel').value = '';
                    console.log(1);
                    document.querySelector('#dummy-input').focus();
                })


        }

        function telRetrieveDemo() {
            const csrfToken = '{{ csrf_token() }}';

            fetch(`/video?tel=01000000000`, {
                method: 'GET',
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
                    if (data.statusCode === 200) {
                        videoReset();
                        if(data.videos.length === 1) {
                            singlePlay(data);
                            play(data.videos[0]);
                            navClose();
                        }
                        else {
                            multiPlay(data);
                        }
                    }
                    else {
                        alert(data.statusMessage);
                    }
                })

        }

        function fullScreen() {
            // var getVideo = document.getElementById("playVideo");
            // getVideo.requestFullscreen();
            var elem = document.getElementById("fullScreen");
            if (elem.requestFullscreen) {
                elem.requestFullscreen();
            }
        }

        window.addEventListener("keydown", (event) => {
            switch (event.key) {
                case '-' :
                    if (confirm("새로고침을 하시겠습니까?")) {
                        location.reload();
                    }
                    break;
                case '*' :
                    if (confirm("홍보영상을 재생하시겠습니까?")) {
                        telRetrieveDemo();
                    }
                    break;
                case '/' :
                    if(document.activeElement.id !== 'tel') {
                        document.querySelector('#tel').focus();
                    }
                    else {
                        document.querySelector('#dummy-input').focus();
                    }
                    break;
                case '+' :
                    videoListClick();
                    break;
                case 'Enter' :
                    if(document.activeElement.id === 'tel') {
                        telRetrieve();
                    }
                    break;
                case '1' :
                    if(document.activeElement.id !== 'tel' && isOpen && videoData !== undefined && videoData[0] !== undefined) {
                        videoSelect(0);
                    }
                    break;
                case '2' :
                    if(document.activeElement.id !== 'tel' && isOpen && videoData !== undefined && videoData[1] !== undefined) {
                        videoSelect(1);
                    }
                    break;
                case '3' :
                    if(document.activeElement.id !== 'tel' && isOpen && videoData !== undefined && videoData[2] !== undefined) {
                        videoSelect(2);
                    }
                    break;
                case '4' :
                    if(document.activeElement.id !== 'tel' && isOpen && videoData !== undefined && videoData[3] !== undefined) {
                        videoSelect(3);
                    }
                    break;
                case '5' :
                    if(document.activeElement.id !== 'tel' && isOpen && videoData !== undefined && videoData[4] !== undefined) {
                        videoSelect(4);
                    }
                    break;
                case '6' :
                    if(document.activeElement.id !== 'tel' && isOpen && videoData !== undefined && videoData[5] !== undefined) {
                        videoSelect(5);
                    }
                    break;
                case '7' :
                    if(document.activeElement.id !== 'tel' && isOpen && videoData !== undefined && videoData[6] !== undefined) {
                        videoSelect(6);
                    }
                    break;
                case '8' :
                    if(document.activeElement.id !== 'tel' && isOpen && videoData !== undefined && videoData[7] !== undefined) {
                        videoSelect(7);
                    }
                    break;
                case '9' :
                    if(document.activeElement.id !== 'tel' && isOpen && videoData !== undefined && videoData[8] !== undefined) {
                        videoSelect(8);
                    }
                    break;

            }

        });
    </script>

@endsection
