<?php
namespace app\manage\controller;
use app\config\model\filebed;
use app\config\model\myuploadfile;
use think\Controller;
use \think\Request;
use \think\View;
vendor('cos.cos-autoloader');
use Qcloud\Cos\Client;
class Cos extends Common {
    public function upload_cos(){
        $cosClient = new Client(array('tj' => getenv('ap-beijing-1'),
            'credentials'=> array(
//                'appId' => getenv('1252406596'),
                'secretId'    => getenv('AKIDV1Z3NbbedVDj5HzAtRoz18BFG8rQ2BOQ'),
                'secretKey' => getenv('62Qfkh7OTr8DCG9qYeIuS0cAhaO0RsAR'))));
        try {
            $result = $cosClient->putObject(array(
                //bucket的命名规则为{name}-{appid} ，此处填写的存储桶名称必须为此格式
                'Bucket' => 'backup-1252406596',
                'Key' => '11.txt',
                'Body' => 'Hello World!'));
            print_r($result);
        } catch (\Exception $e) {
            echo "$e\n";
        }
        exit();
    }

}
