<?php
/*  AdminUser: myAdmin    Date: 2018/6/20   Time: 12:57    */

namespace app\common\controller;

use think\Controller;
use think\Request;

class Base extends Controller
{
    use \traits\controller\Sendmail;    //引用traits继承 邮件发送类 Sendmail
    //处理上传的文件
    public function uploads($fileName,$moveDir,$size='2048000',$ext='jpg,png,jpeg,gif') {
        //获取表单上传文件
        $file = \request()->file($fileName);
        //检测是否有选择文件
        if ( is_null($file) ) {
            return $this->returnError(1,'未选择任何文件');
        }
        //移动到框架应用根目录下的$moveDir子目录下,并验证文件大小和文件类型
        $info = $file->validate(['size'=>$size,'ext'=>$ext])->move($moveDir);
        if ($info) {
            //成功上传后获取上传信息
            return $this->returnSuucess(0,$info->getSaveName());
        } else {
            //上传失败获取错误信息
             return $this->returnError(1,$file->getError());
        }
    }

    protected function returnSuucess($code,$msg){
        $data = $this->returnData($code,$msg);
        return $data;
    }

    protected function returnError($code,$msg){
        $data = $this->returnData($code,$msg);
        return $data;
    }

    protected function returnData($code,$msg) {
        $data = [
            'code'  => $code,
            'time'  =>time(),
            'message'   => $msg
        ];
        return $data;
    }

}