<?php
/*  AdminUser: myAdmin    Date: 2018/6/25   Time: 19:52    */
//验证码
return [
    // 验证码字符集合
    'codeSet'  => '2345678abcdefhijkmnpqrstuvwxyzABCDEFGHJKLMNPQRTUVWXY',
    // 验证码字体大小(px)
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