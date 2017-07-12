<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Http\Requests;
use DB;
use Cache;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use App\PaModel;


class IndexController extends Controller
{
    //首页新闻展示和分类展示
    public function index()
    {
        header("Access-Control-Allow-Origin:*");
        /*星号表示所有的域都可以接受，*/
        header("Access-Control-Allow-Methods:GET,POST");
        $action=isset($_GET['action'])?Input::get('action'):'';
        $jsoncallback=isset($_GET['jsoncallback'])?Input::get('jsoncallback'):'';
        /*if(empty($jsoncallback)){
            return json_encode(array('msg'=>0,'error'=>'NOT FROUND jsoncallback'));die;
        }*/
        if(empty($action)){
            $data=DB::table('cms_news')->join('cms_brand', 'cms_news.bid','=','cms_brand.bid')->limit(10)->get();
            //print_r($brand);die;
        }else{
            $brand=DB::table('cms_brand')->where('bname',$action)->get();
            $data=DB::table('cms_news')->where('bid',$brand[0]['bid'])->get();
            $data['bname']=$brand[0]['bname'];
        }
        //print_r($data);die;
        $arr=['news'=>$data];
        // echo $jsoncallback.'('.json_encode(array('status'=>0,'mess'=>'参数不能为空')).')';die;
        return $jsoncallback.'('.json_encode($arr).')';
    }
    //调用接口添加新闻
    public function add()
    {
        $jsoncallback=isset($_GET['jsoncallback'])?Input::get('jsoncallback'):'';

        $bbname=explode(',',$_GET['bname']);
        foreach($bbname as $k=>$val){
            $url='http://api.jisuapi.com/news/get?channel='.$val.'&start=0&num=20&appkey=fd33d0e0df05c689';
            $data=file_get_contents($url);
            set_time_limit(0);
            $data=json_decode($data ,true);
            $title=$data['result']['channel'];
            $news=$data['result']['list'];

            $brand=DB::table('cms_brand')->where('bname',$title)->get();
            $bid=$brand[0]['bid'];
            //print_r($brand);die;
            foreach ($news as $v){
                $img=$v['pic'];
                $content=$v['content'];
                $addtime=$v['time'];
                $title=$v['title'];
                $a=DB::table('cms_news')->insert(array('bid'=>$bid,
                    'nimg'=>$img,
                    'ncontent'=>$content,
                    'naddtime'=>$addtime,
                    'ntitle'=>$title,
                    'state'=>1));
            }
        }
        return $jsoncallback.'('.json_encode(11).')';

    }
    //查询单条
    public function find()
    {
        header("Access-Control-Allow-Origin:*");
        /*星号表示所有的域都可以接受，*/
        header("Access-Control-Allow-Methods:GET,POST");
        $id=isset($_GET['id'])?Input::get('id'):'';
        $jsoncallback=isset($_GET['jsoncallback'])?Input::get('jsoncallback'):'';
        if(empty($jsoncallback)){
            return json_encode(array('msg'=>0,'error'=>'NOT FROUND jsoncallback'));die;
        }
        //echo $id;die;
        $news=DB::table('cms_news')->where('nid',$id)->get();

        $data=DB::table('cms_brand')->where('bid',$news[0]['bid'])->get();
        $sort=$data[0]['bname'];
        $arr=['sort'=>$sort,'news'=>$news[0]];
        //print_r($arr);die;
        // echo $jsoncallback.'('.json_encode(array('status'=>0,'mess'=>'参数不能为空')).')';die;
        return $jsoncallback.'('.json_encode($arr).')';
    }
    //查询新闻评论
    public function comment()
    {
        header("Access-Control-Allow-Origin:*");
        /*星号表示所有的域都可以接受，*/
        header("Access-Control-Allow-Methods:GET,POST");
        $id=isset($_GET['id'])?Input::get('id'):'';
        $jsoncallback=isset($_GET['jsoncallback'])?Input::get('jsoncallback'):'';
        if(empty($jsoncallback)){
            return json_encode(array('msg'=>0,'error'=>'NOT FROUND jsoncallback'));die;
        }
        //echo $id;die;

        $news=DB::table('cms_comment')->where('nid',$id)->orderBY('like','desc')->limit(3)->get();

        foreach ($news as $k=>$v){
            $name=DB::table('cms_username')->where('uid',$v['uid'])->get();
            $news[$k]['uname']=$name[0]['uname'];
            $news[$k]['img']=$name[0]['img'];
        }
        $arr=['news'=>$news];

        /*$arr = DB::table('cms_comment')
            ->leftJoin('cms_username', 'cms_comment.uid', '=', 'cms_username.uid')
            ->where(['nid'=>$id])
            ->orderBy('like_num','desc')
            ->limit(3)
            ->get();
        print_r($arr);die;*/
        // echo $jsoncallback.'('.json_encode(array('status'=>0,'mess'=>'参数不能为空')).')';die;
        //print_r($arr);die;
        return $jsoncallback.'('.json_encode($arr).')';
    }
    public function newtime()
    {
        header("Access-Control-Allow-Origin:*");
        /*星号表示所有的域都可以接受，*/
        header("Access-Control-Allow-Methods:GET,POST");
        $id=isset($_GET['id'])?Input::get('id'):'';
        $jsoncallback=isset($_GET['jsoncallback'])?Input::get('jsoncallback'):'';
        if(empty($jsoncallback)){
            return json_encode(array('msg'=>0,'error'=>'NOT FROUND jsoncallback'));die;
        }
        //echo $id;die;

        $news=DB::table('cms_comment')->where('nid',$id)->orderBY('addtime','desc')->limit(3)->get();

        foreach ($news as $k=>$v){
            $name=DB::table('cms_username')->where('uid',$v['uid'])->get();
            $news[$k]['uname']=$name[0]['uname'];
            $news[$k]['img']=$name[0]['img'];
        }
        $arr=['news'=>$news];
        //print_r($arr);die;
        // echo $jsoncallback.'('.json_encode(array('status'=>0,'mess'=>'参数不能为空')).')';die;
        return $jsoncallback.'('.json_encode($arr).')';
    }
    public function oldtime()
    {
        header("Access-Control-Allow-Origin:*");
        /*星号表示所有的域都可以接受，*/
        header("Access-Control-Allow-Methods:GET,POST");
        $id=isset($_GET['id'])?Input::get('id'):'';
        $jsoncallback=isset($_GET['jsoncallback'])?Input::get('jsoncallback'):'';
        if(empty($jsoncallback)){
            return json_encode(array('msg'=>0,'error'=>'NOT FROUND jsoncallback'));die;
        }
        //echo $id;die;

        $news=DB::table('cms_comment')->where('nid',$id)->orderBY('addtime','asc')->limit(3)->get();

        foreach ($news as $k=>$v){
            $name=DB::table('cms_username')->where('uid',$v['uid'])->get();
            $news[$k]['uname']=$name[0]['uname'];
            $news[$k]['img']=$name[0]['img'];
        }
        $arr=['news'=>$news];
        //print_r($arr);die;
        // echo $jsoncallback.'('.json_encode(array('status'=>0,'mess'=>'参数不能为空')).')';die;
        return $jsoncallback.'('.json_encode($arr).')';
    }
    public function like()
    {
        $id=isset($_GET['id'])?Input::get('id'):'';
        $jsoncallback=isset($_GET['jsoncallback'])?Input::get('jsoncallback'):'';
        if(empty($jsoncallback)){
            return json_encode(array('msg'=>0,'error'=>'NOT FROUND jsoncallback'));die;
        }
        //echo $id;die;

        $news=DB::table('cms_comment')->where('cid',$id)->get();
        $like=DB::table('cms_comment')->where('cid',$id)->update(['like'=>ceil($news[0]['like']+1)]);
        if($like){
            return $jsoncallback.'(1)';
        }
    }
    public function num()
    {
        $id=isset($_GET['id'])?Input::get('id'):'';
        $jsoncallback=isset($_GET['jsoncallback'])?Input::get('jsoncallback'):'';
        if(empty($jsoncallback)){
            return json_encode(array('msg'=>0,'error'=>'NOT FROUND jsoncallback'));die;
        }
        //echo $id;die;

        $news=DB::table('cms_news')->where('nid',$id)->get();
        $like=DB::table('cms_news')->where('nid',$id)->update(['num'=>ceil($news[0]['num']+1)]);
        if($like){
            return $jsoncallback.'(1)';
        }
    }
    //发表评论
    public function pinglun(){
        $callback=$_GET['callback'];
        $id=$_GET['id'];
        $text=$_GET['text'];
$uid=/*Cache::get('id')*/3;

        $add=DB::table('cms_comment')->insertGetId(
            [   'nid'=>$id,
                'uid'=>$uid,
                'content'=>$text,
                'addtime'=>date('Y-m-d H:i:s',time())
            ]
        );

        if($add){
            $result = DB::table('cms_comment')
                ->leftJoin('cms_username', 'cms_comment.uid', '=', 'cms_username.uid')
                ->where(['nid'=>$id])
                ->orderBy('addtime','desc')
                ->limit(3)
                ->get();
            $results=json_encode($result);
            return $callback.'('.$results.')';

        }

    }
}
