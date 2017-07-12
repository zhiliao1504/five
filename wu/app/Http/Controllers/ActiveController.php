<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Http\Requests;
use DB;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use App\PaModel;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use Cache;

class ActiveController extends Controller
{
    //
    public function index(){
        //echo phpinfo();die;
        $jsoncallback=isset($_GET['jsoncallback'])?$_GET['jsoncallback']:"";
        $action=isset($_GET['action'])?$_GET['action']:"";
        $sign=isset($_GET['sign'])?$_GET['sign']:"";
        $time=isset($_GET['time'])?$_GET['time']:"";
        $num=isset($_GET['num'])?$_GET['num']:"";
        $id=isset($_GET['id'])?$_GET['id']:"";
        $now=time();

        if(empty($jsoncallback)||empty($action)||empty($sign)||empty($time)){
            echo $jsoncallback.'('.json_encode(array('status'=>0,'mess'=>'参数不能为空')).')';die;
        }
        //验证sign
        if($sign!=md5($action.$time)){
            echo $jsoncallback.'('.json_encode(array('status'=>2,'mess'=>'sign错误')).')';die;
        }

        //验证时间是否超时
        if($now-$time>11110){
            echo $jsoncallback.'('.json_encode(array('status'=>3,'mess'=>'请求超时')).')';die;
        }
        //action操作
        if($action=='select'){
            $data=DB::table('cms_active')->orderBy('act_etime','desc')->get();
            if($data){
                echo $jsoncallback.'('.json_encode(array('status'=>1,'data'=>$data)).')';die;
            }else{
                echo $jsoncallback.'('.json_encode(array('status'=>4,'mess'=>'数据库繁忙')).')';die;
            }
        }
        if($action=='find'){
            if(empty($id)){
                echo $jsoncallback.'('.json_encode(array('status'=>0,'mess'=>'参数不能为空')).')';die;
            }
            $data=DB::table('cms_active')->where(['act_id'=>$id])->get();
            if($data){
                $str=$data[0];
                echo $jsoncallback.'('.json_encode(array('status'=>1,'data'=>$str)).')';die;
            }else{
                echo $jsoncallback.'('.json_encode(array('status'=>4,'mess'=>'数据库繁忙')).')';die;
            }
        }
        if($action=='upd'){
            if(empty($id)){
                echo $jsoncallback.'('.json_encode(array('status'=>0,'mess'=>'参数不能为空')).')';die;
            }
            $data=DB::table('cms_active')->where(['act_id'=>$id])->get();
            $nu=$data[0]['act_like']+1;
            $res=DB::table('cms_active')->where(['act_id'=>$id])->update(['act_like'=>$nu]);
            if($res){
                echo $jsoncallback.'('.json_encode(array('status'=>1,'mess'=>'点赞成功','data'=>$nu)).')';die;
            }else{
                echo $jsoncallback.'('.json_encode(array('status'=>4,'mess'=>'点赞失败，数据库繁忙')).')';die;
            }
        }
        if($action=='like'){
            if(empty($id)){
                echo $jsoncallback.'('.json_encode(array('status'=>0,'mess'=>'参数不能为空')).')';die;
            }
            $data=DB::table('cms_pingaction')->where(['p_id'=>$id])->get();
            $nu=$data[0]['like_num']+1;
            $res=DB::table('cms_pingaction')->where(['p_id'=>$id])->update(['like_num'=>$nu]);
            if($res){
                echo $jsoncallback.'('.json_encode(array('status'=>1,'mess'=>'点赞成功','data'=>$nu)).')';die;
            }else{
                echo $jsoncallback.'('.json_encode(array('status'=>4,'mess'=>'点赞失败，数据库繁忙')).')';die;
            }
        }
        if($action=='li'){
            if(empty($id)){
                echo $jsoncallback.'('.json_encode(array('status'=>0,'mess'=>'参数不能为空')).')';die;
            }
            $data=DB::table('cms_videospeak')->where(['speak_id'=>$id])->get();
            $nu=$data[0]['speak_bang']+1;
            $res=DB::table('cms_videospeak')->where(['speak_id'=>$id])->update(['speak_bang'=>$nu]);
            if($res){
                echo $jsoncallback.'('.json_encode(array('status'=>1,'mess'=>'点赞成功','data'=>$nu)).')';die;
            }else{
                echo $jsoncallback.'('.json_encode(array('status'=>4,'mess'=>'点赞失败，数据库繁忙')).')';die;
            }
        }
        if($action=='ping'){
            if(empty($id)){
                echo $jsoncallback.'('.json_encode(array('status'=>0,'mess'=>'参数不能为空')).')';die;
            }
            $users = DB::table('cms_pingaction')
                ->leftJoin('cms_username', 'cms_pingaction.uid', '=', 'cms_username.uid')
                ->where(['act_id'=>$id])
                ->orderBy('like_num','desc')
                ->limit(3)
                ->get();
            if($users){
                echo $jsoncallback.'('.json_encode(array('status'=>1,'data'=>$users)).')';die;
            }else{
                echo $jsoncallback.'('.json_encode(array('status'=>4,'mess'=>'数据库繁忙')).')';die;
            }
        }
        if($action=='new'){
            if(empty($id)){
                echo $jsoncallback.'('.json_encode(array('status'=>0,'mess'=>'参数不能为空')).')';die;
            }
            $users = DB::table('cms_pingaction')
                ->leftJoin('cms_username', 'cms_pingaction.uid', '=', 'cms_username.uid')
                ->where(['act_id'=>$id])
                ->orderBy('add_time','desc')
                ->limit(3)
                ->get();
            if($users){
                echo $jsoncallback.'('.json_encode(array('status'=>1,'data'=>$users)).')';die;
            }else{
                echo $jsoncallback.'('.json_encode(array('status'=>4,'mess'=>'数据库繁忙')).')';die;
            }
        }
        if($action=='old'){
            if(empty($id)){
                echo $jsoncallback.'('.json_encode(array('status'=>0,'mess'=>'参数不能为空')).')';die;
            }
            $users = DB::table('cms_pingaction')
                ->leftJoin('cms_username', 'cms_pingaction.uid', '=', 'cms_username.uid')
                ->where(['act_id'=>$id])
                ->orderBy('add_time','asc')
                ->limit(3)
                ->get();
            if($users){
                echo $jsoncallback.'('.json_encode(array('status'=>1,'data'=>$users)).')';die;
            }else{
                echo $jsoncallback.'('.json_encode(array('status'=>4,'mess'=>'数据库繁忙')).')';die;
            }
        }

        echo $jsoncallback.'('.json_encode(array('status'=>5,'mess'=>'方式请求错误')).')';die;

    }
    public function ping(Request $request){
        $callback=$_GET['callback'];
        $id=$_GET['id'];
        $text=$_GET['text'];

     $uid=/*Cache::get('id');*/3;

//echo $uid;die;
        $add=DB::table('cms_pingaction')->insertGetId(
            [   'act_id'=>$id,
                'uid'=>$uid,
                'content'=>$text,
                'add_time'=>date('Y-m-d H:i:s',time())
            ]
        );

        if($add){
            $result = DB::table('cms_pingaction')
                ->leftJoin('cms_username', 'cms_pingaction.uid', '=', 'cms_username.uid')
                ->where(['act_id'=>$id])
                ->orderBy('add_time','desc')
                ->limit(3)
                ->get();
            $results=json_encode($result);
            return $callback.'('.$results.')';

        }

    }
    public function ip(Request $request){

        $url = "http://api.k780.com:88/?app=ip.get&appkey=24127&sign=0e59d73d12d755d4ffbc79a5895dd6c3&format=json";

        $aa = file_get_contents($url);
        $city = json_decode($aa,true);
        $city = explode(',',$city['result']['att']);
        $city = array_pop($city).'市';

        $callback=$_GET['callback'];
        $data = DB::table('cms_advert')
            ->where(['city'=>$city])
            ->orderBy('aid','desc')
            ->limit(3)
            ->get();
        $results=json_encode($data);
        return $callback.'('.$results.')';
    }

}
