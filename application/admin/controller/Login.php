<?php
/*  AdminUser: myAdmin    Date: 2018/6/20   Time: 8:38    */


namespace app\admin\controller;

use app\common\controller\LoginValidate;
use app\common\model\AdminUsers;
use think\captcha\Captcha;
use think\Controller;
use think\Request;

class Login extends Controller
{
    private $limitNum       = 5;
    private $closeTime      = 300;

    //进入后台登录页面
    public function login()
    {
        return view('login/login');
    }

    public function verify(){
        $config = [
            'fontSize' => 18,
            // 是否画混淆曲线
            'useCurve' => false,
            // 验证码图片高度
            'imageH'   => 38,
            // 验证码图片宽度
            'imageW'   => 110,
            // 验证码位数
            'length'   => 2,
            // 验证成功后是否重置
            'reset'    => true
        ];
        $captcha = new Captcha($config);
        return $captcha->entry();
    }

    //处理后台登录数据
    public function dologin(Request $req){
        $data = $req->post();
        $back_url = '/bk_login';
//        //判断是否是本机
//        if($_SERVER['SERVER_ADDR'] !== '127.0.0.1'){
//            $this->error('你能登录我服你','/bk_login','',2);
//            exit;
//        }
        $closeTime = ceil($this->closeTime/60);

        $loginValidate = new LoginValidate();
        if ( !$loginValidate->check($data) ) {
            //登录参数验证错误
            return $this->error($loginValidate->getError(),$back_url,'',2);
        }
        $captch = new Captcha();
        if ( !$captch->check($data['vCode']) ) {
            //验证码验证失败
            $this->error('验证码错误',$back_url,'',2);
            exit;
        }
        //登录成功 设置$_SESSION['admin_info']['islogin'] = true; $_SESSION['admin_info']['adminName'] = '已登陆用户名';
        $ip = 'bk_login_'.$_SERVER['REMOTE_ADDR'];
        $err = cache($ip);
        if ( !empty($err) && $err >= $this->limitNum ) {
            $this->error("已经登录错误{$err}次,{$closeTime}分钟后方可登录",$back_url,'',2);
            exit;
        } else {
            $res = AdminUsers::where('upwd',md5($data['upwd']))->where('uname',$data['uname'])->field('uid,uname,auth')->find();
            if ($res && $res->getNumRows()) {
                session('login_admin_info.islogin',true);
                session('login_admin_info.uid',$res['uid']);
                session('login_admin_info.adminName',$res['uname']);
                session('login_admin_info.auth',$res['auth']);
                cache($ip,null);//登录成功，清除记录
                $this->success('登录成功,正在跳转,请稍等...','/bk_index','',1);
                exit;
            }else {
                $err = $this->admin_login_errorNum($this->limitNum,$this->closeTime);
                if ($err < $this->limitNum) {
                    $this->error("用户名或密码错误,登录错误次数{$err}",$back_url,'',2);
                    exit;
                } else {
                    $this->error("已经登录错误{$err}次,{$closeTime}分钟后方可登录");
                    exit;
                }
            }
        }
    }

    //返回错误次数
    protected function admin_login_errorNum($limitNum = 5,$closeTime = 300) {
        //获取用户的ip作为标识
        $ip = 'bk_login_'.$_SERVER['REMOTE_ADDR'];
        $err = cache($ip);
        if (empty($err)) {
            $err = 1;
            //cache缓存写入 300秒(5分钟)
            cache($ip,$err,$closeTime);
        } elseif (!empty($err) && $err < $limitNum) {
            ++ $err;
            cache($ip,$err,$closeTime);
        } elseif ($err >= $limitNum) {
            cache($ip,$err,$closeTime);
        }
        return $err;    //返回错误次数
    }

    //后台退出
    public function logout()
    {
        session('login_admin_info',null);
        return $this->success('成功退出','/bk_login','',1);
    }

}