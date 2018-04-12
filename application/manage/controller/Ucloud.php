<?php
namespace app\manage\controller;
use app\config\model\filebed;
use app\config\model\myuploadfile;
use think\Controller;
use \think\Request;
use \think\View;
vendor('ucloud.proxy');
//require_once('../ucloud/proxy.php');
class Ucloud extends Common {
    public function upload_ucloud(){
//        http://rainupload.cn-bj.ufileos.com/filebed/2018032820103847568935dbbe12404ee84e40e6ec86de2773.jpg
        //存储空间
        $bucket = 'rainupload';
        //上传至存储空间后的文件名称(请不要和API公私钥混淆)
        $filePath = $this->upload_file();
        //        echo $filePath;exit();
        $key = 'filebed/'.date('YmdHis') . rand(0, 9999).$filePath;
        //待秒传的本地文件路径
        $file   = './public/uploads/'.date('Ymd').'/'.$filePath;
        //该接口不是上传接口.如果秒传返回非200错误码,意味着该文件在服务器不存在
        //需要继续调用其他上传接口完成上传操作
        list($data, $err) = UCloud_PutFile($bucket, $key, $file);
        if ($err) {
            echo "error: " . $err->ErrMsg . "\n";
            echo "code: " . $err->Code . "\n";
//            exit;
        }
        return 'http://rainupload.cn-bj.ufileos.com/'.$key;
    }

}
