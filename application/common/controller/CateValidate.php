<?php
/*  AdminUser: myAdmin    Date: 2018/6/22   Time: 21:19    */


namespace app\common\controller;


use think\Validate;

class CateValidate extends Validate
{
    protected $rule = [
        'pid'       => ['require','regex'=>'/^\d+$/'],
        'cname'     => ['require','max'=>200,'regex'=>'/^.*+$/']
    ];

    protected $message = [
        'pid.require'       => '父级分类不能为空',
        'pid.regex'         => '父级分类值非法',
        'cname.require'     => '分类名称不能为空',
        'cname.max'         => '分类名称不得超过200个字符',
        'cname.regex'       => '分类名称值非法'
    ];

}