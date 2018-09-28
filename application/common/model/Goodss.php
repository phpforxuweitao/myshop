<?php
/*  AdminUser: myAdmin    Date: 2018/6/24   Time: 13:29    */

namespace app\common\model;

use think\Model;

class Goodss extends Model
{
    protected $table = 'shop_goods';
    protected $pk    = 'gid';
    protected $insert = ['create_time','update_time'];

    protected function getStatusAttr($value) {
        $status = [1=>'新品',2=>'上架',3=>'下架'];
        return $status[$value];
    }

    protected function getCreateTimeAttr($value) {
        return date('Y-m-d H:i:s',$value);
    }

    protected function getUpdateTimeAttr($value) {
        return date('Y-m-d H:i:s',$value);
    }

    protected function setCreateTimeAttr() {
        return time();
    }
    protected function setUpdateTimeAttr() {
        return time()+mt_rand(222,999);
    }

    /*
     * 与shop_cate表关联(一对一) 一个商品对应一个分类
     *  定义关联模型，通过商品模型找到分类模型
     */
    public function cates()
    {
        // return $this->belongsTo('要关联的模型','两个模型关联的外键','主模型的主键')
        return $this->belongsTo('Cates','cate_id','cid');
    }

    //商品对订单详情 一对多关联
    public function details(){
        return $this->hasMany('Details','gid','gid');
    }
}