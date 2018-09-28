<?php
/*  AdminUser: myAdmin    Date: 2018/6/24   Time: 13:46    */


namespace app\admin\controller;

use app\common\controller\Base;
use app\common\controller\GoodsValidate;
use app\common\model\Cates;
use app\common\model\Goodss;
use think\Request;

class Goods extends Base
{
    protected $beforeActionList = [ //前置函数列表
        'menuGoods' => ['except'=>'save,delete,update']
    ];

    protected function menuGoods() { //前置函数
        $this->assign([
            'menu_goods'=>'active open',
        ]);
    }
    //商品列表页面
    public function index(Request $req) {
        $data = $req->get();

        $wheres = [];
        if ( !empty($data['s_cate']) ) {
            $wheres[] = ['cate_id','=',$data['s_cate']];
            $this->assign('s_cate',$data['s_cate']);
        }
        if ( !empty($data['s_key']) ) {
            $wheres[] = ['gname','like',"%{$data['s_key']}%"];
            $this->assign('s_key',$data['s_key']);
        }

        //获取商品分类表中的分类数据
        $clist = Cates::orderRaw("concat(path,cid)")->select();

        $glist = Goodss::where($wheres)->paginate(10)->appends($_GET);

        return view('goods/list',[
            'menu_goods_list'    => 'active',
            'glist'         => $glist,
            'clist'         => $clist,
        ]);
    }
    //进入添加商品页面
    public function create() {
        $cates = Cates::orderRaw("concat(path,cid)")->select();
        return view('goods/add',[
            'menu_goods_add'    => 'active',
            'cates_list'        => $cates,

        ]);
    }
    //处理添加商品
    public function save(Request $req) {
        $data = $req->post();
        //使用tp5提供的Validate类验证post中的值
        $goodsValidate = new GoodsValidate();
        if ( !$goodsValidate->check($data) ) {
            $this->error($goodsValidate->getError(),'/bk_goods/add','',2);
            return false;
        }
        //在Base基类中封装一个文件上传类，返回值 code 0表示没有错 1表示有错误 time表示调用时间,message表示错误信息或文件保存在服务器中的路径
        $savePath = $this->uploads('gpic',config('app.goodsImg_save_path'),'2048000','jpg,jpeg,png,gif');
        if ( 1 === $savePath['code']) {
            $this->error($savePath['message'],'/bk_goods/add','',2);
            return false;
        }
        $data['gpic'] = $savePath['message'];

        //?是否需要检测该商品名称是否已经存在!
        $check = Goodss::where(['gname'=>$data['gname']])->find();
        if ($check) {
            $this->error('该商品名称已存在','/bk_goods/add','',2);
            return false;
        }

        //将数据插入商品表中
        $res = Goodss::create($data,true);

        if ($res && $res->getNumRows()) {
            $this->success('添加商品成功','/bk_goods/list','',1);
            return false;
        }else{
            $this->success('添加商品失败','/bk_goods/add','',1);
            return false;
        }
    }

    //进入商品的修改页面
    public function edit($id,$cid) {
        $cates = Cates::orderRaw("concat(path,cid)")->select();
        $ginfo = Goodss::find($id);
        return view('goods/edit',[
            'ginfo'             => $ginfo,
            'cates_list'        => $cates,
            'gid'               => $id,
            'cid'               => $cid
        ]);
    }

    //处理商品修改数据
    public function update(Request $req,$id,$cid) {
        $data = $req->post();
        $error_back_path = "/bk_goods/edit-{$id}-{$cid}";

        //使用tp5提供的Validate类验证post中的值
        $goodsValidate = new GoodsValidate();
        if ( !$goodsValidate->check($data) ) {
            $this->error($goodsValidate->getError(),$error_back_path,'',2);
            return false;
        }

        $file = $req->file('gpic');
        if ( !is_null($file) ) {
            $savePath = $this->uploads('gpic',config('app.goodsImg_save_path'),'2048000','jpg,jpeg,png,gif');
            $data['gpic']   = $savePath['message'];
        }

        $res = Goodss::update($data,['gid'=>$id],true);
        if ( $res && $res->getNumRows() ) {
            $this->success('id为:'.$id.'的商品修改成功','/bk_goods/list','',1);
            return false;
        } else {
            $this->error('id为:'.$id.'的商品修改失败',$error_back_path,'',1);
            return false;
        }
    }

    protected function upOrdown($status=1,$id){
        $res = Goodss::update(['status'=>$status],['gid'=>$id],true);
        return $res;
    }

    //商品上架功能
    public function goodsUp($id) {  //status 8
        $res = $this->upOrdown(2,$id);
        if ($res) {
            $this->success('上架成功','/bk_goods/list','',1);
            return false;
        }else{
            $this->error('上架失败','/bk_goods/list','',2);
            return false;
        }
    }
    //商品下架功能
    public function goodsDown($id) {    //status 9
        $res = $this->upOrdown(3,$id);
        if ($res) {
            $this->success('下架成功','/bk_goods/list','',1);
            return false;
        }else{
            $this->error('下架失败','/bk_goods/list','',2);
            return false;
        }
    }

    //商品删除功能
    public function goodsDelete($id) {
        $res = Goodss::destroy($id);
        if ($res) {
            $this->success('id为:'.$id.'的商品删除成功','/bk_goods/list','',2);
            return false;
        } else {
            $this->error('id为:'.$id.'的商品删除失败','/bk_goods/list','',2);
            return false;
        }
    }

    //更改排序 升/降
    public function orderlist($type) {
        $sql = '';
        if ('asc' === $type) {
            $sql = '升序';
        } elseif ('desc' ===$type) {
            $sql = '降序';
        }
        return $sql;
    }
}