<?php
namespace app\manage\controller;
use app\config\model\filebed;
use app\config\model\myuploadfile;
use think\Controller;
use \think\Request;
use \think\View;
vendor('nos.autoload');
use NOS\NosClient;
use NOS\Core\NosException;
class Nos extends Common {
    public function upload_nos(){
        $accessKeyId = "4a1a94e854704945a3e8fbeaef0538ab";
        $accessKeySecret = "94de1392f9ce4798bd989961b976502d";
        $endPoint = "nos-eastchina1.126.net";
        $bucket = "rainimage";
        $object = "";
        $content = "";

        $filePath = $this->upload_file();
//        echo $filePath;exit();
        $key = date('YmdHis') . rand(0, 9999).$filePath;
        $object = 'filebed/'.$key;
        $content = file_get_contents('./public/uploads/'.date('Ymd').'/'.$filePath);
        try{
            $nosClient = new NosClient($accessKeyId,$accessKeySecret,$endPoint);
            $nosClient->putObject($bucket,$object,$content);
            /** 类似于 http://rainimage.nos-eastchina1.126.net/20180316/201803160903281194.jpg */
            return 'http://'.$bucket.'.'.$endPoint.'/filebed/'.$key;
        } catch(NosException $e){
            print $e->getMessage();
        }

    }

}
