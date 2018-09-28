<?php
/**
 * Created by PhpStorm.
 * User: tao
 * Date: 2018/7/2
 * Time: 18:37
 */

namespace app\admin\controller;

use app\common\controller\Base;
use app\common\controller\UserValidate;
use app\common\model\HomeUsers;
use think\Request;

class HomeUser extends Base
{
    protected $beforeActionList = [ //前置函数列表
        'menuHomeUser' => ['except'=>'save,delete,update,delete,relieve']
    ];

    protected function menuHomeUser() { //前置函数
        $this->assign([
            'menu_homeuser'=>'active open',
        ]);
    }

    //查询数据,显示到页面
    public function index(Request $request)
    {
        $wheres = [];
        if ( !empty($request->get('searchSex')) ) {
            $wheres[] = ['sex','=',$request->get('searchSex')];
            $this->assign('s_Sex',$request->get('searchSex'));
        }
        if ( !empty($request->get('searchWord')) ) {
            $s_word = $request->get('searchWord');
            $wheres[] = ['uname','like',"%{$s_word}%"];
            $this->assign('s_word',$request->get('searchWord'));
        }

        $ulist = HomeUsers::where($wheres)->order('uid','desc')->field('upwd',true)->paginate(7)->appends($_GET);

        return view('homeuser/list',[
            'ulist'         => $ulist,
            'menu_homeuser_list'=> 'active'
        ]);
    }
    //进入一个添加表单
    public function create()
    {
        return view('homeuser/insert',[
            'menu_homeuser_add'=>'active'
        ]);
    }

    //接收添加表单的数据
    public function save(Request $request)
    {
        $data = $request->post();
        $back_url = '/bk_homeuser/add';
        $this->checkSava_Up($data,$back_url);

        $check_uname = HomeUsers::where('uname',$data['uname'])->find();
        if ( $check_uname ){
            $this->error("用户名：'<b>{$data['uname']}</b>' 已存在!",$back_url,'',2);
            return false;
        }

        $check_tel = HomeUsers::where('tel',$data['tel'])->find();
        if ( $check_tel ){
            $this->error("手机号码：'<b>{$data['tel']}</b>' 已存在!",$back_url,'',2);
            return false;
        }


        $res = HomeUsers::create($data,true);
        if ($res->getLastInsID()) {
            $this->success('添加成功','/bk_homeuser/list','',1);
            return false;
        }else{
            $this->error('添加失败','/bk_homeuser/add','',2);
            return false;
        }
    }

    //进入一个修改页面
    public function edit($uid)
    {
        $uinfo = HomeUsers::where(['status'=>'0'])->field('upwd,create_time',true)->find($uid);
        if ( $uinfo['status'] !== '正常' ) {
            $this->redirect('/bk_homeuser/list',301);
            return false;
        }
        return view('homeuser/edit',[
            'uinfo' => $uinfo
        ]);
    }

    //接收修改页面的数据,更新到数据库
    public function update(Request $request,$uid)
    {
        $data = $request->post();
        $back_url = "/bk_homeuser/edit/{$uid}";
        $this->checkSava_Up($data,$back_url);

        try {
            HomeUsers::update($data,['uid'=>$uid],true);
        } catch (\Exception $e) {
            $this->error('修改失败',"/bk_homeuser/edit/{$uid}",'',2);
            return false;
        }
        $this->success('修改成功','/bk_homeuser/list','',1);
        return false;
    }

    //接收到uid，禁用该用户
    public function delete($uid) {
        //软删除 是将用户的状态更新为 '1'
        $res = HomeUsers::update(['uid'=>$uid,'status'=>'1']);
        if ($res) {
            $this->success('禁用成功','/bk_homeuser/list','',1);
            return false;
        } else {
            $this->error('禁用失败','/bk_homeuser/list','',1);
            return false;
        }
    }

    //用户添加与用户更新的公共部分代码
    protected function checkSava_Up($data,$back_url) {

        $data_validate = new UserValidate();
        if ( !$data_validate->check($data) ) {
            $this->error($data_validate->getError(),$back_url,'',2);
            return false;
        }

        if ( $data['upwd'] !== $data['reupwd'] ) {
            $this->error('密码与确认密码不一致!',$back_url,'',2);
            return false;
        }
        if ( $data['uname'] === $data['upwd'] ){
            $this->error('用户名与密码不能一样!',$back_url,'',2);
            return false;
        }
    }

    //接收uid,解除用户禁用
    public function relieve($uid) {
        //解禁 是将用户的状态更新为 '0'
        $res = HomeUsers::update(['uid'=>$uid,'status'=>'0']);
        if ($res) {
            $this->success('解禁成功','/bk_homeuser/list','',1);
            return false;
        } else {
            $this->error('解禁失败','/bk_homeuser/list','',1);
            return false;
        }
    }
}