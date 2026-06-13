<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;

class PlaybackManagementController extends Controller
{
    public int $paginate = 15;
    public array $nav = [];

    public function __construct()
    {
        $this->nav['videoCount'] = MvsAuth::getVideoCount();
        $this->nav['customerCount'] = MvsAuth::getCustomerCount();
    }

    public function index()
    {
        $nav = $this->nav;
        $nav['breadcrumbs'][0]['title'] = '추모영상 재생관리';
        $nav['breadcrumbs'][0]['path'] = route('playback-management.index');

        return view('playback-management.index', [
            'route' => 'playback-management',
            'user' => Auth::user(),
            'nav' => $nav,
            'terminals' => $this->terminals(),
            'socketHost' => rtrim((string) env('SOCKET_HOST'), '/'),
        ]);
    }

    public function search(Request $request)
    {
        $keyword = trim((string) $request->input('keyword'));

        $query = DB::table('customer_video')
            ->join('customers', 'customer_video.customer_id', '=', 'customers.id')
            ->join('videos', 'customer_video.video_id', '=', 'videos.id')
            ->select(
                'customers.id as customer_id',
                'customers.name as customer_name',
                'customers.tel as customer_tel',
                'videos.id as video_id',
                'videos.title',
                'videos.playtime_string',
                'videos.deceased',
                'videos.birth',
                'videos.death',
                'videos.video_tel',
                'videos.video_url',
                'videos.thumbnail_url',
                'videos.created_at'
            )
            ->orderBy('videos.created_at', 'desc');

        if ($keyword !== '') {
            $query->where(function ($where) use ($keyword) {
                $where->where('customers.tel', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('customers.name', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('videos.deceased', 'LIKE', '%' . $keyword . '%');
            });
        }

        return response()->json([
            'statusCode' => Response::HTTP_OK,
            'items' => $query->limit(50)->get(),
        ]);
    }

    public function play(Request $request)
    {
        $input = $request->validate([
            'terminal_key' => ['required', 'regex:/^terminal-[1-9]$/'],
            'video_id' => ['required', 'integer', 'exists:videos,id'],
            'customer_id' => ['nullable', 'integer', 'exists:customers,id'],
        ]);

        $video = Video::findOrFail($input['video_id']);
        $customer = !empty($input['customer_id'])
            ? Customer::find($input['customer_id'])
            : null;

        $payload = [
            'eventType' => 'playbackCommand',
            'statusCode' => Response::HTTP_OK,
            'videos' => json_encode([$this->videoPayload($video)]),
            'customer' => json_encode($this->customerPayload($customer)),
            'terminalKey' => $input['terminal_key'],
            'terminalNo' => (int) str_replace('terminal-', '', $input['terminal_key']),
            'channel' => 'videos',
        ];

        Redis::publish('videos', json_encode($payload));

        return response()->json([
            'statusCode' => Response::HTTP_OK,
            'message' => '재생 명령을 전송했습니다.',
            'terminalKey' => $payload['terminalKey'],
        ]);
    }

    public function terminalStatus(Request $request)
    {
        $input = $request->validate([
            'terminal_key' => ['required', 'regex:/^terminal-[1-9]$/'],
            'status' => ['required', 'in:online,heartbeat,playing,ended,idle,error'],
            'video_title' => ['nullable', 'string', 'max:250'],
            'customer_name' => ['nullable', 'string', 'max:50'],
            'message' => ['nullable', 'string', 'max:250'],
        ]);

        $terminalNo = (int) str_replace('terminal-', '', $input['terminal_key']);
        $payload = [
            'eventType' => 'terminalStatus',
            'terminalKey' => $input['terminal_key'],
            'terminalNo' => $terminalNo,
            'status' => $input['status'],
            'videoTitle' => $input['video_title'] ?? null,
            'customerName' => $input['customer_name'] ?? null,
            'message' => $input['message'] ?? null,
            'updatedAt' => now()->format('Y-m-d H:i:s'),
            'channel' => 'videos',
        ];

        Redis::publish('videos', json_encode($payload));

        return response()->json([
            'statusCode' => Response::HTTP_OK,
        ]);
    }

    private function terminals(): array
    {
        $terminals = [];

        for ($i = 1; $i <= 9; $i++) {
            $terminals[] = [
                'no' => $i,
                'key' => 'terminal-' . $i,
                'name' => 'TV ' . $i,
                'pc_url' => route('pc', ['terminal' => $i]),
                'mobile_url' => route('index', ['terminal' => $i]),
            ];
        }

        return $terminals;
    }

    private function videoPayload(Video $video): array
    {
        return [
            'id' => $video->id,
            'title' => $video->title,
            'playtime_seconds' => $video->playtime_seconds,
            'name' => $video->name,
            'size' => $video->size,
            'playtime_string' => $video->playtime_string,
            'original_name' => $video->original_name,
            'deceased' => $video->deceased,
            'birth' => $video->birth,
            'video_tel' => $video->video_tel,
            'death' => $video->death,
            'video_url' => $video->video_url,
            'thumbnail_url' => $video->thumbnail_url,
            'created_at' => $video->created_at,
        ];
    }

    private function customerPayload(?Customer $customer): array
    {
        if (!$customer) {
            return [
                'id' => null,
                'name' => '수동 재생',
                'tel' => null,
            ];
        }

        return [
            'id' => $customer->id,
            'name' => $customer->name,
            'tel' => $customer->tel,
        ];
    }
}
