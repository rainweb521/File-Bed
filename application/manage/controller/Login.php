<?php
namespace app\manage\controller;
use think\Controller;
use \think\Request;
use \think\View;
vendor('baidu.AipFace');
class Login {
    // 人脸识别的 ID ，Key
    public $Face_APP_ID = '10498320';
    public $Face_API_KEY = '0N17xebL0uwNhAogL2S0lGFw';
    public $Face_SECRET_KEY = 'Qja3oVTvbYXEoN0ZB1U4qmgs7Ge51Zqp ';

    public function index(){
//        $login = session('login');
//        if ($login==null){
//            echo "null";exit;
//        }else{
//            echo "login";exit;
//
//        }
        return \view('face');
    }
    /**
     *用于上传头像采集图片的ajax，目录与普通图片不同
     */
    public function image_ajax(){
        $image_src = upload_photo('the_file');
        return $image_src;
    }
    public function door_ajax2(){
        $image_src = upload_image('the_file');
        $result = array('state'=>'1','image1'=>$image_src,'image2'=>'','scoure'=>'');
        return $result;
//        return '{"error_code":222202,"error_msg":"pic not has face","log_id":2659027871,"timestamp":1527231061,"cached":0,"result":null}';
    }
    public function door_ajax(){
//        return array('state'=>-1);
        /**
         * 这里设置登录验证，采集人脸超过十次以后，封锁这个IP，但只是在session上，关闭浏览器将失效
         */
        $login = session('login');
        if ($login==null){
            $login = array('num'=>0,'state'=>1);
            session('login',$login);
        }
        $login = session('login');
        if ($login['num']>10){
            return array('state'=>-1);
        }else{
            $login['num'] = $login['num'] + 1;
            session('login',$login);
        }

        $image_src = upload_image('the_file');
        $client = new \AipFace($this->Face_APP_ID, $this->Face_API_KEY, $this->Face_SECRET_KEY);

        $imageType = "BASE64";
        $result = $client->search(base64_encode(file_get_contents('.'.$image_src)),$imageType,'imnu');

        $image1 = $image_src;
        $image2 = '';
        $state = 0;
        $scoure = 0;
        /** state = 0 表示没有识别到人脸，  1 表示无人脸  2表示通过 */

        if ($result['error_code']==0){
            $scoure = floor($result['result']['user_list'][0]['score']);
            if ($scoure>80){
                $state = 2;
                $user_id = $result['result']['user_list'][0]['user_id'];
//                $stuent_model = new StudentM();
//                $user = $stuent_model->get_Info(['user_id'=>$user_id]);
//                $image2 = $user['photo'];
            }else{
                $state = 1;
            }
        }
        $result = array('state'=>$state,'image1'=>$image1,'image2'=>$image2,'scoure'=>$scoure);
        return $result;
//        return '{"error_code":222202,"error_msg":"pic not has face","log_id":2659027871,"timestamp":1527231061,"cached":0,"result":null}';
    }
}
