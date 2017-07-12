<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use DB;
use Illuminate\Support\Facades\Input; 
use App\Http\Controllers\Controller;
use Cache;
header("Access-Control-Allow-Origin:*");
/*星号表示所有的域都可以接受，*/
header("Access-Control-Allow-Methods:GET,POST");
class MemberController extends Controller{
	public function index(){

		$callback = Input::get('callback');
		$bid  = Input::get('bid');
		/*$uid = 1;*/
		// print_r($uid);die;
		$data=DB::table('cms_username')->where(['uid'=>$bid])->get();
		// print_r($data);die;
		// $data=DB::table('cms_video')->where(['uid'=>$uid])->get();
		echo $callback.'('.json_encode($data).')';
	}
	
	//关注
	public function follow(Request $request){
		$callback = Input::get('callback');
        $data['uid'] = 3;
		$data['bid'] = $_GET['bid'];
	    $arr = DB::table("cms_follow")->where($data)->get();
	   if($arr){
	   	 
	   	  echo $callback.'('.json_encode(array('status'=>2)).')';
	   }else{
	   	  $res = DB::table("cms_follow")->insert($data);
	   	  if($res){
	   	  	echo $callback.'('.json_encode(array('status'=>1)).')';
	   	  }else{
	   	  	echo $callback.'('.json_encode(array('status'=>3)).')';
	   	  }
	   }
	}
}
?>	