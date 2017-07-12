<?php

namespace App\Http\Controllers;

use App\mymodel;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Cache;

class TitController extends Controller
{
        //biaoti
    public function title(Request $request){
        $callback=$_GET['callback'];
        /*$id=Cache::get('id');*/
        $kw=$_GET['kw'];
        $data= DB::table('cms_link')->where('link_name',$kw)->first();
        if($data){
            $datas=json_encode($data['link']);
        }else{
            $datas=json_encode(array('status'=>1));
        }
        return $callback.'('.$datas.')';
    }

}

?>