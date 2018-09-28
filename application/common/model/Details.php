<?php
/*  AdminUser: myAdmin    Date: 2018/6/28   Time: 14:59    */

namespace app\common\model;

use think\Model;

class Details extends Model
{
    protected $table = 'shop_details';
    protected $pk    = 'did';

    //订单表Detail中定义一个方法,关联Order模型,Detail与Order是一对一的关系
    public function orders()
    {
        return $this->belongsTo('Details','orders_oid','oid');
    }

    //事件模型
    public static function init()
    {
        //self:event('事件',执行的操作);
        //$detail形参表示生成的订单详情
        self::event('after_insert',function($detail){
            //找到下单的商品,修改商品的库存量 = 库存量-购买数量
            //Goodss::find(商品id$detail->gid)->setDec('stock',购买量$detail->cnt);
            Goodss::find($detail->gid)->setDec('stock',$detail->cnt);
        });

        self::event('before_delete',function($detail){
            Goodss::find($detail->gid)->setInc('stock',$detail->cnt);
        });

    }

    // 一条商品详情对应一件商品 一对一关系  一件商品对应多个商品详情 一对多
    public function goodss() {
        return $this->belongsTo('Goodss','gid','gid');
    }

}