<?php
namespace app\manage\controller;
use app\config\model\filebed;
use app\config\model\myuploadfile;
use think\Controller;
use \think\Request;
use \think\View;
vendor('qiniu.autoload');
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
class Qiniu extends Common {
    public function index(){
//        return '<style type="text/css">*{ padding: 0; margin: 0; } .think_default_text{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/><span style="font-size:30px">十年磨一剑 - 为API开发设计的高性能框架</span></p><span style="font-size:22px;">[ V5.0 版本由 <a href="http://www.qiniu.com" target="qiniu">七牛云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_bd568ce7058a1091"></thinkad>';
        $filebed_model = new filebed();
        $list = $filebed_model->get_filebedList();
        $list = array_reverse($list);
//        $list_top = $filebed_model->get_filebedList(array('state'=>2));
        $this->assign('list',$list);
        return view('index');
    }

    public function upload_qiniu(){
//        http://p2s2tpkky.bkt.clouddn.com/filebed/201803281017025997./public/uploads/20180328/7b28b97439ff98c5becdf71f025c3da6.jpg
        // 用于签名的公钥和私钥
        $accessKey = 'BPI8YX6EM6OtAofXhizQ-OpwWpA96SVDFNYuad08';
        $secretKey = 'EeNqy2iBVuc-ml-69La4uit3pxa2Q24nSDw2MGkG';
        $bucket = "uploads";
        // 初始化签权对象
        $auth = new Auth($accessKey, $secretKey);
        // 生成上传 Token
        $token = $auth->uploadToken($bucket);
        // 要上传文件的本地路径
        $filePath = $this->upload_file();
        // 上传到七牛后保存的文件名
        $key = 'filebed/'.date('YmdHis') . rand(0, 9999).$filePath;
//        echo $filePath;exit();
        $filePath = './public/uploads/'.date('Ymd').'/'.$filePath;
        // 初始化 UploadManager 对象并进行文件的上传。
        $uploadMgr = new UploadManager();
        // 调用 UploadManager 的 putFile 方法进行文件的上传。
        list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
//        echo "\n====> putFile result: \n";
        if ($err !== null) {
//            return 'http://p2s2tpkky.bkt.clouddn.com/'.$key;
            var_dump($err);
        } else {
//            var_dump($ret);
            return 'http://p2s2tpkky.bkt.clouddn.com/'.$key;
        }
//        exit();
    }



}
