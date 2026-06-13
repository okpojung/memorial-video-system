@extends ('layouts.index')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">추모영상 재생관리</h1>
                <p class="mt-2 text-sm text-gray-500">
                    PC 모니터에서 단말기 재생 상태를 실시간으로 확인하고, 조회한 추모영상을 선택한 TV에 재생합니다.
                </p>
            </div>
            <div class="flex gap-2">
                <button type="button" onclick="setGrid(4)"
                        class="rounded-md bg-gray-700 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-900">
                    4분할
                </button>
                <button type="button" onclick="setGrid(9)"
                        class="rounded-md bg-gray-700 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-900">
                    9분할
                </button>
            </div>
        </div>

        <div id="terminalGrid" class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach($terminals as $terminal)
                <div id="terminal-card-{{$terminal['key']}}"
                     class="terminal-card rounded-lg border border-gray-200 bg-white shadow-sm"
                     data-terminal-key="{{$terminal['key']}}">
                    <div class="flex items-center justify-between border-b border-gray-200 px-4 py-3">
                        <div>
                            <h2 class="text-base font-semibold text-gray-900">{{$terminal['name']}}</h2>
                            <p class="text-xs text-gray-500">{{$terminal['key']}}</p>
                        </div>
                        <span id="terminal-status-{{$terminal['key']}}"
                              class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-600">
                            대기
                        </span>
                    </div>
                    <div class="aspect-video bg-gray-900 p-4 text-white">
                        <div class="flex h-full flex-col justify-between">
                            <div>
                                <div class="text-xs text-gray-400">현재 재생</div>
                                <div id="terminal-video-{{$terminal['key']}}" class="mt-2 text-lg font-semibold">
                                    재생중인 영상 없음
                                </div>
                                <div id="terminal-customer-{{$terminal['key']}}" class="mt-1 text-sm text-gray-300">
                                    -
                                </div>
                            </div>
                            <div class="flex items-center justify-between text-xs text-gray-400">
                                <span id="terminal-updated-{{$terminal['key']}}">상태 수신 전</span>
                                <a href="{{$terminal['pc_url']}}" target="_blank" class="text-indigo-300 hover:text-indigo-100">
                                    단말기 열기
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3">
                        <button type="button"
                                onclick="selectTerminal('{{$terminal['key']}}')"
                                class="w-full rounded-md border border-indigo-600 px-3 py-2 text-sm font-semibold text-indigo-700 hover:bg-indigo-50">
                            이 TV 선택
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8 grid grid-cols-1 gap-6 xl:grid-cols-3">
            <div class="rounded-lg bg-white p-5 shadow xl:col-span-1">
                <h2 class="text-lg font-semibold text-gray-900">영상 조회</h2>
                <p class="mt-1 text-sm text-gray-500">유족 휴대폰번호, 유족명, 고인명으로 조회합니다.</p>
                <div class="mt-4">
                    <label for="keyword" class="block text-sm font-medium text-gray-700">검색어</label>
                    <div class="mt-1 flex gap-2">
                        <input id="keyword" type="text"
                               class="block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               placeholder="예: 01012345678, 홍길동">
                        <button type="button" onclick="searchVideos()"
                                class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                            검색
                        </button>
                    </div>
                </div>

                <div class="mt-5 rounded-md bg-gray-50 p-4">
                    <div class="text-sm font-semibold text-gray-700">선택 정보</div>
                    <dl class="mt-3 space-y-2 text-sm">
                        <div class="flex justify-between gap-3">
                            <dt class="text-gray-500">TV</dt>
                            <dd id="selectedTerminalLabel" class="font-medium text-gray-900">선택 안됨</dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt class="text-gray-500">영상</dt>
                            <dd id="selectedVideoLabel" class="text-right font-medium text-gray-900">선택 안됨</dd>
                        </div>
                    </dl>
                    <button type="button" onclick="sendPlayCommand()"
                            class="mt-4 w-full rounded-md bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700">
                        선택 TV에 재생
                    </button>
                    <div id="commandMessage" class="mt-3 text-sm"></div>
                </div>
            </div>

            <div class="rounded-lg bg-white p-5 shadow xl:col-span-2">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">조회된 영상</h2>
                    <span id="resultCount" class="text-sm text-gray-500">0건</span>
                </div>
                <div id="searchResults" class="mt-4 grid grid-cols-1 gap-4 lg:grid-cols-2">
                    <div class="rounded-md border border-dashed border-gray-300 p-6 text-center text-sm text-gray-500 lg:col-span-2">
                        검색어를 입력하고 영상을 조회하세요.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{$socketHost}}/socket.io/socket.io.js"></script>
    <script>
        const csrfToken = @json(csrf_token());
        const searchUrl = @json(route('playback-management.search'));
        const playUrl = @json(route('playback-management.play'));
        const socketHost = @json($socketHost);

        let selectedTerminalKey = null;
        let selectedVideo = null;

        function setGrid(size) {
            const grid = document.querySelector('#terminalGrid');
            grid.classList.remove('md:grid-cols-2', 'xl:grid-cols-3');

            if (size === 4) {
                grid.classList.add('md:grid-cols-2');
            } else {
                grid.classList.add('md:grid-cols-2', 'xl:grid-cols-3');
            }

            document.querySelectorAll('.terminal-card').forEach((card, index) => {
                card.classList.toggle('hidden', size === 4 && index >= 4);
            });
        }

        function selectTerminal(terminalKey) {
            selectedTerminalKey = terminalKey;
            document.querySelector('#selectedTerminalLabel').textContent = terminalKey;

            document.querySelectorAll('.terminal-card').forEach((card) => {
                card.classList.remove('ring-2', 'ring-indigo-500');
            });
            document.querySelector(`#terminal-card-${terminalKey}`).classList.add('ring-2', 'ring-indigo-500');
        }

        async function searchVideos() {
            const keyword = document.querySelector('#keyword').value;
            const response = await fetch(`${searchUrl}?keyword=${encodeURIComponent(keyword)}`, {
                headers: {
                    'Accept': 'application/json',
                },
            });
            const data = await response.json();
            renderSearchResults(data.items || []);
        }

        function renderSearchResults(items) {
            const wrap = document.querySelector('#searchResults');
            document.querySelector('#resultCount').textContent = `${items.length}건`;
            wrap.innerHTML = '';

            if (items.length === 0) {
                wrap.innerHTML = '<div class="rounded-md border border-dashed border-gray-300 p-6 text-center text-sm text-gray-500 lg:col-span-2">조회된 영상이 없습니다.</div>';
                return;
            }

            items.forEach((item) => {
                const thumbnail = item.thumbnail_url ? `/${item.thumbnail_url}` : '';
                const card = document.createElement('button');
                card.type = 'button';
                card.className = 'video-result text-left rounded-lg border border-gray-200 p-3 hover:border-indigo-500 hover:bg-indigo-50';
                card.innerHTML = `
                    <div class="flex gap-3">
                        <div class="h-24 w-32 flex-shrink-0 overflow-hidden rounded bg-gray-200">
                            ${thumbnail ? `<img src="${thumbnail}" alt="" class="h-full w-full object-cover">` : ''}
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="truncate text-sm font-semibold text-gray-900">${escapeHtml(item.title || '제목 없음')}</div>
                            <div class="mt-1 text-sm text-gray-600">유족: ${escapeHtml(item.customer_name || '-')} / ${escapeHtml(item.customer_tel || '-')}</div>
                            <div class="mt-1 text-sm text-gray-600">고인: ${escapeHtml(item.deceased || '-')}</div>
                            <div class="mt-1 text-xs text-gray-500">재생시간: ${escapeHtml(item.playtime_string || '-')}</div>
                        </div>
                    </div>
                `;
                card.addEventListener('click', () => selectVideo(item, card));
                wrap.appendChild(card);
            });
        }

        function selectVideo(item, card) {
            selectedVideo = item;
            document.querySelector('#selectedVideoLabel').textContent = `${item.title || '제목 없음'} / ${item.deceased || '-'}`;

            document.querySelectorAll('.video-result').forEach((el) => {
                el.classList.remove('border-indigo-600', 'bg-indigo-50');
            });
            card.classList.add('border-indigo-600', 'bg-indigo-50');
        }

        async function sendPlayCommand() {
            const message = document.querySelector('#commandMessage');
            message.textContent = '';
            message.className = 'mt-3 text-sm';

            if (!selectedTerminalKey) {
                showCommandMessage('재생할 TV를 선택하세요.', false);
                return;
            }

            if (!selectedVideo) {
                showCommandMessage('재생할 영상을 선택하세요.', false);
                return;
            }

            const response = await fetch(playUrl, {
                method: 'POST',
                body: JSON.stringify({
                    terminal_key: selectedTerminalKey,
                    video_id: selectedVideo.video_id,
                    customer_id: selectedVideo.customer_id,
                }),
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-Token': csrfToken,
                },
            });
            const data = await response.json();

            if (data.statusCode === 200) {
                showCommandMessage(data.message || '재생 명령을 전송했습니다.', true);
            } else {
                showCommandMessage(data.message || '재생 명령 전송에 실패했습니다.', false);
            }
        }

        function showCommandMessage(text, success) {
            const message = document.querySelector('#commandMessage');
            message.textContent = text;
            message.className = `mt-3 text-sm ${success ? 'text-green-700' : 'text-red-600'}`;
        }

        function updateTerminalStatus(data) {
            const terminalKey = data.terminalKey;
            const statusEl = document.querySelector(`#terminal-status-${terminalKey}`);
            const videoEl = document.querySelector(`#terminal-video-${terminalKey}`);
            const customerEl = document.querySelector(`#terminal-customer-${terminalKey}`);
            const updatedEl = document.querySelector(`#terminal-updated-${terminalKey}`);

            if (!statusEl || !videoEl || !customerEl || !updatedEl) {
                return;
            }

            statusEl.textContent = statusLabel(data.status);
            statusEl.className = `rounded-full px-2.5 py-1 text-xs font-semibold ${statusClass(data.status)}`;
            videoEl.textContent = data.videoTitle || '재생중인 영상 없음';
            customerEl.textContent = data.customerName || data.message || '-';
            updatedEl.textContent = data.updatedAt || new Date().toLocaleString();
        }

        function statusLabel(status) {
            const labels = {
                online: '온라인',
                heartbeat: '온라인',
                playing: '재생중',
                ended: '종료',
                idle: '대기',
                error: '오류',
            };

            return labels[status] || '대기';
        }

        function statusClass(status) {
            if (status === 'playing') {
                return 'bg-green-100 text-green-700';
            }
            if (status === 'online' || status === 'heartbeat') {
                return 'bg-blue-100 text-blue-700';
            }
            if (status === 'error') {
                return 'bg-red-100 text-red-700';
            }

            return 'bg-gray-100 text-gray-600';
        }

        function escapeHtml(value) {
            return String(value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        if (socketHost) {
            const socket = io.connect(socketHost, {});
            socket.on('videos', function(data) {
                if (data.eventType === 'terminalStatus') {
                    updateTerminalStatus(data);
                }
            });
        }
    </script>
@endsection
