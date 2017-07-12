<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2017/6/16
 * Time: 15:59
 */

namespace App\Http\Controllers;
header('Access-Control-Allow-Origin:*');
use Cache;
use App\Http\Controllers;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class OneselfController extends Controller
{

    public function oneself(Request $request){
            $callback=$request->input('callback');
//            Cache::put('id',5,60);
            /*$id=Cache::get('id');*/
        $id=3;
            if(empty($id)){
                return $callback.'(0)';
            }else{
                return $callback.'(1)';
            }
    }

    public function selfdetails(Request $request){

        $callback=$request->input('callback');
        /*$id=Cache::get('id');*/
        $id=3;
        $ziji=DB::table('cms_username')->where('uid',$id)->first();
        $zong=DB::table('cms_follow')->where('uid',$id)->get();
        $ziji['zong']=count($zong);
        $zijii=json_encode($ziji);
        return $callback.'('.$zijii.')';
    }

    //开始修改
    public function upself()
    {
        $file = input::file('img');
        $name=Input::get('al_title');
        $desc=Input::get('descript');


        if ($file->isValid()) {    //判断文件是否上传成功
            $originalName = $file->getClientOriginalName(); //源文件名

            $ext = $file->getClientOriginalExtension();    //文件拓展名

            $type = $file->getClientMimeType(); //文件类型

            $realPath = $file->getRealPath();   //临时文件的绝对路径

            $fileName = time(). uniqid() . '.' . $ext;  //新文件名
            //$new_path = app_path().'/storage/uploads'.''$fileName;

            $new_path = 'D:\Documents\HBuilderProjects\news\img';


            $file->move($new_path,$fileName);

            //数据
            /*$id=Cache::get('id')*/;
            $id=3;
            $add=DB::table('cms_username')->where('uid',$id)->update(['uname'=>$name,'desc'=>$desc,'img'=>$fileName]);
            if($add){

            return json_encode('1');

            }


        }
    }

}