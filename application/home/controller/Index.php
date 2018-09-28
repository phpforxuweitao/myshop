<?php
namespace app\home\controller;


use app\common\controller\Base;
use app\common\controller\HomeUserValidate;
use app\common\model\Cates;
use app\common\model\HomeUsers;
use app\common\model\Orders;
use think\Request;

class Index extends Base
{
    //前置函数 用来验证后台的每一个操作在访问之前是否已经登录
    protected $beforeActionList = [

    ];
    public function index($gid='')
    {

        $total  = 0;    //总金额
        $num    = 0;    //总数量
        if ( !empty( session('cartlist') ) ) {
            foreach (session('cartlist') as $k => $v) {
                $num += $v->cnt;
                $total += $v->cnt * $v->price;
            }
        }

        $cates = Cates::getCateTree();

        $unpay_num = Orders::where(['user_uid'=>session('login_home_info.uid')])->count();


        return view('index/index',[
            'cates'     => $cates,
            'num'       => $num,
            'total'     => $total,
            'unpay_num'         => $unpay_num
        ]);
    }

    //进入用户中心
    public function userCenter() {
        $uid = session('login_home_info.uid');
        $uinfo = HomeUsers::field('upwd',true)->find($uid);

        return view('index/userCenter',[
            'uinfo'         => $uinfo
        ]);
    }

    //处理用户修改信息数据
    public function upUserInfo(Request $req) {
        $uid = session('login_home_info.uid');//已登录用户id
        $data = $req->post();
        $back_path = "/userCenter";

        //使用tp5提供的Validate类验证post中的值
        $goodsValidate = new HomeUserValidate();
        if ( !$goodsValidate->check($data) ) {
            $this->error($goodsValidate->getError(),$back_path,'',2);
            return false;
        }

        $file = $req->file('uface');
        if ( !is_null($file) ) {
            $savePath = $this->uploads('uface',config('app.userImg_save_path'),'2048000','jpg,jpeg,png,gif');
            $data['uface']   = $savePath['message'];
        }

        $res = HomeUsers::update($data,['uid'=>$uid],true);
        if ( $res ) {
            $this->success('用户信息修改成功',$back_path,'',1);
            return false;
        } else {
            $this->error('用户信息修改失败',$back_path,'',1);
            return false;
        }
    }

    //前台用户修改密码
    public function modifyPwd(Request $req) {
        $data = $req->post();
        $uid = session('login_home_info.uid');//已登录用户id
        $back_path = "/userCenter";
        if ( empty($data['upwd']) ) {
            return $this->error('原密码不能为空',$back_path,'',2);
        }
        if ( empty($data['newpwd']) ) {
            return $this->error('新密码不能为空',$back_path,'',2);
        }
        if ( empty($data['renewPwd']) ) {
            return $this->error('确认新密码不能为空',$back_path,'',2);
        }

        if ( $data['renewPwd'] !== $data['newpwd'] ) {
            return $this->error('新密码与确认新密码一致',$back_path,'',2);
        }

        if ( $data['upwd'] === $data['newpwd'] ) {
            return $this->error('原密码与新密码相同',$back_path,'',2);
        }

        $check = HomeUsers::where(['upwd'=>md5($data['upwd']),'uid'=>$uid])->value('uid');
        if ( $check ) { //原密码正确,执行下面的真正修改操作
            $data['newpwd'] = md5($data['newpwd']);
            $res = HomeUsers::where(['uid'=>$uid])->update(['upwd'=>$data['newpwd']]);

            if ($res) {
                return $this->success('修改密码成功',$back_path,'',1);
            } else {
                return $this->error('修改密码失败',$back_path,'',2);
            }

        } else { //原密码错误,返回
            return $this->error('原密码错误',$back_path,'',2);
        }

    }

    public function in_notfound()
    {
        return view('index/notfound');
    }

}
