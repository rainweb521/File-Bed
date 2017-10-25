<?php
/**
 * Created by PhpStorm.
 * User: Rain
 * Date: 2017/10/24
 * Time: 18:01
 */
namespace app\config\model;

use phpDocumentor\Reflection\Types\Null_;
use think\Model;

class filebed extends Model{
    /**
     * 主键默认自动识别
     */
//    protected $pk = 'uid';
// 设置当前模型对应的完整数据表名称
    protected $table = 'filebed';
    public function get_filebedInfo($where=null){
        $data = filebed::where($where)->find();
        return $data->getData();
    }
    public function insert_filebedInfo($data){
        filebed::save($data);
    }
    public function get_filebedList($where=null){
        $list = filebed::where($where)->select();
        return $list;
    }
    public function get_filebedIndex_List($where=null){
        $where['state'] = 1;
        $list = filebed::where($where)->select();
        return $list;
    }
    public function delete_filebedInfo($id){
//        filebed::delete(array('id'=>$id));
        $data = $this->get_filebedInfo(array('id'=>$id));
        $data['state'] = 3;
        $data['out_time'] = date('Y-m-d');
//        var_dump($data);
//        exit();
        filebed::save($data,['id'=>$id]);
    }
    /**
     * 给文件添加有效期，默认为 7 天
     */
    public function set_Out_time($where,$day){
        $data = $this->get_filebedInfo($where);
        $data['state'] = 1;
        $data['out_time'] = date("Y-m-d", strtotime('+'.$day." day ".$data['out_time']));
        filebed::save($data,$where);
    }
    /**
     * 将文件设置为过期，但不删除文件
     */
    public function set_Out($where){
        $data = $this->get_filebedInfo($where);
        $data['state'] = 0;
        $data['out_time'] = date('Y-m-d');
        filebed::save($data,$where);
    }
    /**
     * 将文件置顶，也就是一直不过期，将日期设为0000-00-00
     */
    public function set_Top($where){
        $data = $this->get_filebedInfo($where);
        $data['out_time'] = '0000-00-00';
        $data['state'] = 1;
        filebed::save($data,$where);
    }
}