<?php
namespace app\admin\controller;

use app\common\controller\Base;
use app\common\controller\ModifyPwd;
use app\common\model\AdminUsers;
use app\common\model\Cates;
use app\common\model\Goodss;
use app\common\model\HomeUsers;
use app\common\model\Orders;
use think\Request;

class Index extends Base
{
    protected $beforeActionList = [ //前置函数列表
        'menuIndex'
    ];

    protected function menuIndex() { //前置函数
        $this->assign([
            'menu_index'=>'active',
        ]);
    }

    public function index()
    {
        $goods_total_nums = Goodss::count();
        $cate_total_nums  = Cates::where('pid','<>',0)->count();
        $order_total_nums = Orders::count();
        $homeuser_total_nums = HomeUsers::count();

       return view('index/index',[
           'g_total_nums'       => $goods_total_nums,
           'c_total_nums'       => $cate_total_nums,
           'o_total_nums'       => $order_total_nums,
           'homeuser_total_nums'=> $homeuser_total_nums
       ]);
    }

    //进入修改管理员密码页面
    public function modifyPwd() {
        return view('index/modifypwd');
    }

    //处理修改密码数据
    public function updatePwd(Request $req) {
        $data = $req->post();
        $back_url = '/bk_admin/modifyPwd';
        //验证确认密码与密码是否一致
        if ( $data['upwd'] !== $data['reupwd'] ) {
            $this->error('密码与确认密码不一致',$back_url,'',2);
            exit;
        }
        //原密码与新密码不能一样
        if ( $data['origin'] === $data['upwd'] ) {
            $this->error('原密码与新密码一致',$back_url,'',2);
            exit;
        }
        $checkValidate = new ModifyPwd();
        if ( !$checkValidate->check($data) ) {
            $this->error($checkValidate->getError(),$back_url,'',2);
            exit;
        }

        $data['upwd'] = md5($data['upwd']);

        $check = AdminUsers::where(['uid'=>$data['uid'],'upwd'=>md5( $data['origin'])])->find();

        if ( $check ) {
            //执行更新操作
            $res = AdminUsers::where(['uid'=>$data['uid']])->update(['upwd'=>$data['upwd']]);
            if ( $res ) {
                $this->success('修改密码成功','/bk_index','',1);
                exit;
            } else {
                $this->error('修改密码失败',$back_url,'',2);
                exit;
            }
        } else {
            $this->error('原密码错误,错误5次之后强制下线,30分钟后方可登录',$back_url,'',2);
            exit;
        }

    }

    //查看服务器信息页面
    public function systemInfo()
    {
        return view('index/systeminfo');
    }
    public function notfound()
    {
        return view('common/notfound');
    }
}
