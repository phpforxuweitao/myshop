<?php
/*  AdminUser: myAdmin    Date: 2018/6/20   Time: 8:49    */


namespace app\admin\controller;


class Error
{
    public function _empty($name)
    {
        return view('common/notfound');
    }
}