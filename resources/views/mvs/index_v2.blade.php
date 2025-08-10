@extends ('layouts.fullIndex')

@section('content')


    <div class="isolate">
        <div class="absolute inset-x-0 top-[-10rem] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[-20rem]">
            <svg class="relative left-[calc(50%-11rem)] -z-10 h-[21.1875rem] max-w-none -translate-x-1/2 rotate-[30deg] sm:left-[calc(50%-30rem)] sm:h-[42.375rem]" viewBox="0 0 1155 678">
                <path fill="url(#f4773080-2a16-4ab4-9fd7-579fec69a4f7)" fill-opacity=".2" d="M317.219 518.975L203.852 678 0 438.341l317.219 80.634 204.172-286.402c1.307 132.337 45.083 346.658 209.733 145.248C936.936 126.058 882.053-94.234 1031.02 41.331c119.18 108.451 130.68 295.337 121.53 375.223L855 299l21.173 362.054-558.954-142.079z" />
                <defs>
                    <linearGradient id="f4773080-2a16-4ab4-9fd7-579fec69a4f7" x1="1155.49" x2="-78.208" y1=".177" y2="474.645" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#9089FC"></stop>
                        <stop offset="1" stop-color="#FF80B5"></stop>
                    </linearGradient>
                </defs>
            </svg>
        </div>
        <div class="px-6 pt-6 lg:px-8">
            <nav class="flex items-center justify-between" aria-label="Global">
                <div class="lg:flex lg:flex-1 lg:justify-end">
                    <a href="{{route('auth.index')}}" class="bg-gray-500 px-4 py-3 rounded-md text-sm font-semibold leading-6 text-white">관리자 &nbsp;&nbsp;<span aria-hidden="true">&rarr;</span></a>
                </div>
            </nav>
        </div>
        <main>
            <div class="relative pt-0 lg:pb-40">
                <div class="mx-auto max-w-7xl px-6 lg:px-8">
                    <div class="mx-auto w-full text-center">
                        <div class="mx-auto w-14 lg:w-28">
                            <img class=""
                                 src="/images/logo/main_logo.svg"
                                 alt=""/>
                        </div>

                    </div>
                    <div class="text-center pt-4 lg:pt-9 pb-3">
                        <button class="main-color text-center rounded-md w-full lg:text-6xl leading-7 text-white shadow-sm  focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-400">070-7540-1486</button>
                    </div>

                    <div class="text-center">
                        <button class="text-gray-200 mt-2 text-center text-md lg:text-xl leading-7">전화를 걸면 영상이 재생됩니다</button>

                    </div>
                    <div class="mt-5 lg:mt-16 rounded-md bg-white/5 shadow-2xl ring-1 ring-white/10 ">
                        <video  id="playVideo" autoplay controls class="w-screen">
                            <source  id="sourceVideo" src="{{asset('storage/media/test.mp4')}}">
                        </video>
                    </div>

{{--                    <img src="https://tailwindui.com/img/component-images/dark-project-app-screenshot.png" alt="App screenshot" width="2432" height="1442" class="mt-5 lg:mt-16 rounded-md bg-white/5 shadow-2xl ring-1 ring-white/10 ">--}}
                </div>
                <div class="absolute inset-x-0 top-[calc(100%-13rem)] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[calc(100%-30rem)]">
                    <svg class="relative left-[calc(50%+3rem)] h-[21.1875rem] max-w-none -translate-x-1/2 sm:left-[calc(50%+36rem)] sm:h-[42.375rem]" viewBox="0 0 1155 678">
                        <path fill="url(#ee0717bf-3e43-49df-b1bd-de36422ed3d3)" fill-opacity=".2" d="M317.219 518.975L203.852 678 0 438.341l317.219 80.634 204.172-286.402c1.307 132.337 45.083 346.658 209.733 145.248C936.936 126.058 882.053-94.234 1031.02 41.331c119.18 108.451 130.68 295.337 121.53 375.223L855 299l21.173 362.054-558.954-142.079z" />
                        <defs>
                            <linearGradient id="ee0717bf-3e43-49df-b1bd-de36422ed3d3" x1="1155.49" x2="-78.208" y1=".177" y2="474.645" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#9089FC"></stop>
                                <stop offset="1" stop-color="#FF80B5"></stop>
                            </linearGradient>
                        </defs>
                    </svg>
                </div>
            </div>
        </main>
    </div>

    <script src="http://192.168.94.101:6001/socket.io/socket.io.js"></script>
    <script>
        // console.dir(document.querySelector('#video'));

        var socket = io.connect('http://localhost:6001',{
        });
        socket.on('error', function(data)
        {
            console.log(data);
        });
        socket.on('videos', function(data){ //4

            if(data.statusCode === 200) {
                let result = JSON.parse(data.result);
                if(result.length === 1) {
                    result = result[0];
                    var getVideo = document.getElementById("playVideo");
                    var getSource = document.getElementById("sourceVideo");
                    getSource.setAttribute("src", result.video_url);
                    getVideo.load();
                    getVideo.play();

                    var elem = document.getElementById("playVideo");
                    if (elem.requestFullscreen) {
                        elem.requestFullscreen();
                    } else if (elem.webkitRequestFullscreen) { /* Safari */
                        elem.webkitRequestFullscreen();
                    } else if (elem.msRequestFullscreen) { /* IE11 */
                        elem.msRequestFullscreen();
                    }
                    // const playTime = (result.playtime_seconds * 1000) + 3000;

                    setTimeout(function() {
                        fullScreenExit();
                    }, 5000);
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

                }
            }
        });

        function fullScreenExit() {
            if (document.fullscreenElement) {
                document.exitFullscreen()
                    .then(() => console.log("Document Exited from Full screen mode"))
                    .catch((err) => console.error(err))
            } else {
                document.documentElement.requestFullscreen();
            }
        }
    </script>
@endsection
