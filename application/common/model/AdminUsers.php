<?php
/*  AdminUser: myAdmin    Date: 2018/6/20   Time: 12:54    */
namespace app\common\model;

use think\Model;

class AdminUsers extends Model
{
    protected $table = 'admin_users';
    protected $pk   = 'uid';
    protected $insert = ['create_time','update_time'];

    protected function getCreateTimeAttr($value) {
        return date('Y-m-d H:i:s',$value);
    }

    protected function getUpdateTimeAttr($value) {
        return date('Y-m-d H:i:s',$value);
    }

    protected function getAuthAttr($value) {
        $auth = [1=>'超级管理员','2'=>'后台管理员','3'=>'前台管理员'];
        return $auth[$value];
    }

    protected function getSexAttr($value) {
        $sex = ['w'=>'女','m'=>'男','x'=>'保密'];
        return $sex[$value];
    }
    protected function getStatusAttr($value){
        $status = ['0'=>'正常','1'=>'禁用','2'=>'注销'];
        return $status[$value];
    }

    protected function setUpwdAttr($value) {
        return md5($value);
    }

    protected function setCreateTimeAttr() {
        return time();
    }
    protected function setUpdateTimeAttr() {
        return time()+mt_rand(222,999);
    }

}