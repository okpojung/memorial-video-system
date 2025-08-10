<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Psy\Util\Json;
use RealRashid\SweetAlert\Facades\Alert;
use Symfony\Component\HttpFoundation\Response;


class UserController extends Controller
{

    public function index(Request $request)
    {
        try {
//            $admins = User::get();
//            $rs['admins']    = $admins;
            $rs['statusCode']         = Response::HTTP_OK;
            $rs['message']          = '관리자들 조회';
        } catch (\Exception $e) {
            $rs['statusCode']         = $e->getCode();
            $rs['message']          = $e->getMessage();
        }

//        return view('index',$rs);
//        return response()->json($rs, Response::HTTP_OK, [], JSON_PRETTY_PRINT);
    }

    public function create()
    {
        return view('auth.register');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
//        try {

//            Log::channel('company')->info($request);

            $input = $request->validate([
                'reg_id'            => ['required', 'unique:users', 'regex:/^[0-9a-zA-Z]+$/', 'min:2', 'max:10'], // 영문자 및 숫자
                'role_id'           => ['nullable'], // 영문자 및 숫자
                'password'          => ['required', 'min:4', 'max:255'],
                'name'              => ['required', 'min:2', 'max:10'],
                'tel'               => ['required', 'min:10', 'max:12'],
                'email'             => ['nullable', 'email', 'max:100'],
            ]);

            $input['password'] = bcrypt($input['password']);
            $input['role_id'] = 2;
//
            User::create($input);

//            $rs['statusCode']     = Response::HTTP_CREATED;
//            $rs['message']      = '관리자 생성';
//        } catch (\Exception $e) {
//            $rs['statusCode']     = $e->getCode();
//            $rs['message']      = $e->getMessage();
//        }
//        return response()->json($rs, Response::HTTP_CREATED, [], JSON_PRETTY_PRINT);
//        alert('생성되었습니다.');
        Alert::success('회원가입', '계정이 생성되었습니다');
//        return  redirect('?alert=register', Response::HTTP_CREATED);
        return  redirect('/', Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $company = User::where(['id' => $id])->get();

            $rs['company']  = $company;
            $rs['statusCode']     = Response::HTTP_OK;
            $rs['message']      = '관리자 조회';
        } catch (\Exception $e) {
            $rs['statusCode']     = $e->getCode();
            $rs['message']      = $e->getMessage();
        }
        return response()->json($rs, Response::HTTP_OK, [], JSON_PRETTY_PRINT);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {

            $auth = AuthController::authCheck();

            if((int) $auth->id !== (int) $id) {
                throw new \Exception("잘못된 인증입니다", Response::HTTP_UNAUTHORIZED);
            }

            $input = $request->validate([
                'name' => ['nullable', 'min:2', 'max:10'],
                'tel' => ['nullable', 'min:10', 'max:12'],
                'email' => ['nullable', 'email', 'max:100'],
                'zone_code' => ['nullable', 'max:5'],
                'sido' => ['nullable', 'max:10'],
                'sigungu' => ['nullable', 'max:10'],
                'bname' => ['nullable', 'max:10'],
                'road_name' => ['nullable', 'max:100'],
                'road_addr' => ['nullable', 'max:100'],
                'jibun_addr' => ['nullable', 'max:100'],
                'detail_addr' => ['nullable', 'max:100'],
                'location_agree' => ['nullable', 'max:1'],
                'sms_agree' => ['nullable', 'max:1'],
                'email_agree' => ['nullable', 'max:1'],
            ]);
            $company = User::where('id', $id)->update($input);

            $rs['company']     = $company;
            $rs['statusCode']     = Response::HTTP_OK;
            $rs['message']      = '관리자 수정';
        } catch (\Exception $e) {
            $rs['statusCode']     = $e->getCode();
            $rs['message']      = $e->getMessage();
        }
        return response()->json($rs, Response::HTTP_OK, [], JSON_PRETTY_PRINT);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $auth = AuthController::authCheck();

            if((int) $auth->id !== (int) $id) {
                throw new \Exception("잘못된 인증입니다", Response::HTTP_UNAUTHORIZED);
            }
            User::where('id', $id)->delete();

            $rs['statusCode']     = Response::HTTP_OK;
            $rs['message']      = '관리자 삭제';
        } catch (\Exception $e) {
            $rs['statusCode']     = $e->getCode();
            $rs['message']      = $e->getMessage();
        }
        return response()->json($rs, Response::HTTP_OK, [], JSON_PRETTY_PRINT);
    }

    public function registerNotificationSend()
    {
//        for($i=1; $i<=10; $i++) {
//            $company = Company::find($i);
//            Log::channel('notification')->info($company);

//            $company->class = 'admin';
//            $company->type = 'register';
//            $company->text = $company->name.'이 신규가입 되었습니다';
//            $company->url = 'http://127.0.0.1:8000/comapany/view/1';
//
//            Log::channel('notification')->info($company);
//            NotificationController::registeredNotification($company);
//        }

        $admins = User::select('id','name')->get();

        foreach ($admins as $admin) {
            for($i=1; $i<=10; $i++) {
                $company = Company::select('name')->find($i);

//                $data = [
//                    'class' => 'admin',
//                    'type' => 'register',
//                    'text' => $company->name.'이 신규가입 되었습니다',
//                    'url' => 'http://127.0.0.1:8000/comapany/view/1'
//                ];

                DB::table('admin_notifications')->insert([
                    'admin_id' => $admin->id,
                    'type' => 'register',
                    'data'=> $company->name.'이 신규가입 되었습니다',
                ]);

            }
        }



    }

    public function changePassword(Request $request)
    {
        try {
            $auth = AuthController::authCheck();
            $table = $request["table"];
            $value = $request["value"];
            $where = $request["where"];

            $password = $value['password'];
            $newPassword = $value['newPassword'];
            $newPasswordConfirm = $value['newPasswordConfirm'];

            if (!Hash::check($password, $auth->password)) {
                throw new \Exception("현재 비밀번호가 일치하지 않습니다", Response::HTTP_UNAUTHORIZED);
            }
            if ($newPassword !== $newPasswordConfirm) {
                throw new \Exception("새비밀번호가 일치하지 않습니다", Response::HTTP_UNAUTHORIZED);
            }


            $admin =  DB::table($table);

            foreach ($where as $key => $val) {
                $admin = $admin->where($key, '=', $val);
            }

            $result = $admin->update(['password' => bcrypt($newPassword)]);

            $rs['statusCode'] = Response::HTTP_OK;
            $rs['data'] = $result;

        } catch (\Exception $e) {
            $rs['statusCode'] = $e->getCode();
            $rs['statusMessage'] = $e->getMessage();
        }
        return response()->json($rs);
    }


}
