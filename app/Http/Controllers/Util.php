<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class Util extends Controller
{
    static public function alert($value)
    {
        Alert::success('회원가입', '계정이 생성되었습니다');
        switch ($value) {
            case 'register':
                Alert::success('회원가입', '계정이 생성되었습니다');
                break;
        }
    }
}
