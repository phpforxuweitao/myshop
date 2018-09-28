<?php
/*  AdminUser: myAdmin    Date: 2018/6/26   Time: 23:49    */

namespace app\common\controller;

use think\Validate;

class ModifyPwd extends Validate
{
    protected $rule = [
        'upwd'          => ['require'],
        'reupwd'        => ['require'],
        'origin'        => ['require'],
    ];

    protected $message = [
        'origin.require'    => '原密码不能为空',
        'upwd.require'      => '密码不能为空',
        'reupwd.require'    => '确认密码不能为空',
    ];
}