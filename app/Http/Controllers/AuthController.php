<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use RealRashid\SweetAlert\Facades\Alert;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function index(Request $request)
    {


//        alert('Title','Lorem Lorem Lorem', 'success');
//        alert()->success('Title','Lorem Lorem Lorem');
//        alert()->info('Title','Lorem Lorem Lorem');
//        alert()->warning('Title','Lorem Lorem Lorem');
//        alert()->error('Title','Lorem Lorem Lorem');
//        alert()->image('Image Title!','Image Description','Image URL','Image Width','Image Height');
//        alert()->question('Title','Lorem Lorem Lorem');
//        alert()->html('<i>HTML</i> <u>example</u>'," You can use <b>bold text</b>, <a href='//github.com'>links</a> and other HTML tags ",'success');
//        toast('Your Post as been submited!','success');
        if(Auth::check()) {
//            echo '<pre>';
//            echo Auth::user();
            return redirect()->intended('dashboard');
//            return view('mvs.dashboard',['route' =>Route::currentRouteName()]);
        }
        else {
            return view('auth.login');
        }
    }

    public function login(Request $request)
    {
//        dump($request->all());

        $credentials = $request->validate([
            'reg_id' => ['required'],
            'password' => ['required'],
        ]);

//        dump($credentials);
//        return;

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('dashboard');
        }

        return back()->withErrors(['message'=>'아이디 또는 비밀번호가 일치하지 않습니다.'])->withInput();
    }

    public function create()
    {
        return view('auth.register');
    }

    public function find(Request $request)
    {
        try {
            Log::channel('auth')->info($request);


            $table = $request->table;
            if($request->select) {
                $select = $request->select;
            }
            else {
                $select = '*';
            }

            $value = $request->value;
            Log::channel('auth')->info($value);

            $result = DB::table($table)->select($select)->where($value)->first();

            if(!$result) {
                throw new Exception('일치하는 정보가 없습니다', Response::HTTP_UNAUTHORIZED);
            }

            $rs['statusCode'] = Response::HTTP_OK;
            $rs['items'] = $result;

        } catch (\Exception $e) {
            $rs['statusCode'] = $e->getCode();
            $rs['statusMessage'] = $e->getMessage();
        }
        return response()->json($rs);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
