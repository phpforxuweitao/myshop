<?php
/*  AdminUser: myAdmin    Date: 2018/6/22   Time: 19:07    */


namespace app\admin\controller;


use app\common\controller\Base;
use app\common\controller\CateValidate;
use app\common\model\Cates;
use think\Request;

class Cate extends Base
{
    protected $beforeActionList = [ //前置函数列表
        'menuCate' => ['except'=>'save,delete,update']
    ];

    protected function menuCate() { //前置函数
        $this->assign([
            'menu_cate'=>'active open',
        ]);
    }

    //分类列表页面
    public function index() {
        $cate_list = Cates::orderRaw("concat(path,cid)")->paginate(14);

        return view('cate/list',[
            'menu_cate_list'    => 'active',
            'cate_list'             => $cate_list
        ]);
    }
    //进入添加分类页面
    public function create($id='')
    {
        $data = Cates::orderRaw("concat(path,cid)")->select();

        return view('cate/add',[
            'menu_cate_add'     => 'active',
            'data_cate'         => $data,
            'cid'               => $id
        ]);
    }
    //处理添加分类数据
    public function save(Request $request,$id)
    {
        $data = $request->post();
        //获取验证添加分类的验证器
        $cateValidate = new CateValidate();
        if ( !$cateValidate->check($data) ) {
            $this->error($cateValidate->getError(),'/bk_cate/add','',2);
        }

        //如果是一级类 path = '0,'
        if ($data['pid'] == '0') {
            $data['path'] = '0,';
        } else {
            //如果不是一级类 path = 父类id的path + '父类id, '
            $data['path'] = Cates::get($data['pid'])->path.$data['pid'].',';
        }

        //添加之前检查分类名称是否已经存在
        $check = Cates::where(['cname'=>$data['cname']])->find();
        if ($check) {
            $this->error('该分类名称已存在',"/bk_cate/add/{$id}",'',2);
            return false;
        }

        try {
            Cates::create($data,true);
        } catch (\Exception $e) {
            $this->error('添加分类失败','/bk_cate/add','',2);
            return false;
        }
        $this->success('添加分类成功','/bk_cate/list','',1);
        return false;
    }
    //进入编辑分类页面
    public function edit($id){
        $catainfo = Cates::field('cid,cname,pid')->find($id);
        return view('cate/edit',[
            'cate'          => $catainfo
        ]);
    }
    //处理分类编辑
    public function update(Request $req,$id) {
        $data = $req->post();
        //更新之前检查分类名称是否已经存在
        $check = Cates::where(['cname'=>$data['cname']])->find();
        if ($check) {
            $this->error('该分类名称已存在',"/bk_cate/{$id}/edit",'',2);
            return false;
        }

        //进行更新操作
        $res = Cates::update($data,['cid'=>$id],true);
        if ($res) {
            $this->success('修改分类名称成功','/bk_cate/list','',1);
            return false;
        } else {
            $this->error('修改分类名称失败',"/bk_cate/{$id}/edit",'',2);
            return false;
        }
    }
    //删除分类(不允许删除有子类的分类)
    public function delete($id) {
        //删除之前 是否有数据的pid为$id
        $check = Cates::where(['pid'=>$id])->find();
        if ($check) {
            $this->error('当前类有子类,不允许删除','/bk_cate/list','',2);
            return false;
        }
        $res = Cates::destroy($id);
        if ($res) {
            $this->success('分类删除成功','/bk_cate/list','',1);
            return false;
        } else {
            $this->error('分类删除失败','/bk_cate/list','',2);
            return false;
        }
    }

    //批量删除
    public function batchdel() {
        return '批量删除!';
    }
    //升序/降序
    public function orderlist() {
        return '升序/降序';
    }

}