<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Video;
use Carbon\Carbon;
use Cassandra\Custom;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class CustomerController extends Controller
{
    public $paginate = 15;
    public $videoCount;
    public $customerCount;
    public $nav;
    /**
     * Display a listing of the resource.
     *
     */
    public function __construct ()
    {
//        if(!(boolean) MvsAuth::flag()) {
//            echo '서비스가 중지되었습니다';
//            exit();
//        }

        $this->videoCount = MvsAuth::getVideoCount();
        $this->customerCount = MvsAuth::getCustomerCount();
        $this->nav['videoCount'] = $this->videoCount;
        $this->nav['customerCount'] = $this->customerCount;
    }

    public function index(Request $request)
    {
        $sfl = $request->sfl;
        $stx = $request->stx;
        $nav = $this->nav;
        $nav['breadcrumbs'][0]['title'] = '고객';
        $nav['breadcrumbs'][0]['path'] = '/customers';
        $user = Auth::user();
        if($stx) {
            $customers = Customer::where($sfl, 'LIKE', '%'.$stx.'%')->orderBy('created_at','desc')->paginate($this->paginate);
        }
        else {
            $customers = Customer::orderBy('created_at','desc')->paginate($this->paginate);
        }

        $customers->appends(['sfl' => $sfl, 'stx'=> $stx]);
        $count = $customers->total() - ($customers->perPage() * ($customers->currentPage()-1));
        return view('customers.index', ['route' => 'customers','customers' => $customers,'count'=>$count,'user' => $user, 'nav' => $nav,
        'sfl' =>$sfl,
        'stx' =>$stx,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        $user = Auth::user();
        $nav = $this->nav;
        $nav['breadcrumbs'][0]['title'] = '고객';
        $nav['breadcrumbs'][0]['path'] = '/customers';
        $nav['breadcrumbs'][1]['title'] = '고객 추가';
        $nav['breadcrumbs'][1]['path'] = '/customers/create';
        return view('customers.create', ['route' => 'customers','user' => $user, 'nav' => $nav]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {

        $input = $request->validate([
            'name'              => ['required'],
            'tel'               => ['required','unique:customers'],
            'email'             => ['nullable'],
            'sido'              => ['nullable'],
            'sigungu'           => ['nullable'],
            'bname'             => ['nullable'],
            'roadname'          => ['nullable'],
            'jibun_address'        => ['nullable'],
            'latitude'          => ['nullable'],
            'longitude'         => ['nullable'],
            'zone_code'         => ['nullable'],
            'road_address'         => ['nullable'],
            'detail_address'    => ['nullable'],
        ]);
        Customer::create($input);

        Alert::success('고객', '고객이 등록되었습니다');
        return  redirect('/customers', Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     */
    public function edit($id)
    {
        $user = Auth::user();

        $customer = Customer::where('id', '=', $id)->first();
        $nav = $this->nav;
        $nav['breadcrumbs'][0]['title'] = '고객';
        $nav['breadcrumbs'][0]['path'] = '/customers';
        $nav['breadcrumbs'][1]['title'] = '고객 수정';
        $nav['breadcrumbs'][1]['path'] = "/customers/{$id}/edit";
        return view('customers.edit', ['route' => 'customers','user' => $user, 'customer' => $customer, 'nav' => $nav]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     */
    public function update(Request $request, $id)
    {
        $input = $request->validate([
            'name'              => ['required'],
            'email'             => ['nullable'],
            'sido'              => ['nullable'],
            'sigungu'           => ['nullable'],
            'bname'             => ['nullable'],
            'roadname'          => ['nullable'],
            'jibun_address'        => ['nullable'],
            'latitude'          => ['nullable'],
            'longitude'         => ['nullable'],
            'zone_code'         => ['nullable'],
            'road_address'         => ['nullable'],
            'detail_address'    => ['nullable'],
        ]);
        $customer = Customer::where('id', '=', $id);
        $customer->update($input);
        toast('수정되었습니다','success');
        return redirect('/customers');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy($id)
    {
        $customer = Customer::where('id', '=', $id);
        $customer->delete();
        toast('고객이 삭제되었습니다','success');
        return redirect('/customers');
    }

    public function video(Request $request, $id)
    {
        $sfl = $request->sfl;
        $stx = $request->stx;
        $nav = $this->nav;
        $nav['breadcrumbs'][0]['title'] = '영상';
        $nav['breadcrumbs'][0]['path'] = '/videos';
        $user = Auth::user();

        $customerVideos = DB::table('customer_video')
            ->join('videos', 'customer_video.video_id', '=', 'videos.id')
            ->select('customer_video.id','videos.title', 'videos.name', 'videos.size','videos.playtime_string','videos.original_name', 'videos.deceased', 'videos.birth', 'videos.video_tel', 'videos.death', 'videos.video_url', 'videos.thumbnail_url', 'videos.created_at')
            ->where('customer_id','=',$id)
            ->get();

        if ($stx) {
            $videos = Video::where($sfl, 'LIKE', '%'.$stx.'%')->orderBy('created_at','desc')->paginate($this->paginate);
        } else {
            $videos = Video::orderBy('created_at','desc')->paginate($this->paginate);
        }

        $videos->appends(['sfl' => $sfl, 'stx' => $stx]);
        $count = $videos->total() - ($videos->perPage() * ($videos->currentPage() - 1));
        return view(
            'customers.video',
            [
                'route' => 'videos',
                'videos' => $videos,
                'customerVideos' => $customerVideos,
                'count' => $count,
                'user' => $user,
                'id' => $id,
                'nav' => $nav,
                'sfl' => $sfl,
                'stx' => $stx,
            ]
        );
    }

    public function videoStore(Request $request)
    {

        $count = DB::table('customer_video')->where(['customer_id' => $request->customer_id,'video_id' => $request->video_id])->count();

        if($count == 1) {
            toast('이미 등록된 영상입니다','error');
            return back();
        }

        DB::table('customer_video')->insert(
            [
                'customer_id' => $request->customer_id
                , 'video_id' => $request->video_id
                , 'created_at' => Carbon::now()
            ]
        );
        toast('영상을 선택했습니다','success');
        return back();
    }

    public function videoDestroy($id)
    {
        $customerVideo = DB::table('customer_video')->where('id', '=', $id);
        $customerVideo->delete();
        toast('영상선택을 해제하였습니다','success');
        return back();
    }

    public function view(Request $request, $id)
    {
        $user = Auth::user();

        $customer = Customer::where('id', '=', $id)->first();

        $sfl = $request->sfl;
        $stx = $request->stx;
        $nav = $this->nav;
        $nav['breadcrumbs'][0]['title'] = '고객';
        $nav['breadcrumbs'][0]['path'] = '/customers';
        $nav['breadcrumbs'][1]['title'] = "{$customer->name}";
        $nav['breadcrumbs'][1]['path'] = "/customers/{$id}/view";

        $customerVideos = DB::table('customer_video')
            ->join('videos', 'customer_video.video_id', '=', 'videos.id')
            ->select('customer_video.id','videos.title', 'videos.name', 'videos.size','videos.playtime_string','videos.original_name', 'videos.deceased', 'videos.birth', 'videos.video_tel', 'videos.death', 'videos.video_url', 'videos.thumbnail_url', 'videos.created_at')
            ->where('customer_id','=',$id)
            ->get();

        return view(
            'customers.view',
            [
                'route' => 'videos',
                'customerVideos' => $customerVideos,
                'user' => $user,
                'id' => $id,
                'customer' => $customer,
                'nav' => $nav,
                'sfl' => $sfl,
                'stx' => $stx,
            ]
        );
    }

    public function flag()
    {
        $flag = DB::connection('mysql_management')->table('flag')->select('flag')->where('title','=',"mvs")->get()->first();

        return $flag;
    }
}
