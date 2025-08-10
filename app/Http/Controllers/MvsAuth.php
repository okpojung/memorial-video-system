<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MvsAuth extends Controller
{

    static public function getVideoCount() {
        return Video::all()->count();

    }
    static public function getCustomerCount() {
        return Customer::all()->count();

    }
    static public function flag()
    {
        $query = DB::connection('mysql_management')->table('flag')->select('flag')->where('title','=',"mvs")->get()->first();
        return $query->flag;
    }
}
