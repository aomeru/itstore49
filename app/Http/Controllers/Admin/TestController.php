<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Carbon;
use Storage;

class TestController extends Controller
{
    public function index()
    {
        Storage::makeDirectory('public/purchase');
        Storage::makeDirectory('public/approval');
        //$mytime = Carbon\Carbon::now();
        //echo $mytime->toDateTimeString();
        
        //dd(time());
        //dd(date('d-m-y, g:ia', time()));

        // $users = User::all();
        // foreach ($users as $u) {
        //     if($u->email != null)
        //     {
        //         //$e = explode('@',$u->email);
        //         $u->username = strtolower($u->username);
        //         $u->update();
        //     }
        // }
    }

    public function folder()
    {
        
    }
}
