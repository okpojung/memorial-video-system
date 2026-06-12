<?php

namespace App\Http\Controllers;

use App\Events\VideoProcessed;
use App\Models\Customer;
use App\Models\Video;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;

class MvsController extends Controller
{
    public $paginate = 15;
    public $videoCount;
    public $customerCount;
    public $nav;

    /**
     * Display a listing of the resource.
     *
     */
    public function __construct()
    {


        $this->videoCount = MvsAuth::getVideoCount();
        $this->customerCount = MvsAuth::getCustomerCount();
        $this->nav['videoCount'] = $this->videoCount;
        $this->nav['customerCount'] = $this->customerCount;
    }

    function pingDomain($domain)
    {
        $starttime = microtime(true);
        $file      = fsockopen ($domain, 80, $errno, $errstr, 10);
        $stoptime  = microtime(true);
        $status    = 0;

        if(!$file)
        {
            $status = -1;
        }
        else
        {
            fclose($file);
            $status = ($stoptime - $starttime) * 1000;
            $status = floor($status);
        }
        return $status . ' ms';
    }

    public function socketCheck() {
        $url = env('SOCKET_HOST');
        $ch = curl_init();                                 //CURL 세션 초기화
        curl_setopt($ch, CURLOPT_URL, $url);               //URL 지정
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);       //connection timeout 3초
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    //요청 결과를 문자열로 반환
        $response = curl_exec($ch);
        curl_close($ch);
        return strlen($response);
    }

    public function index(Request $request)
    {
        if(!(boolean) MvsAuth::flag()) {
            echo '서비스가 중지되었습니다';
            exit();
        }
        $socket = $this->socketCheck() !== 0 ? true : false;
        $terminalNo = (int) $request->query('terminal', 0);
        $terminalKey = $terminalNo >= 1 && $terminalNo <= 9 ? 'terminal-' . $terminalNo : null;

        return view('mvs.index', [
            'socket' => $socket,
            'terminalNo' => $terminalNo,
            'terminalKey' => $terminalKey,
            'socketHost' => rtrim((string) env('SOCKET_HOST'), '/'),
        ]);
    }

    public function pc(Request $request)
    {
        if(!(boolean) MvsAuth::flag()) {
            echo '서비스가 중지되었습니다';
            exit();
        }
        $socket = $this->socketCheck() !== 0 ? true : false;
        $terminalNo = (int) $request->query('terminal', 0);
        $terminalKey = $terminalNo >= 1 && $terminalNo <= 9 ? 'terminal-' . $terminalNo : null;

        return view('mvs.pc', [
            'socket' => $socket,
            'terminalNo' => $terminalNo,
            'terminalKey' => $terminalKey,
            'socketHost' => rtrim((string) env('SOCKET_HOST'), '/'),
        ]);
    }

    public function dashboard()
    {
        $from = date('Y-m') . "-01";
        $to = Carbon::now();
        $videoCnt = Video::whereBetween('created_at', [$from, $to])->count();
        $customerCnt = Customer::whereBetween('created_at', [$from, $to])->count();
        $setAmount = number_format($videoCnt * 75000);

        $videos = Video::whereBetween('created_at', [$from, $to])->orderBy('created_at','desc')->limit(5)->get();
        $customers = Customer::whereBetween('created_at', [$from, $to])->orderBy('created_at','desc')->limit(5)->get();

        $nav = $this->nav;
        $nav['profile'] = true;
        $user = Auth::user();
        return view('mvs.dashboard', ['nav' => $nav, 'route' => Route::currentRouteName(), 'user' => $user,
            'videoCnt' => $videoCnt,
            'customerCnt' => $customerCnt,
            'setAmount' => $setAmount,
            'videos' => $videos,
'customers' => $customers]);
    }

    public function receive(Request $request)
    {
        try {
            $tel = $request->tel;
            $play = $request->play;

            if(!$tel) {
                throw new Exception('전화번호 누락', Response::HTTP_UNAUTHORIZED);
            }

            $customer = Customer::where('tel','=',$tel)->first();

            if(empty($customer->id)) {
                $customer = Customer::where('tel','=','01000000000')->first();
//                throw new Exception('등록되지 않은 번호입니다', Response::HTTP_UNAUTHORIZED);
            }


            $customerId = $customer->id;
            $videos = DB::table('customer_video')
                ->join('videos', 'customer_video.video_id', '=', 'videos.id')
                ->select('customer_video.id','videos.title','videos.playtime_seconds', 'videos.name', 'videos.size','videos.playtime_string','videos.playtime_string','videos.original_name', 'videos.deceased', 'videos.birth', 'videos.video_tel', 'videos.death', 'videos.video_url', 'videos.thumbnail_url', 'videos.created_at')
                ->where('customer_id','=',$customerId)
                ->get();

//            echo '<pre>';
//            print_r($videos);
//            return;
            if(count($videos) == 0) {
                throw new Exception('등록된 영상이 없습니다.', Response::HTTP_UNAUTHORIZED);
            }

            if($play && count($videos) > 1) {
//                print_r($videos[$play-1]);
                $videoIndex = $play-1;
//                print_r($videos[$play-1]);
//                foreach($videos as $key => $video) {
//                    if((int) $videoIndex !== (int) $key) {
//                        unset($videos[$key]);
//                    }
//                }
                $tmp = $videos[$videoIndex];
                $videos = [];
                $videos[0] = $tmp;
            }

            $rs['videos'] = json_encode($videos);
            $rs['statusCode'] = Response::HTTP_OK;
            $rs['customer'] = json_encode($customer);

        } catch (\Exception $e) {
            $rs['statusCode'] = $e->getCode();
            $rs['statusMessage'] = $e->getMessage();
        }
        $rs['channel'] = 'videos';
        Redis::publish('videos', json_encode($rs));



//        $video = Video::find(9);
//        VideoProcessed::dispatch($video);

//        Redis::set('name', 'Taylor');
//        Redis::publish('videos', json_encode($customers));

//        VideoProcessed::dispatch($order);
    }
    public function retrieve(Request $request)
    {
        try {
            $tel = $request->tel;

            if(!$tel) {
                throw new Exception('전화번호 누락', Response::HTTP_UNAUTHORIZED);
            }

            $customer = Customer::where('tel','=',$tel)->first();

            if(empty($customer->id)) {
//                $customer = Customer::where('tel','=','01000000000')->first();
                throw new Exception('등록되지 않은 번호입니다', Response::HTTP_UNAUTHORIZED);
            }

            $customerId = $customer->id;
            $videos = DB::table('customer_video')
                ->join('videos', 'customer_video.video_id', '=', 'videos.id')
                ->select('customer_video.id','videos.title','videos.playtime_seconds', 'videos.name', 'videos.size','videos.playtime_string','videos.playtime_string','videos.original_name', 'videos.deceased', 'videos.birth', 'videos.video_tel', 'videos.death', 'videos.video_url', 'videos.thumbnail_url', 'videos.created_at')
                ->where('customer_id','=',$customerId)
                ->get();

            if(count($videos) == 0) {
                throw new Exception('등록된 영상이 없습니다.', Response::HTTP_UNAUTHORIZED);
            }

            $rs['statusCode'] = Response::HTTP_OK;
            $rs['videos'] = $videos;
            $rs['customer'] = $customer;

        } catch (\Exception $e) {
            $rs['statusCode'] = $e->getCode();
            $rs['statusMessage'] = $e->getMessage();
        }

        return response()->json($rs);
    }

    public function phpInfo()
    {
        echo phpinfo();
    }
}
