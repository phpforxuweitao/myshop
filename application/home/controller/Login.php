<?php
/*  AdminUser: myAdmin    Date: 2018/6/26   Time: 18:17    */

namespace app\home\controller;

use app\common\controller\Base;
use app\common\controller\HomeUserRegisterValidate;
use app\common\controller\UserLoginValidate;
use app\common\model\AdminUsers;
use app\common\model\HomeUsers;
use think\Request;

class Login extends Base
{
    public function login() {
        return view('login/login');
    }

    public function dologin(Request $req) {
        $data = $req->post();
        $back_url = '/login';
        $check = new UserLoginValidate();
        if ( !$check->check($data) ) {
            return $this->error($check->getError(),$back_url,'',2);
        }

        $res = HomeUsers::where(['upwd'=>md5($data['upwd']),'uname'=>$data['uname']])->find();
        if ($res) {
            session('login_home_info.islogin',true);
            session('login_home_info.uid',$res['uid']);
            session('login_home_info.username',$res['uname']);
            $login_back = '/';
            if ( empty( session('hm_login_back') ) ) {
                $login_back = 'order/checkOrder';
            }
            return $this->success('登录成功',$login_back,'',1);
        } else {
            return $this->error('用户名或密码错误',$back_url,'',2);
        }
    }

    public function logout()
    {
        session('login_home_info',null);
        return $this->success('成功退出','/login','',1);
    }

    public function register() {
        return view('login/register');
    }

    public function doregister(Request $req)
    {
        $data = $req->post();
        $data['email'] = trim($data['email']);
        $cache_check = cache($data['email']);
        if ($cache_check != $data['vali_code']) {
            return $this->reErrorData('邮箱验证码错误');
        }

        $reg_validate = new HomeUserRegisterValidate();
        if ( !$reg_validate->check($data) ) {
            return $this->reErrorData($reg_validate->getError());
        }

        $res = HomeUsers::create($data,true);
        if ($res) {
            return $this->reSuccessData('注册成功');
        } else {
            return $this->reErrorData('注册失败');
        }
    }

    public function UserExists(Request $req)
    {
        $data = $req->post();
        $check = HomeUsers::where(['uname'=>$data['uname']])->find();
        if ($check) { //如果用户名已存在
            return $this->reErrorData('用户名已存在');
        } else { //用户名不存在
            return $this->reSuccessData('用户名可以使用');
        }
    }

    /**
     * 根据提交的邮箱帐号 发送验证码
     */
    public function getEmailVerifyCode(Request $req)
    {
        $data = $req->post();
        $reg_check = preg_match('/^[\w-_]+@[\w-_]+(\.\w+){1,3}$/',$data['email']);
        //通过验证规则类  验证邮箱(正则)
        if ( $reg_check == 0 ) {
            return $this->reErrorData('邮箱账号非法');
        }


        $check_email = HomeUsers::where(['email'=>$data['email']])->value('email');
        //检测邮箱是否已被注册
        if ( $check_email ) {
            return $this->reErrorData('邮箱已被注册,换一个');
        }else {
            $verifyCode = mt_rand(111111,999999);
            cache($data['email'],$verifyCode,60);
            return $this->reSuccessData('邮箱验证码为: '.$verifyCode);
        }


//        $res = $this->sendMail($data['email'],'用户注册验证码',"欢迎注册成为本站的用户,注册验证码为<b>:$verifyCode".'</b>有效期为60秒');
//        cache($data['email'],$verifyCode,60);//使用tp5的缓存(memcache/redis)通过邮箱帐号来保存 验证码的值
//        if($res){
//            return true;
//        }else{
//            return false;
//        }
    }

    protected function reSuccessData($msg) {
        $data = [
            'code'      => 1,
            'msg'       => $msg,
            'time'      => time()
        ];

        return $data;
    }

    protected function reErrorData($msg) {
        return [
            'code'      => 0,
            'msg'       => $msg,
            'time'      => time()
        ];
    }

}