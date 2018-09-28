<?php
/*  AdminUser: myAdmin    Date: 2018/6/28   Time: 14:43    */

namespace app\common\model;

use think\Model;

class Orders extends Model
{
    protected $table = 'shop_orders';
    protected $pk    = 'oid';


    public function getStatusAttr($value){
        //1下单未发货2出库,未收货3收货完成4用户取消订单
        $status = [1=>'未发货',2=>'已发货',3=>'已收货',4=>'取消订单'];
        return $status[$value];
    }

    //订单表Order中定义一个方法,关联Detail模型,Order与detail是一对多的关系
    public function details()
    {
        return $this->hasMany('Details','orders_oid','oid');
    }

    //订单表与用户表关联  一对一
    public function homeusers(){
        return $this->belongsTo('HomeUsers','user_uid','uid');
    }

}