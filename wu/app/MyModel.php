<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;


class MyModel extends Model
{
    protected $table='kao';
    protected $primaryKey='id';
    //字段
    protected  $fillable = ['name','sex','age','hobby'];




}