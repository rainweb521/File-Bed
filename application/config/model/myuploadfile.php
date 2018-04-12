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

class myuploadfile extends Model{
    /**
     * 主键默认自动识别
     */
//    protected $pk = 'uid';
// 设置当前模型对应的完整数据表名称
    protected $table = 'myuploadfile';
    public function get_myuploadfileInfo($where=null){
        $data = myuploadfile::where($where)->find();
        return $data->getData();
    }
    public function insert_myuploadfileInfo($data){
        $data['add_time'] = date('Y-m-d');
        myuploadfile::save($data);
    }
    public function get_myuploadfileList($where=null){
        $list = myuploadfile::where($where)->select();
        return $list;
    }

}