<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Http\Requests;
use DB;
use Cache;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use App\PaModel;


class ShowController extends Controller
{
    /*课程详情展示*/
    public function desc()
    {
        /*星号表示所有的域都可以接受，*/
        header("Access-Control-Allow-Origin:*");
        header("Access-Control-Allow-Methods:GET,POST");
        $id=isset($_GET['id'])?Input::get('id'):'';
        $action=isset($_GET['action'])?Input::get('action'):'';
        $callback=isset($_GET['callback'])?Input::get('callback'):'';
        if(empty($callback)){
            return json_encode(array('msg'=>0,'error'=>'NOT FROUND callback'));die;
        }
        if(empty($id)){
            return json_encode(array('msg'=>0,'error'=>'NOT FROUND id'));die;
        }
        if(empty($action)){
            return json_encode(array('msg'=>0,'error'=>'NOT FROUND action'));die;
        }
        /*课程详情*/
        if($action=='show'){
            $model=new PaModel();
            $arr=$model->getWhere('class_title',['title_id'=>$id]);
            $array=$model->getWhere('teacher',['tid'=>$id]);
            return $callback.'('.json_encode(array_merge($arr,$array)).')';
        }
        /*课程目录*/
        if($action=='mu'){
            $model=new PaModel();
            $arr=$model->getWhere('class_title',['title_id'=>$id]);
            $array['str']=$model->getWhere('class_jie',['title_id'=>$id],1);
            return $callback.'('.json_encode(array_merge($arr,$array)).')';
        }
        /*章节展示*/
        if($action=='jie'){
            $model=new PaModel();
            $arr=$model->getWhere('class_jie',['jie_id'=>$id]);
            return $callback.'('.json_encode($arr).')';
        }
    }
}
