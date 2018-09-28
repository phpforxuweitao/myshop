<?php
/*  AdminUser: myAdmin    Date: 2018/6/20   Time: 12:54    */
namespace app\common\model;

use think\Model;

class HomeUsers extends Model
{
    protected $table = 'home_users';
    protected $pk   = 'uid';
    protected $insert = ['create_time','update_time'];

    protected function getCreateTimeAttr($value) {
        return date('Y-m-d H:i:s',$value);
    }

    protected function getUpdateTimeAttr($value) {
        return date('Y-m-d H:i:s',$value);
    }

    protected function getAuthAttr($value) {
        $auth = [1=>'普通会员',2=>'黄金会员',3=>'白金会员'];
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

    //用户与订单关联 用户为主模型  一对多
    public function orders(){
        return $this->hasMany('Orders','user_uid','uid');
    }

}