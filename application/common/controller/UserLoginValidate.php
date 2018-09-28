<?php
/*  AdminUser: myAdmin    Date: 2018/6/27   Time: 21:27    */

namespace app\common\controller;

use think\Validate;

class UserLoginValidate extends Validate
{
    protected $rule = [
        'uname'     => ['require','regex'=>'/^[^\!|\@|\#|\$|\%|\%|\^|\<|\>|\&|\*|\(|\)]+$/'],
        'upwd'      => ['require']
    ];

    protected $message = [
        'uname.require'     => '用户名不能为空',
        'uname.regex'       => '用户名非法',
        'upwd.require'      => '密码不能为空'
    ];

}