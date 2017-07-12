<?php

namespace App\Http\Controllers;

use App\mymodel;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Cache;

class UserController extends Controller
{
        //在线用户
    public function sessionId(Request $request){
        $callback=$_GET['callback'];
        /*$id=Cache::get('id');*/
        $id=3;
/*        $data= DB::table('cms_username')->where('uid',$id)->first();*/
        if($id){
            $datas=json_encode($id);
        }else{
            $datas=json_encode(array('status'=>1));
        }
        return $callback.'('.$datas.')';
    }
    //在线用户信息
    public function userInfo(Request $request){
        $callback=$_GET['callback'];
        /*$id=Cache::get('id');*/
        $id=3;
        $data= DB::table('cms_username')->where('uid',$id)->first();
        $datas=json_encode($id);
        return $callback.'('.$datas.')';
    }


}

?>