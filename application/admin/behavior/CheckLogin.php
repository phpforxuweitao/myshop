<?php
/*  AdminUser: myAdmin    Date: 2018/6/26   Time: 19:24    */
namespace app\admin\behavior;

class CheckLogin
{
    public function run() {
        if ( is_null(session('login_admin_info')) && !session('login_admin_info.islogin') ) {
            die(header('location:/bk_login'));
        }
    }
}