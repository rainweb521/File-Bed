<?php
/**
 * Created by PhpStorm.
 * User: Rain
 * Date: 2017/10/25
 * Time: 16:57
 */

namespace app\manage\controller;
use think\Controller;

class Common extends Controller {
    public function __construct(){
        header("Content-type: text/html; charset=utf-8");
        error_reporting(E_ERROR | E_WARNING | E_PARSE);
        parent::__construct();

//        $this->_initialize();
//        $this->_init();
        $this->assign('title','童晶印象');
    }
    protected function _initialize(){
        //只报告错误,忽略通知
        error_reporting(E_ALL ^ E_NOTICE);
        // 定义当前请求的系统常量
        define('NOW_TIME', $_SERVER['REQUEST_TIME']);
        define('REQUEST_METHOD',$_SERVER['REQUEST_METHOD']);
        define('IS_GET', REQUEST_METHOD =='GET' ? true : false);
        define('IS_POST', REQUEST_METHOD =='POST' ? true : false);
        define('IS_PUT', REQUEST_METHOD =='PUT' ? true : false);
        define('IS_DELETE', REQUEST_METHOD =='DELETE' ? true : false);
        return;
    }
    public function upload_file(){
        $image = \request()->file('file');
        if($image){
            /**
             * //判断图片文件的格式
             * 判断图片大小 最大为10mb
             */
            $info = $image->validate()->move(ROOT_PATH . 'public' . DS . 'uploads');
//             echo $info->getSize();
//        $info->getExtension()   //判断图片文件的格式
//        $info->getSize()>10000000 //判断图片文件的格式
            if($info){
                $image_src = ''.$info->getFilename();
                return $image_src;
            }else{
                return 0;
            }
        }else{
            return 1;
        }
    }
    /**
     * 初始化
     * @return
     */
    public function _init(){

       //  如果已经登录
        $isLogin = $this->isLogin();
        if (!$isLogin) {
            $this->assign('User',array('username'=>'0'));
            // 跳转到登录页面
//            $this->redirect('/index.php/index/login/index');
//            $url = '/index.php?c=login';
//            echo "<script language=\"javascript\">";
//            echo "location.href=\"$url\"";
//            echo "</script>";
        }else{
            $this->assign('User',session('User'));
        }
    }

    /**
     * 获取登录用户信息
     * @return array
     */
    public function getLoginUser()
    {
        return session("User");
    }

    /**
     * 判定是否登录
     * @return boolean
     */
    public function isLogin()
    {
        $user = $this->getLoginUser();
        if ($user && is_array($user)) {
            return true;
        }

        return false;
    }
    public function _empty(){
        echo "不存在的方法";
    }

}