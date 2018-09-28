<?php
/*  AdminUser: myAdmin    Date: 2018/6/24   Time: 18:33    */


namespace app\common\controller;

use think\Validate;

class GoodsValidate extends Validate
{
    protected $rule = [
        'cate_id'       => ['require','regex'=>'/^\d+$/'],
        'gname'         => ['require','max'=>200,'regex'=>'/^.*+$/'],
        'price'         => ['require','regex'=>'/^[^-]?\d+\.?\d+$/'],
        'stock'         => ['require','regex'=>'/^\d+$/'],
        'gdesc'         => ['regex'=>'/^.*+$/'],
    ];
    protected $message = [
        'cate_id.require'       => '商品分类不能为空',
        'cate_id.regex'         => '商品分类值非法',
        'gname.require'         => '商品名称不能为空',
        'gname.max'             => '商品名称不能超过200个字符',
        'gname.regex'           => '商品名称值非法',
        'price.require'         => '商品定价不能为空',
        'price.regex'           => '商品定价值非法',
        'stock.require'         => '商品库存不能为空',
        'stock.regex'           => '商品库存值非法',
        'gdesc.regex'           => '商品描述中不能包含特殊字符'
    ];
}