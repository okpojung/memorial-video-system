<?php

    namespace App\Http\Controllers;

    use App\Http\Controllers\Controller;
    use App\Models\Customer;
    use App\Models\Video;
    use Carbon\Carbon;
    use FFMpeg\FFMpeg;
    use getID3;
    use Illuminate\Http\Request;
    use Illuminate\Http\Response;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\File;
    use Illuminate\Support\Facades\Storage;
    use Pawlox\VideoThumbnail\Facade\VideoThumbnail;
    use RealRashid\SweetAlert\Facades\Alert;

    use function Webmozart\Assert\Tests\StaticAnalysis\boolean;

    class VideoController extends Controller
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
//            if(!(boolean) MvsAuth::flag()) {
//                echo '서비스가 중지되었습니다';
//                exit();
//            }

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
            $nav['breadcrumbs'][0]['title'] = '영상';
            $nav['breadcrumbs'][0]['path'] = '/videos';
            $user = Auth::user();
            if ($stx) {
                $videos = Video::where($sfl, 'LIKE', '%'.$stx.'%')->orderBy('created_at','desc')->paginate($this->paginate);
            } else {
                $videos = Video::orderBy('created_at','desc')->paginate($this->paginate);
            }

            $videos->appends(['sfl' => $sfl, 'stx' => $stx]);
            $count = $videos->total() - ($videos->perPage() * ($videos->currentPage() - 1));
            return view(
                'videos.index',
                [
                    'route' => 'videos',
                    'videos' => $videos,
                    'count' => $count,
                    'user' => $user,
                    'nav' => $nav,
                    'sfl' => $sfl,
                    'stx' => $stx,
                ]
            );
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
            return view('videos.create', ['route' => 'videos', 'user' => $user, 'nav' => $nav]);
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param  \Illuminate\Http\Request  $request
         */
        public function store(Request $request)
        {
            /* INPUT TEXT DATA*/
            $title = $request->title;
            $deceased = $request->deceased;
            $birth = $request->birth;
            $video_tel = $request->video_tel;
            $death = $request->death;

            if ($request->repeat == true) {
                $repeat = true;
            } else {
                $repeat = false;
            }

            if (!$request->video) {
                toast('영상을 업로드해주시기 바랍니다.', 'error');
                return back()->withInput();
            }

            /* VIDEO FILE DATA*/
            $video = $request->video;
            $originName = $video->getClientOriginalName();
            $originName = preg_replace("/\s+/", "_", $originName);
            $time = date('YmdHis');
            $size = $video->getSize();
            $format = $video->extension();
            $videoFirstName = explode('.', $originName)[0];
            $videoPath = 'mvs/';
            $thumbnailPath = 'thumbnail/';
            $name = "{$time}_{$originName}";
            $thumbnailName = "{$time}_{$videoFirstName}_thumbnail.jpg";
            $video->move(public_path($videoPath), $name);
            $videoUrl = $videoPath.$name;
            $thumbnailUrl = $thumbnailPath.$thumbnailName;

            $getID3 = new getID3;

            $video_file = $getID3->analyze($videoUrl);

            $resolutionW = $video_file['video']['resolution_x'];
            $resolutionH = $video_file['video']['resolution_y'];

            if ($resolutionW >= $resolutionH) {
                $mode = 1;
            } else {
                $mode = 2;
            }

            $playtimeSeconds = $video_file['playtime_seconds'];
            $playtimeString = $video_file['playtime_string'];



            VideoThumbnail::createThumbnail(
                (string)public_path($videoUrl),
                (string)public_path($thumbnailPath),
                (string)$thumbnailName,
                (int)2
//                (int)$width = 640,
//                (int)$height = 480
            );

            Video::create(
                [
                    'title' => $title,
                    'name' => $name,
                    'format' => $format,
                    'resolution_w' => $resolutionW,
                    'resolution_h' => $resolutionH,
                    'playtime_string' => $playtimeString,
                    'playtime_seconds' => $playtimeSeconds,
                    'mode' => $mode,
                    'repeat' => $repeat,
                    'original_name' => $originName,
                    'deceased' => $deceased,
                    'birth' => $birth,
                    'video_tel' => $video_tel,
                    'death' => $death,
                    'video_url' => $videoUrl,
                    'thumbnail_url' => $thumbnailUrl,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'size' => $size,
                ]
            );


            DB::connection('mysql_management')->table('mvs_video')->insert(
                [
                    'temple' => env('APP_PLACE'),
                    'title' => $title,
                    'name' => $name,
                    'format' => $format,
                    'size' => $size,
                    'playtime_seconds' => $playtimeSeconds,
                    'playtime_string' => $playtimeString,
                    'original_name' => $originName,
                    'deceased' => $deceased,
                    'birth' => $birth,
                    'video_tel' => $video_tel,
                    'death' => $death,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            );

            toast('영상이 등록되었습니다.', 'success');
            return redirect('/videos', Response::HTTP_CREATED);
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

            $video = Video::where('id', '=', $id)->first();
            $nav = $this->nav;
            $nav['breadcrumbs'][0]['title'] = '영상';
            $nav['breadcrumbs'][0]['path'] = '/videos';
            $nav['breadcrumbs'][1]['title'] = '영상 수정';
            $nav['breadcrumbs'][1]['path'] = "/videos/{$id}/edit";
            return view(
                'videos.edit',
                ['route' => 'videos', 'user' => $user, 'video' => $video, 'nav' => $nav]
            );
        }

        /**
         * Update the specified resource in storage.
         *
         * @param  \Illuminate\Http\Request  $request
         * @param  int  $id
         */
        public function update(Request $request, $id)
        {
            $input = $request->validate(
                [
                    'repeat' => ['nullable'],
                    'title' => ['required'],
                    'video_tel' => ['nullable'],
                    'deceased' => ['nullable'],
                    'birth' => ['nullable'],
                    'death' => ['nullable'],
                ]
            );
            $input['updated_at'] = Carbon::now();
            $video = Video::where('id', '=', $id);
            $video->update($input);
            toast('수정되었습니다', 'success');
            return redirect('/videos');
        }

        /**
         * Remove the specified resource from storage.
         *
         * @param  int  $id
         */
        public function destroy($id)
        {
            $video = Video::where('id', '=', $id);
            $videoInfo = $video->first();
            $videoInfo->video_url;
            $videoUrl = public_path($videoInfo->video_url);
            $thumbnailUrl = public_path($videoInfo->thumbnail_url);

            if (File::exists($videoUrl)) {
                File::delete($videoUrl);
            }

            if (File::exists($thumbnailUrl)) {
                File::delete($thumbnailUrl);
            }

            $video->delete();
            toast('영상이 삭제되었습니다', 'success');
            return redirect('/videos');
        }

        public function destroyTest(Request $request)
        {
            $url = $request->url;

            $videoUrl = public_path($url);
            print_r(File::exists($videoUrl));

//            print_r(unlink($videoUrl));

//            if (File::exists($videoUrl)) {
//                File::delete($videoUrl);
//            }
        }
    }
