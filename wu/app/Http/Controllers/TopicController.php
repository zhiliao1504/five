<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Http\Requests;
use DB;
use Cache;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use App\PaModel;


class TopicController extends Controller
{
    //首页新闻展示和分类展示
    public function index()
    {
        $jsoncallback=isset($_GET['jsoncallback'])?Input::get('jsoncallback'):'';


        $news=DB::table('cms_discuss')->join('cms_username','cms_discuss.uid','=','cms_username.uid')->get();
        foreach ($news as $k=>$v){
            //if($v['parent']!=0){
            $news[$k]['f_name']=DB::table('cms_discuss')->select('uname')->join('cms_username','cms_discuss.uid','=','cms_username.uid')->where('did',$v['parent'])->get();

            $names = array_column($news[$k]['f_name'], 'uname');
            $news[$k]['f_name']=implode(',',$names);

        }
        $news=$this->getSort($news);
        $arr=['news'=>$news];

        return $jsoncallback.'('.json_encode($arr).')';
    }
    public function pinglun()
    {
        //$uid=isset($_GET['uid'])?$_GET['uid']:'';   //用户ID
        $did = isset($_GET['did']) ? $_GET['did'] : '';
        //$nid = $_GET['id'];
        $text = $_GET['texts'];
        $jsoncallback = isset($_GET['jsoncallback']) ? Input::get('jsoncallback') : '';
        if (empty($jsoncallback)) {
            return json_encode(array('msg' => 0, 'error' => 'NOT FROUND jsoncallback'));
            die;
        }
        if (empty($did)) {
            $add = DB::table('cms_discuss')->insertGetId(
                [   'nid' => 1,
                    'uid' => 1,
                    'content' => $text,
                    'addtime' => date('Y-m-d H:i:s', time())
                ]
            );
            if ($add) {
                $news = DB::table('cms_discuss')->join('cms_username', 'cms_discuss.uid', '=', 'cms_username.uid')->get();
                foreach ($news as $k => $v) {
                    //if($v['parent']!=0){
                    $news[$k]['f_name'] = DB::table('cms_discuss')->select('uname')->join('cms_username', 'cms_discuss.uid', '=', 'cms_username.uid')->where('did', $v['parent'])->get();

                    $names = array_column($news[$k]['f_name'], 'uname');
                    $news[$k]['f_name'] = implode(',', $names);

                }
                $news = $this->getSort($news);
                $arr = ['news' => $news];

                return $jsoncallback . '(' . json_encode($arr) . ')';
            }
        } else {
            $add = DB::table('cms_discuss')->insertGetId(
                ['nid' => 1,
                    'uid' => 1,
                    'parent' => $did,
                    'content' => $text,
                    'addtime' => date('Y-m-d H:i:s', time())
                ]
            );
            if ($add) {
                $news = DB::table('cms_discuss')->join('cms_username', 'cms_discuss.uid', '=', 'cms_username.uid')->get();
                foreach ($news as $k => $v) {
                    //if($v['parent']!=0){
                    $news[$k]['f_name'] = DB::table('cms_discuss')->select('uname')->join('cms_username', 'cms_discuss.uid', '=', 'cms_username.uid')->where('did', $v['parent'])->get();

                    $names = array_column($news[$k]['f_name'], 'uname');
                    $news[$k]['f_name'] = implode(',', $names);

                }
                $news = $this->getSort($news);
                $arr = ['news' => $news];

                return $jsoncallback . '(' . json_encode($arr) . ')';
            }
        }
    }
    //点赞
    public function like()
    {
        $id=isset($_GET['id'])?Input::get('id'):'';
        $jsoncallback=isset($_GET['jsoncallback'])?Input::get('jsoncallback'):'';
        if(empty($jsoncallback)){
            return json_encode(array('msg'=>0,'error'=>'NOT FROUND jsoncallback'));die;
        }
        //echo $id;die;

        $news=DB::table('cms_discuss')->where('did',$id)->get();
        $like=DB::table('cms_discuss')->where('did',$id)->update(['like'=>ceil($news[0]['like']+1)]);
        if($like){
            return $jsoncallback.'(1)';
        }
    }
    //评论递归
    public function getSort($data,$pid=0,$level="",$width=0){
        static $arr=array();
        foreach ($data as $k=>$v){
            if($v['parent'] == $pid){
                $v['level']=$level;
                $v['width']=$width;
                $arr[$k]=$v;
                //$this->getSort($data,$v['did'],$level.'&nbsp;&nbsp;&nbsp;&nbsp;');
            }else{
                $v['level']='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                $v['width']=50;
                $arr[$k]=$v;
            }
        }
        return $arr;
    }
}
