<?php
namespace app\index\controller;
use app\config\model\filebed;
use app\config\model\key_password;
use think\Controller;
use \think\Request;
use \think\View;
class Index extends Controller {
    public function index(){
//        return '<style type="text/css">*{ padding: 0; margin: 0; } .think_default_text{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/><span style="font-size:30px">十年磨一剑 - 为API开发设计的高性能框架</span></p><span style="font-size:22px;">[ V5.0 版本由 <a href="http://www.qiniu.com" target="qiniu">七牛云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_bd568ce7058a1091"></thinkad>';
        $filebed_model = new filebed();
        $list = $filebed_model->get_filebedIndex_List();
        $list_top = $filebed_model->get_filebedList(array('state'=>2));
        $list = array_merge(array_reverse($list_top), array_reverse($list));
        $this->assign('list',$list);
        return view('index');
    }

    public function test(){
//        $value = input('get.value');
//        $value = Request::instance()->get('value');
//        $value2 = Request::instance()->get('value2');
//        $value = $_GET['value'];
        $filebed_model = new filebed();
        return $filebed_model->get_filebedInfo();
    }
    public function upload(){
        $file = \request()->file('file');
//        var_dump($file);
        /**
         * 获取对象中的属性，先指向对象属性，然后在括号里写数组的名称
         */
//        echo '<hr>5423523333333333333333333333'.$file->getInfo('name');
        if ($file){
            /**
             * 现在大小为3M，去掉三个0
             *
                 验证参数	说明
                 size	上传文件的最大字节
                 ext	文件后缀，多个用逗号分割或者数组
                 type	文件MIME类型，多个用逗号分割或者数组
             */

            $info = $file->validate(['size'=>3072000])->move(ROOT_PATH.'public'.DS.'uploads');
            if($info){
                // 成功上传后 获取上传信息
                // 输出 文件格式 jpg
//                echo $info->getExtension();
//                echo "<br>";
//                // 输出 文件保存的目录 20160820/42a79759f284b767dfcb2a0197904287.jpg
//                echo $info->getSaveName();echo "<br>";
//                echo $info->getSize();echo "<br>";
//                // 输出 文件名称 42a79759f284b767dfcb2a0197904287.jpg
//                echo $info->getFilename();
                $data = array();
                $request = Request::instance();
                $data['file_name'] = $file->getInfo('name');
                $data['file_size'] = $info->getSize();
                $data['file_url'] = '\public\uploads\\'.$info->getSaveName();
                $data['file_type'] = $info->getExtension();
                $data['upload_time'] = date('Y-m-d');
                $data['upload_ip'] = $request->ip();
                $data['upload_address'] = "address";
                $data['out_time'] = date("Y-m-d", strtotime("7 day"));
                $data['state'] = 1;
                $filebed_model = new filebed();
                $filebed_model->insert_filebedInfo($data);
                $this->success('上传成功');
            }else{
                // 上传失败获取错误信息
//                echo $file->getError();
                $this->error('上传失败,可能是由于以下原因：'.$file->getError());
            }
        }else{
            $this->error('上传失败,可能没有选择文件哦，请不要直接将文件拖入游览器，或者不要上传大于3M的文件');
        }
    }

    /**
     * 公共的文件过期程序，文件删除程序，文件到期，会将其过期，过期三天后将会删除
     * 再调用邮箱接口，发送邮件
     */
    public function function_set_out_file(){
        $filebed_model = new filebed();
        $text = '';
        $list = $filebed_model->get_filebedList(array('state'=>'1','out_time'=>date('Y-m-d')));
        $text = '<h2>今日过期文件有'.count($list).'个</h2>';
        foreach ($list as $file){
            $text = $text.$file['file_name'].'<br>';
            $filebed_model->set_Out(array('id'=>$file['id']));
        }
        $delete_day = date("Y-m-d", strtotime("-3 day "));
        $list = $filebed_model->get_filebedList(array('state'=>'0','out_time'=>$delete_day));
        $text = $text.'<h2>今日删除的过期文件有'.count($list).'个</h2>';
        foreach ($list as $file){
            $text = $text.$file['file_name'].'<br>';
            $filebed_model->delete_filebedInfo($file['id']);
            unlink('.'.$file['file_url']);
        }
        $title = date('Y-m-d')."-文件床清理报告";
        $email = 'nylrain@163.com';
//        $key_model = new key_password();
//        $key = $key_model->get_key_passwordInfo(array('id'=>'1'));
        $url='http://function.rain1024.com/index.php?c=email&a=public_rain_sendemail&title='.$title.'&email='.$email.'&message='.$text;
        $result = file_get_contents($url);
        echo $result;
        echo $text;
    }
}
