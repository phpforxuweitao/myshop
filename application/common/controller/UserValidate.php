<?php
/*  AdminUser: myAdmin    Date: 2018/6/21   Time: 20:17    */

namespace app\common\controller;

use think\Validate;

class UserValidate extends Validate
{
    protected $rule = [
        'auth'      => ['require','regex'=>'/^\d$/'],
        'uname'     => ['require','max'=>20,'min'=>2,'regex'=>'/^.*?$/'],
        'upwd'      => ['require','max'=>20,'min'=>6],
        'tel'       => ['max'=>11,'min'=>11,'regex'=>'1(31|32|34|35|36|37|38|39|50|57|58|86|87|88)[0-9]{8}'],
        'sex'       => ['require','regex'=>'/^["w"|"m"|"x"]$/'],
        'addr'      => ['max'=>200,'min'=>3,'regex'=>'/^.*?$/'],
        'email'     => ['require','max'=>42,'regex'=>'/^[\w-_]+@[\w-_]+(\.\w+){1,3}$/']
    ];

    protected $message = [
        'auth.require'      => '权限不能为空',
        'auth.regex'        => '权限值非法',
        'uname.require'     => '名称不能为空',
        'uname.max'         => '名称长度不得超过20个字符',
        'uname.min'         => '名称长度不得低于2个字符',
        'uname.regex'       => '名称值非法',
        'upwd.require'      => '密码不能为空',
        'upwd.max'          => '密码长度不得超过20个字符',
        'upwd.min'          => '密码长度不得低于6个字符',
        'tel.max'           => '请输入完整的11位手机号码',
        'tel.min'           => '请输入完整的11位手机号码',
        'tel.regex'         => '手机号码值非法',
        'sex.require'       => '性别不能为空',
        'sex.regex'         => '性别值非法',
        'addr.max'          => '地址长度不得超过200个字符',
        'addr.min'          => '地址长度不得低于3个字符',
        'addr.regex'        => '地址不能包含特殊字符',
        'email.require'     => '邮箱不能为空',
        'email.max'         => '邮箱账号最长42位',
        'email.regex'       => '邮箱账号非法'
    ];
}