<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Mail;

/*use Symfony\Component\HttpFoundation\Session\Session;*/
use Illuminate\Support\Facades\Session;
use Cache;


header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 1000');

class LoginController extends Controller
{
    //登录验证
    public function login(Request $request){
        header("Access-Control-Allow-Origin:*");
        /*星号表示所有的域都可以接受，*/
        header("Access-Control-Allow-Methods:GET,POST");
        $data = $request->input();
        $uname = $data['uname'];
        $pwd = $data['pwd'];

        $token = isset($data['token'])?$data['token']:'';
        $time = isset($data['time'])?$data['time']:'';
        //校验
        if(empty($token)){
            echo 2;die;
        }
        //校验码不正确
        if($token != md5($uname.$time)){
            echo 4;die;//校验不正确
        }

        //时间不得超过60秒
        $times = time();
        if($times - $time >60){
            echo 3;die;
        }
        $res = DB::table('cms_username')->where(['uname'=>"$uname",'pwd'=>"$pwd"])->first();
       if($res){
           /*Session::put('id', $res['uid']);  //将图形验证码的值写入到session中
           Session::save();*/
           $request->session()->put('id', $res['uid'],10);
           $request->session()->save();
           session(['site'=>'LaravelAcademy.org']);
           echo 1;die;
       }else{
           echo 0;die;
       }
    }

    //用户名验证唯一
    public function reg_uname(Request $request){
        $uname = $request->input('uname');
        if(empty($uname)){
                echo 2;die;
            }else{
                $reg="/^[a-z_]\w{3,9}$/i";
                if(!preg_match($reg, $uname)){
                    echo 2;die;
            }
        }
        $res = DB::table('cms_username')->where(['uname'=>"$uname"])->first();
        if(empty($res)){
            echo 1;die;
        }else{
            echo 0;die;
        }
    }

    //邮箱验证唯一
    public function em(Request $request){
        $email = $request->input('email');
        $res = DB::table('cms_username')->where(['email'=>"$email"])->first();
        if(empty($res)){
            echo 1;die;
        }else{
            echo 0;die;
        }
    }

    //手机号验证唯一
    public function reg_tel(Request $request){
        $tel = $request->input('tel');
       if(empty($tel)){
            echo 3;die;
        }else{
            $reg="/^1[7,3,8,5]\d{9}$/";
            if(!preg_match($reg, $tel)){
                echo 2;die;
            }
            $arr = DB::table('cms_username')->where(['tel'=>"$tel"])->first();
            if(empty($arr)){
                echo 1;die;
            }else{
                echo 0;die;
            }
        }

    }


//注册
    public function reg(Request $request){
        $uname = $request['uname'];
        $pwd = $request['pwd'];
        $tel = $request['tel'];
        $phone = $request['phone'];
        $email = $request['email'];

        if(empty($uname)){
            echo 2;die;
        }else{
            $reg="/^[a-z_]\w{3,9}$/i";
            if(!preg_match($reg, $uname)){
                echo 2;die;
            }
        }

        //手机号
        if(empty($tel)){
            echo 2;die;
        }else{
            $reg="/^1[7,3,8,5]\d{9}$/";
            if(!preg_match($reg, $tel)){
                echo 2;die;
            }
            //密码
            if(empty($pwd)){
                echo 2;die;
            }else{
                if(strlen($pwd)< 6 && strlen($pwd) >10){
                    echo 2;die;
                }
             //邮箱验证
             if(empty($email)){
                 echo 2;die;
             }else{
                 $reg="/^[A-Za-z\d]+([-_.][A-Za-z\d]+)*@([A-Za-z\d]+[-.])+[A-Za-z\d]{2,4}$/";
                 if(!preg_match($reg, $email)){
                     echo 2;die;
                 }
             }
                //短信验证
             if(empty($phone)){
                    echo 2;die;
             }
        $res = DB::table('cms_username')->insertGetId(['uname'=>"$uname",'pwd'=>"$pwd",'tel'=>"$tel",'email'=>"$email",'img'=>'img/a2.png']);
        if($res){
            Cache::put('id',$res['uid'],60);
            echo 1;die;
        }else{
            echo 0;die;
        }
    }
        }
    }

    //短信验证
    public function duanxin(){
        $tel = Input::get('tel');
        $rand=rand(1000,9999);
        $url="http://api.k780.com";
        $data=array(
            'app'=>'sms.send',
            'tempid'=>'51015',
            'param'=>'code%3d'.$rand,
            'phone'=>"$tel",
            'appkey'=>'23760',
            'sign'=>'e9d71c5357903f13aa5c68ddcf799cab',
        );
        $res=$this->curl($url,$data,true);
        $aa = json_decode($res);
        $data = array('res'=>$aa,'rand'=>$rand);
        $data = json_encode($data);
//        $rand = json_encode($data);

        return $data;

    }

    function curl($url,$data=array(),$post=false){
        if(empty($url)){
            return false;
        }
        // 初始化一个 cURL 对象
        $curl = curl_init();
        // 设置header
        curl_setopt($curl, CURLOPT_HEADER, 0);
        // 设置cURL 参数，要求结果(1保存到字符串中)还是(0输出到屏幕上)。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        if($post){
            // post数据
            curl_setopt($curl, CURLOPT_POST, 1);
            // post的变量
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }else{
            $data = http_build_query($data);
            $url = $url.'?'.$data;
        }
        // 设置你需要抓取的URL
        curl_setopt($curl, CURLOPT_URL, $url);
        // 运行cURL，请求网页
        $html = curl_exec($curl);
        // 关闭URL请求
        curl_close($curl);

        return $html;
    }


    //邮箱
    public function email(){
        $email = Input::get('email');
        $rand = Input::get('rand');
        $arr = DB::table('cms_username')->where(['email'=>"$email"])->first();
        if(empty($arr)){
            echo 2;die; //该邮箱没有注册
        }else{
            $res =  Mail::raw($rand,function ($message){
                $message->from('dahanaminwansui@sina.com','小茗同学');
                $message->subject('密码找回');
                $email = Input::get('email');
                $message->to($email);
            });
            if($res){
                echo 1;die;
            }else{
                echo 0;die;
            }
        }


    }

    //密码找回
    public function find(Request $request){
        $email = $request->input('email');
        $news = $request->input('news');
        if(strlen($news)< 6 || strlen($news) >10) {
            echo 2;die;
        }
        $res = DB::table('cms_username')->where('email',"$email")->update(['pwd'=>"$news"]);
        if($res){
            echo 1;die;
        }else{
            echo 0;die;
        }

    }
    //关注的好友
    public function follow(){
      /*$uid=Cache::get('id');*/
        $uid = 3;
        $data = DB::table('cms_username')
            ->leftJoin('cms_follow','cms_username.uid','=','cms_follow.bid')
            ->where(['cms_follow.uid'=>$uid])->get();
        if($data){
            echo json_encode($data);

        }else{
            echo json_encode(2);

        }

    }



}
