<?php
/* User: tao    Date: 2018/7/3   Time: 10:49 */

namespace app\common\controller;

use think\Validate;

class HomeUserValidate extends Validate
{
    protected $rule = [
        'uname'         => ['require','max'=>20,'min'=>2,'regex'=>'/[^\@|\!|\~|\#|\$|\%|\%|\^|\&|\*|\(|\)|\<|\>|\。|\?]+/'],
        'sex'           => ['require','regex'=>'/^[w|m|x]{1}$/'],
        'email'         => ['require','max'=>'40','regex'=>'/^[\w-_]+@[\w-_]+(\.\w+){1,3}$/'],
        'tel'           => ['require','max'=>'11','min'=>11,'regex'=>'/^1[3-9]\d{9}$/'],
        'addr'          => ['require']
    ];

    protected $message = [
        'uname.require'     => '用户名不能为空',
        'uname.max'         => '用户名不得超过20位字符',
        'uname.min'         => '用户名字符不得少于2位',
        'uname.regex'       => '用户名非法',
        'sex.require'       => '性别不能为空',
        'sex.regex'         => '性别非法',
        'email.require'     => '邮箱账号不能为空',
        'email.max'         => '邮箱账号最大40个字符',
        'email.regex'       => '邮箱账号非法',
        'tel.require'       => '手机号码不能为空',
        'tel.max'           => '请输入11位数的手机号码',
        'tel.min'           => '请输入11位数的手机号码',
        'tel.regex'         => '手机号码非法',
        'addr.require'      => '收货地址不能为空'
    ];
}