<?php
/*  AdminUser: myAdmin    Date: 2018/6/25   Time: 20:44    */

namespace app\common\controller;

use think\Validate;

class LoginValidate extends Validate
{
    protected $rule = [
        'uname'         => ['require','regex'=>'/^\w{1,30}$/'],
        'upwd'          => ['require']
    ];
    protected $message = [
        'uname.require'     => '用户名不能为空',
        'uname.regex'       => '用户名值非法',
        'upwd.require'      => '密码不能为空'
    ];
}