<?php

namespace App\Http\Controllers;

use App\mymodel;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;

class VideoController extends Controller
{
        //视频的列表的展示
		public function  video_url(){

			$callback=$_GET['callback'];
					$data=DB::table('cms_video')->get();
					$datas=json_encode($data);

			return $callback.'('.$datas.')';

		}

        //单个视频的详细页面的展示
        public function video_content(){
            $callback=$_GET['callback'];
            $id=$_GET['id'];
            $data= DB::table('cms_video')->where('vid',$id)->first();
            $datas=json_encode($data);

            return $callback.'('.$datas.')';
        }

        //视频的点赞的数量
        public function video_zan(){
            $callback=$_GET['callback'];
            $id=$_GET['id'];
            $update=DB::table('cms_video')->where('vid',$id)->increment('video_zan',1);
            if($update){
                $num=DB::table('cms_video')->where('vid',$id)->select('video_zan')->get();
                if($num){
                    return $callback.'('.$num[0]['video_zan'].')';
                }
            }

        }


        //视频的评论
        public function video_speak(){
            $callback=$_GET['callback'];
            $id=$_GET['id'];
            $text=$_GET['text'];


            $add=DB::table('cms_videospeak')->insertGetId(
                ['video_id'=>$id,
                 'video_tspeak'=>$text,
                    'speak_time'=>date('Y-m-d H:i:s',time())
                ]
            );

            if($add){
                $result=DB::table('cms_videospeak')->where('video_id',$id)->orderBy('speak_time',"desc")->take(3)->get();

                $results=json_encode($result);
                return $callback.'('.$results.')';

            }

        }

        //评论的人气展示数据
        public function video_renqi(){
            $callback=$_GET['callback'];
            $id=$_GET['id'];
            $data=DB::table('cms_videospeak')
                ->leftJoin('cms_username', 'cms_videospeak.user_id', '=', 'cms_username.uid')

                ->where('video_id',$id)->orderBy('speak_bang',"desc")->take(3)->get();
            if($data){
//                $arr[]=$data;
                $datas=json_encode($data);
                return $callback.'('.$datas.')';

            }
        }

    //评论的最新的数据
    public function video_new(){
        $callback=$_GET['callback'];
        $id=$_GET['id'];
        $data=DB::table('cms_videospeak')->leftJoin('cms_username', 'cms_videospeak.user_id', '=', 'cms_username.uid')
            ->where('video_id',$id)->orderBy('speak_time',"desc")->take(3)->get();
        if($data){

            $datas=json_encode($data);
            return $callback.'('.$datas.')';

        }
    }

    //评论的最早的数据
    public function video_asc(){
        $callback=$_GET['callback'];
        $id=$_GET['id'];
        $data=DB::table('cms_videospeak')
            ->leftJoin('cms_username', 'cms_videospeak.user_id', '=', 'cms_username.uid')

            ->where('video_id',$id)->orderBy('speak_time',"asc")->take(3)->get();
        if($data){

            $datas=json_encode($data);
            return $callback.'('.$datas.')';

        }
    }



    public function video(){

           echo date('Y-m-d H:i:s',time());

    }





}

?>