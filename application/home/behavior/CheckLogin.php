<?php
/*  AdminUser: myAdmin    Date: 2018/6/26   Time: 19:24    */
namespace app\home\behavior;

class CheckLogin
{
    public function run() {
        if ( is_null(session('login_home_info')) && !session('login_home_info.islogin') ) {
            session('hm_login_back',true);
            die(header('location:/login'));
        }
    }
}