<?php
namespace app\manage\controller;
use app\config\model\filebed;
use app\config\model\myuploadfile;
use think\Controller;
use \think\Request;
use \think\View;
//vendor('nos.autoload');
//use NOS\NosClient;
//use NOS\Core\NosException;
//vendor('qiniu.autoload');
//use Qiniu\Auth;
//use Qiniu\Storage\UploadManager;
class Index extends Common {
    public function index(){
//        return '<style type="text/css">*{ padding: 0; margin: 0; } .think_default_text{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/><span style="font-size:30px">十年磨一剑 - 为API开发设计的高性能框架</span></p><span style="font-size:22px;">[ V5.0 版本由 <a href="http://www.qiniu.com" target="qiniu">七牛云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_bd568ce7058a1091"></thinkad>';
        $filebed_model = new filebed();
        $list = $filebed_model->get_filebedList();
        $list = array_reverse($list);
//        $list_top = $filebed_model->get_filebedList(array('state'=>2));
        $this->assign('list',$list);
        return view('index');
    }
    public function test(){
        $day_time = date("Y-m-d", strtotime("-5 day"));
        echo $day_time;
//        $value = input('get.value');
//        $value = Request::instance()->get('value');
//        $value2 = Request::instance()->get('value2');
//        $value = $_GET['value'];
//        $filebed_model = new filebed();
//        return $filebed_model->get_filebedInfo();
    }

    /**
     * 删除文件，只是删除文件，再将记录值设置为删除
     */
    public function delete(){
        $id = Request::instance()->get('id',0);
        if ($id){
            $filebed_model = new filebed();
            $filebed_model->delete_filebedInfo($id);
            $file = $filebed_model->get_filebedInfo(array('id'=>$id));
            unlink('.'.$file['file_url']);
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }

    /**
     * 给文件添加有效期，默认为 7 天
     */
    public function add_out_time(){
        $id = Request::instance()->get('id',0);
        if ($id){
            $filebed_model = new filebed();
            $filebed_model->set_Out_time(array('id'=>$id),7);
            $this->success('修改有效期成功，增加了 7 天的有效期');
        }else{
            $this->error('修改失败');
        }
    }

    /**
     * 将文件设置为过期，但不删除文件
     */
    public function set_Out(){
        $id = Request::instance()->get('id',0);
        if ($id){
            $filebed_model = new filebed();
            $filebed_model->set_Out(array('id'=>$id));
            $this->success('已将文件设置为过期');
        }else{
            $this->error('修改失败');
        }
    }
    public function myfile(){
        $status = Request::instance()->get('status',1);
        $myfile_model = new myuploadfile();
        $data = $myfile_model->get_myuploadfileList(['status'=>$status]);
        return \view('myfile',array('data'=>$data));
    }
    public function uploads(){
        $flag = Request::instance()->post('flag',0);
        $data = array();
        if ($flag==0){
            $this->assign('data',$data);
        }else{
//            $data['file'] = $this->upload_nos();
            $box = Request::instance()->post('checkbox/a');
            if ($box!=null){
                $myfile_model = new myuploadfile();
                foreach ($box as $l){
                    $line = array();
                    if ($l==1){
                        $nos = new Nos();
                        $line['file'] = $nos->upload_nos();
                        $line['status'] = 1;
                        $line['site'] = '网易云NOS';
                    }else if ($l==2){
                        $cos = new Cos();
                        $line['file'] = $cos->upload_cos();
                        $line['status'] = 2;
                        $line['site'] = '腾讯云COS';
                    }else if ($l==3){
                        $qiniu = new Qiniu();
                        $line['file'] = $qiniu->upload_qiniu();
                        $line['status'] = 3;
                        $line['site'] = '七牛云';
                    }else if ($l==6){
                        $ucloud = new Ucloud();
                        $line['file'] = $ucloud->upload_ucloud();
                        $line['status'] = 6;
                        $line['site'] = 'Ucloud';
                    }
                    $myfile_model->insert_myuploadfileInfo($line);
                    array_push($data,$line);
                }

            }
            $this->assign('data',$data);
        }
        return \view('uploads');
    }


    /**
     * 将文件置顶，也就是一直不过期，将日期设为0000-00-00
     */
    public function set_Top(){
        $id = Request::instance()->get('id',0);
        if ($id){
            $filebed_model = new filebed();
            $filebed_model->set_Top(array('id'=>$id));
            $this->success('已将文件置顶，永不过期');
        }else{
            $this->error('修改失败','');
        }
    }
}
