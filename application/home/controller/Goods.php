<?php
/*  AdminUser: myAdmin    Date: 2018/6/26   Time: 17:00    */

namespace app\home\controller;

use app\common\controller\Base;
use app\common\model\Cates;
use app\common\model\Goodss;
use think\Request;

class Goods extends Base
{
    public function index(Request $req)
    {
        $condition = [];
        $id = $req->get('id');

        session('glist_id',$id);

        $cates_ids = Cates::where('path','LIKE',"%,{$id},%")->column('cid');
        array_unshift($cates_ids,$id);

        if ($id) {
            $condition[] = ['cate_id','IN',$cates_ids];
        }
        $gname = $req->get('gname');
        if ($gname) {
            $condition[] = ['gname','like','%'.$gname.'%'];
        }

        $glist = Goodss::where(['status'=>'2'])->where($condition)->paginate(10);
        return view('goods/goods_list',[
            'glist'         => $glist
        ]);
    }

    public function goodsDetail($gid) {
        $ginfo = Goodss::find($gid);
        return view('goods/goods_detail',[
            'ginfo'         => $ginfo,
            ''
        ]);
    }

}