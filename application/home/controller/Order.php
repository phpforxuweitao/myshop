<?php
/*  AdminUser: myAdmin    Date: 2018/6/28   Time: 21:05    */

namespace app\home\controller;

use app\common\controller\Base;
use app\common\model\Details;
use app\common\model\Goodss;
use app\common\model\Orders;
use think\Db;
use think\Request;

class Order extends Base
{
    //收货人信息确认页
    public function info()
    {
        if ( empty(session('cartlist')) ) {
            return $this->redirect('/');
        }else {
            return view('order/info');
        }


    }

    //确认订单
    public function checkOrder(Request $req)
    {
        $data = $req->post();
        if ( empty($data['rec']) || empty($data['tel']) || empty($data['addr']) || empty($data['umsg'])) {
            return $this->error('信息不能为空','/order/info','',2);
        }
        //获取订单需要的数据 收件人、电话、地址、留言
        session('orders.rec',$data['rec']);
        session('orders.tel',$data['tel']);
        session('orders.addr',$data['addr']);
        session('orders.umsg',$data['umsg']);
        //购物车中选中的商品
        $carts = session('cartlist');
        $num = session('orders.cnt');
        $total = session('orders.total');

        return view('order/shopping_check',[
            'cartlist'  => $carts,
            'num'       => $num,
            'total'     => $total
        ]);
    }

    //订单成功方法
    public function orderDone()
    {
        //1、获取到要生成的订单数据
        session('orders.oid',date('YmdHis').mt_rand(100,999));
        //2、订单总金额 session('orders.total') 订单总数量 session('orders.num')
        //当前下单用户的uid
        session('orders.user_uid',session('login_home_info.uid'));
        //收件人 session('orders.rec') 地址session('orders.addr') 电话 session('orders.tel') 留言信息 session('orders.umsg')
        //订单状态 1、下单未发货 2 已出库,未收到货物 3 收到货物 4 取消订单
        session('orders.status',1);
        //下单时间
        session('orders.create_time',time());

        //启动事务
        Db::startTrans();
        try {
            $orders = Orders::create(session('orders'),true);
            $orders->details()->saveAll(session('cartlist'));
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            return $this->error('订单提交失败','/','',2);
        }

        //获取到前台页面需要的订单号
        $orderid = session('orders.oid');
        //获取订单的总金额
        $total   = session('orders.total');
        //成功下单后清除购物车
        session('orders',null);
        session('cartlist',null);
        return view('order/shopping_done',[
            'orderid'       => $orderid,
            'total'         => $total
        ]);
    }

    //我的订单页面
    public function myorder()
    {
        //获取我的订单页面需要的数据
        //当前登录用户的订单详情
        $orders = Orders::where(['user_uid'=>session('login_home_info.uid')])->paginate(2);

        $unpay_num = Orders::where(['user_uid'=>session('login_home_info.uid')])->count();
        foreach ($orders as $k=>$v) {
            $v->g_list_nums = $v->details()->count();
        }

        return view('order/my_order',[
            'u_orderlist'       => $orders,
            'unpay_num'         => $unpay_num
        ]);
    }

    //用户删除指定的$oid订单
    public function orderDelete($oid) {
        $orders = Orders::find($oid);
        foreach ($orders->details as $k => $v) {
            Details::destroy($v->did);
        }
        $res = Orders::destroy($oid);

        if ($res) {
            return $this->success('删除订单成功','/order/myorder','',1);
        } else {
            return $this->error('删除订单失败','/order/myorder','',2);
        }
    }

    //立即支付
    public function selectPay($oid) {
        $order = Orders::find($oid);

//        ["oid"] => string(17) "20180702211003719"
//        ["total"] => string(9) "439340.00"
//        ["cnt"] => int(55)
//        ["user_uid"] => int(43)
//        ["rec"] => string(9) "许伟涛"
//        ["addr"] => string(39) "天河区宦溪西路爱丁堡幼儿园"
//        ["tel"] => string(11) "13535565666"
//        ["status"] => string(9) "未发货"
//        ["umsg"] => string(12) "老地方哟"


        return view('order/selectpay',[
            'oinfo'       => $order
        ]);
    }

    //确认收货
    public function confirmGet($oid)
    {
        $res = Orders::update(['status'=>3],['oid'=>$oid]);
        if ( $res ) {
            return $this->success('确认收货成功','/order/myorder','',1);
        } else {
            return $this->error('确认收货失败','/order/myorder','',2);
        }
    }

    //删除已经完成的订单记录
    public function del_success_order()
    {
        return '该功能未开通';
    }

}