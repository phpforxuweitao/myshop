<?php
/* User: tao    Date: 2018/7/3   Time: 21:28 */

namespace app\common\controller;

use think\Validate;

class HomeUserRegisterValidate extends Validate
{
    protected $rule = [
        'email'         => ['require','max'=>40,'regex'=>'/^[\w-_]+@[\w-_]+(\.\w+){1,3}$/'],
        'uname'         => ['require','max'=>20,'min'=>3,'regex'=>'/^[^\!|\@|\#|\$|\%|\%|\^|\<|\>|\&|\*|\(|\)]+$/'],
        'upwd'          => ['require','max'=>20,'min'=>6],
        'vali_code'     => ['require','regex'=>'/^\d{6}$/']
    ];

    protected $message = [
        'email.require'     => '邮箱账号不能为空',
        'email.max'         => '邮箱账号长度最大40位',
        'email.regex'       => '邮箱账号非法',
        'uname.require'     => '用户名不能为空',
        'uname.max'         => '用户名最多20位字符',
        'uname.min'         => '用户名最低3位字符',
        'upwd.require'      => '密码不能为空',
        'upwd.max'          => '密码最多20位字符',
        'upwd.min'          => '密码最低6位字符',
        'vali_code.require' => '邮箱验证码不能为空',
        'vali_code.regex'   => '邮箱验证码非法'
    ];
}