<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class PaModel extends Model
{
//    protected $table='kao';
//    protected $primaryKey='id';
    //查
    public function getList($table)
    {
        return DB::table($table)->get();
    }
    //添加
    public function getAdd($table,$input){
        return DB::table($table)->insert($input);
    }
    //删除
    public function getDel($table,$where){
        return DB::table($table)->where($where)->delete();

    }
    //查 条件
    public function getWhere($table,$where,$f=0)
    {
        $data=DB::table($table)->where($where)->get();
        if($f==0){
            return $data[0];
        }
        if($f==1){
            return $data;
        }
    }
    //修改
    public function getUpd($table,$where,$str){
        return DB::table($table)->where($where)->update($str);
    }
}
