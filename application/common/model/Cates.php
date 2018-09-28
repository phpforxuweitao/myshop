<?php
/*  AdminUser: myAdmin    Date: 2018/6/22   Time: 19:04    */

namespace app\common\model;

use think\Model;

class Cates extends Model
{
    protected $table = 'shop_cate';
    protected $pk    = 'cid';
    protected $insert = ['create_time','update_time'];

    protected function getCreateTimeAttr($value) {
        return date('Y-m-d H:i:s',$value);
    }

    protected function getUpdateTimeAttr($value) {
        return date('Y-m-d H:i:s',$value);
    }

    protected function setCreateTimeAttr() {
        return time();
    }
    protected function setUpdateTimeAttr() {
        return time()+mt_rand(222,999);
    }

    //获取无限极分类的方法
    public static function getCateTree($cates = [],$pid = 0) {
        if (empty($cates)) {
            $cates = self::select();
        }
        /*
         *  对分类进行递归遍历,获取到拍好序的分类数据
         *      第一个一级类
         *          当前一级类下的二级类
         *              当前二级类下的三级类
         *                  ....
         *      第二个一级类
         *          当前一级类下的二级类
         *              当前二级类下的三级类
         *                  ....
         */
        $tmp = [];
        foreach ($cates as $k => $v) {
            if ($v->pid == $pid) {
                $v->child   = self::getCateTree($cates,$v->cid);
                $tmp[$v->cid]   = $v;
            }
        }
        return $tmp;
    }

    /*
     *      与shop_goods表关联(一对多) 一个分类被多个商品所使用
     *  分类模型Cates是主模型其中的cid与 商品模型Goodss中的cate_id是关联(主外键)关系，所以Goodss模型是被关联模型
     */
    public function goods()
    {
        // 如果模型关系是一对多，主模型中需要使用hasMany方法
        // return $this->hasMany(要关联的模型，两个模型关联的外键，主模型的主键)
        return $this->hasMany('Goodss','cate_id','cid');
    }

}