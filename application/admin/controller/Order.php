<?php
/*  AdminUser: myAdmin    Date: 2018/6/25   Time: 23:36    */

namespace app\admin\controller;

use app\common\controller\Base;
use app\common\model\Details;
use app\common\model\HomeUsers;
use app\common\model\Orders;
use Think\Db;
use think\Request;

class Order extends Base
{
    protected $beforeActionList = [ //前置函数列表
        'menuOrder' => ['except'=>'save,delete,update']
    ];

    protected function menuOrder() { //前置函数
        $this->assign([
            'menu_order'=>'active open',
        ]);
    }

    //订单列表
    public function index(Request $req){
        $data = $req->get();
        $wheres = [];
        if ( !empty($data['s_status']) ) {
            $data['s_status'] = trim($data['s_status']);
            $wheres[] = ['status','=',$data['s_status']];
            $this->assign('s_status',$data['s_status']);
        }
        if ( !empty($data['s_key']) ) {
            $data['s_key'] = trim($data['s_key']);
            if ( is_numeric($data['s_key']) ) {
                $wheres[] = ['oid','like',"%{$data['s_key']}%"];
            }elseif (is_string($data['s_key'])){
                $uid = HomeUsers::where(['uname'=>$data['s_key']])->value('uid');
                if ($uid) {
                    $wheres[] = ['user_uid','=',$uid];
                }
            }
            $this->assign('s_key',$data['s_key']);
        }

        $order_list = Orders::where($wheres)->paginate(3)->appends($_GET);

        return view('order/list',[
            'menu_order_list'       => 'active',
            'olist'     => $order_list,
        ]);
    }

    //进入修改订单页面
    public function edit($oid) {
        $oinfo = Orders::find($oid);

        return view('order/edit',[
            'oinfo'     => $oinfo
        ]);
    }

    //处理修改订单数据
    public function update(Request $req,$oid) {
        $data = $req->post();
        $uid = HomeUsers::where(['uname'=>$data['user_uname']])->find();
        $data['user_uid'] = $uid['uid'];
        $res = Orders::update($data,['oid'=>$oid]);
        if ($res) {
            return $this->success('修改订单成功','/bk_order/list','',1);
        } else {
            return $this->error('修改订单失败',"/bk_order/edit-{$oid}",'',2);
        }
    }

    //进入订单详情页面
    public function orderDetail($oid) {

        $order = Orders::find($oid);

        return view('order/orderdetail',[
            'odetail'   => $order
        ]);
    }

    //删除指定的$oid订单
    public function orderDelete($oid) {
        $orders = Orders::find($oid);
        foreach ($orders->details as $k => $v) {
            Details::destroy($v->did);
        }
        $res = Orders::destroy($oid);

        if ($res) {
            return $this->success('删除订单成功','/bk_order/list','',1);
        } else {
            return $this->error('删除订单失败','/bk_order/list','',2);
        }
    }

    //发货 改变$oid订单号的status为2
    public function send($oid) {
        $res = Orders::update(['status'=>2],['oid'=>$oid]);
        if ($res) {
            return $this->success('发货成功','/bk_order/list','',1);
        } else {
            return $this->error('发货失败','/bk_order/list','',2);
        }
    }
}