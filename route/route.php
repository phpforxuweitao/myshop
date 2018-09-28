<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

Route::get('/','home/Index/index');
Route::get('verify','admin/Login/verify');

//后台模块路由
Route::group(['name'=>'bk_index/','prefix'=>'admin/Index/'],function(){
    //Index控制器
    Route::get('','index');
    Route::get('systeminfo$','systemInfo');//查看服务器信息
    //一旦设置了miss路由，相当于开启了强制路由模式
    Route::miss('admin/Index/notfound');
})->after(['app\admin\behavior\CheckLogin']);

//管理员密码修改组路由
Route::group(['name'=>'bk_admin/','prefix'=>'admin/Index/'],function(){
    //进入修改密码页面
    Route::get('modifyPwd','modifyPwd');
    //处理修改密码数据
    Route::post('updatePwd','updatePwd');
    Route::miss('admin/Index/notfound');
})->after(['app\admin\behavior\CheckLogin']);

//登录与退出模块
Route::get('bk_login$','admin/Login/login');//后台登陆
Route::post('bk_login/dologin$','admin/Login/dologin');//处理后台登录
Route::get('bk_logout$','admin/Login/logout');//后台退出

//AdminUsers模块组
Route::group(['name'=>'bk_user/','prefix'=>'admin/AdminUser/'],function(){
    //进入用户列表页
    Route::get('list$','index');
    //进入用户添加页
    Route::get('add$','create');
    //处理添加用户
    Route::post('save$','save');
    //禁用用户
    Route::get('delete/<uid>$','delete');
    //解除禁用用户
    Route::get('relieve/<uid>$','relieve');
    //进入修改用户页面
    Route::get('edit/<uid>$','edit');
    //处理修改用户页面
    Route::post('update/<uid>$','update');
    //一旦设置了miss路由，相当于开启了强制路由模式
    Route::miss('admin/Index/notfound');
})->pattern([
    'uid'       => '\d+'
])->after(['app\admin\behavior\CheckLogin']);

//HomeUsers模块组
Route::group(['name'=>'bk_homeuser/','prefix'=>'admin/HomeUser/'],function(){
    //进入用户列表页
    Route::get('list$','index');
    //进入用户添加页
    Route::get('add$','create');
    //处理添加用户
    Route::post('save$','save');
    //禁用用户
    Route::get('delete/<uid>$','delete');
    //解除禁用用户
    Route::get('relieve/<uid>$','relieve');
    //进入修改用户页面
    Route::get('edit/<uid>$','edit');
    //处理修改用户页面
    Route::post('update/<uid>$','update');
    //一旦设置了miss路由，相当于开启了强制路由模式
    Route::miss('admin/Index/notfound');
})->pattern([
    'uid'       => '\d+'
])->after(['app\admin\behavior\CheckLogin']);

//Cate分类模块
Route::group(['name'=>'bk_cate/','prefix'=>'admin/Cate/'],function(){
    //进入分类列表页
    Route::get('list/[:id]','index');
    //进入添加分类页面
    Route::get('add/[:id]','create');
    //处理添加分类数据
    Route::post('<id>/save$','save');
    //进入编辑分类页面
    Route::get('<id>/edit','edit');
    //处理编辑分类数据
    Route::post('<id>/update','update');
    //删除分类
    Route::get('delete-<id>','delete');
    //升序/降序
    Route::get('orderlist','orderlist');
    Route::miss('admin/Index/notfound');
})->pattern([
    'id'    => '\d+'
])->after(['app\admin\behavior\CheckLogin']);

//Goods模块
Route::group(['name'=>'bk_goods/','prefix'=>'admin/Goods/'],function(){
    //进入商品添加页面
    Route::get('add','create');
    //处理商品
    Route::post('save','save');
    //进入商品列表页面
    Route::get('list','index');
    //进入商品修改页面
    Route::get('edit-<id>-<cid>','edit');
    //处理修改商品的数据
    Route::post('update-<id>-<cid>','update');
    //商品上架
    Route::get('<id>/gUp','goodsUp');
    //商品下架
    Route::get('<id>/gDown','goodsDown');
    //商品的删除
    Route::get('<id>/gDelete','goodsDelete');
    //更改商品列表的排序 升/降
    Route::get('orderlist-<type>','orderlist');
    Route::miss('admin/Index/notfound');
})->pattern([
    'id'        => '\d+',
    'cid'       => '\d+',
    'type'      => 'asc|desc'
])->after(['app\admin\behavior\CheckLogin']);

//订单模块
Route::group(['name'=>'bk_order/','prefix'=>'admin/Order/'],function(){
    //进入订单列表页面
    Route::get('list','index');
    //进入订单修改页面
    Route::get('edit-<oid>','edit');
    //处理订单修改数据
    Route::post('update-<oid>','update');
    //发货
    Route::get('send-<oid>','send');
    //进入订单详情页面
    Route::get('detail-<oid>','orderDetail');
    //删除订单
    Route::get('delete-<oid>','orderDelete');
    Route::miss('admin/Index/notfound');
})->after(['app\admin\behavior\CheckLogin']);



#--------------------------------------------------------------------------------#
#--------------------------------------------------------------------------------#
//前台模块路由

Route::get('index','home/Index/index');
Route::get('login','home/Login/login');//前台进入登录页面
Route::post('dologin','home/Login/dologin');//处理前台登录数据
Route::get('logout','home/Login/logout');//前台退出
Route::get('register','home/Login/register');//前台进入注册页

Route::post('do_register','home/Login/doregister');//前台处理注册数据
Route::post('getEmailVerifyCode','home/Login/getEmailVerifyCode');//获取邮箱验证码
Route::post('checkUserExists','home/Login/UserExists');

Route::get('glist/[:gid]','home/Goods/index');//商品列表页面
Route::get('gdetail-<gid>','home/Goods/goodsDetail');//商品详情页面(未写)

//购物车页面
Route::post('cart/add','home/Cart/addCart');//添加商品到购物车(保存到session中)

Route::group(['name'=>'','prefix'=>''],function(){
    Route::get('cart/list','home/Cart/listCart');//购物车列表页
    Route::get('cart/incr/:id','home/Cart/incr');//商品购买数量 +1
    Route::get('cart/desc/:id','home/Cart/desc');//商品购买数量 -1
    Route::get('cart/delete/:id','home/Cart/delete');//删除商品
    Route::get('order/info','home/Order/info'); //收货人信息确认页
    Route::post('order/checkOrder','home/Order/checkOrder');//确认订单页面
    Route::get('deleteOrder-<oid>','home/Order/orderDelete');
    Route::get('order/orderDone','home/Order/orderDone');//确认订单页面
    Route::get('order/myorder','home/Order/myorder');//我的订单页面
    Route::get('del_success_order','home/Order/del_success_order');//删除已经完成的订单记录
    Route::get('confirmGet-<oid>','home/Order/confirmGet');//确认收货
    Route::get('selectPay-<oid>','home/Order/selectPay');//进入支付页面
    Route::get('userCenter','home/Index/userCenter');//进入用户中心
    Route::post('upuserinfo','home/Index/upUserInfo');//处理用户信息修改数据
    Route::post('modifyPwd','home/Index/modifyPwd');
})->after(['app\home\behavior\CheckLogin']);


Route::miss('home/Index/in_notfound');


return [
];