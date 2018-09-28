<?php
/*  AdminUser: myAdmin    Date: 2018/6/27   Time: 18:02    */

namespace app\home\controller;

use app\common\controller\Base;
use app\common\model\Details;
use app\common\model\Goodss;
use app\common\model\Orders;
use app\common\model\AdminUsers;
use think\Db;
use think\Request;

class Cart extends Base
{
    //1、添加到购物车
    public function addCart(Request $req) {
        $data = $req->post();
        $gid = $data['gid'];

        $ginfo = Goodss::find($gid);

        if ($data['cnt'] > $ginfo->stock) {
            return $this->error("商品数量不够,该商品剩余库存:{$ginfo->stock}","/gdetail-{$gid}",'',2);
        }

        $ginfo->cnt = $data['cnt'];
        //将添加的到购物车中的商品保存到session中,为了防止覆盖，使用session并且保存到二维数组中
        session("cartlist.$gid",$ginfo);

        $total  = 0;    //总金额
        $num    = 0;    //总数量
        if ( !empty( session('cartlist') ) ) {
            foreach (session('cartlist') as $k => $v) {
                $num += $v->cnt;
                $total += $v->cnt * $v->price;
            }
        }

        //将总数量和总金额写入session中
        session('orders.total',$total);
        session('orders.cnt',$num);

        return view('cart/precart',[
            'ginfo'         =>$ginfo
        ]);
    }

    //2、购物车列表
    public function listCart(){
        $total  = 0;    //总金额
        $num    = 0;    //总数量
        if ( !empty( session('cartlist') ) ) {
            foreach (session('cartlist') as $k => $v) {
                $num += $v->cnt;
                $total += $v->cnt * $v->price;
            }
        }

        //将总数量和总金额写入session中
        session('orders.total',$total);
        session('orders.cnt',$num);

        return view('cart/shopping_cart',[
            'num'=>$num,
            'total'=>$total
        ]);
    }


    //3、购物车中商品数量 +1
    public function incr($id) {
        session("cartlist.$id")->cnt++;
        //重定向到订单列表页
        $this->redirect('/cart/list');
        return;
    }

    //4、购物车中商品数量 -1
    public function desc($id) {
        if ( session("cartlist.$id")->cnt <=1 ) {
            session("cartlist.$id")->cnt = 1;
        }else{
            session("cartlist.$id")->cnt--;
        }
        //重定向到订单列表页
        $this->redirect('/cart/list');
        return;
    }
    //5、删除购物车中的指定商品
    public function delete($id) {
        session("cartlist.$id",null);
        //重定向到订单列表页
        $this->redirect('/cart/list');
        return;
    }
}