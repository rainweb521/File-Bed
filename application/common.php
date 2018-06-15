<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
use think\Request;
use think\Session;
use think\Controller;
/**
 * file_get_contens函数，用作所有图片的处理，应该是返回图片内容，还有URL的请求发起
 */
//function file_get_contents($filename, $incpath = false, $resource_context = null) {
//    if (false === $fh = fopen($filename, 'rb', $incpath)) {
//        user_error('file_get_contents() failed to open stream: No such file or directory',
//            E_USER_WARNING);
//        return false;
//    }
//    clearstatcache();
//    if ($fsize = @filesize($filename)) {
//        $data = fread($fh, $fsize);
//    }
//    else {
//        $data = '';
//        while (!feof($fh)) {
//            $data .= fread($fh, 8192);
//        }
//    }
//    fclose($fh);
//    return $data;
//}
function str_handling($text){
    $text = str_replace(" ", "", $text);
    $text = str_replace("\n", "", $text);
    $text = str_replace("\r", "", $text);
    $text = htmlspecialchars( $text);
    return $text;
}




/** 上传图片函数，还可以对图片格式，大小进行验证， 并对图片进行压缩
 * @param $name
 * @return int|string
 */
function upload_image($name){
    $image = \request()->file($name);
    if($image){
        /**
         * //判断图片文件的格式
         * 判断图片大小 最大为10mb
         */
        $info = $image->validate(['size'=>10000000,'ext'=>'jpg,png,jpeg'])->move(ROOT_PATH . 'public' . DS . 'uploads');
//             echo $info->getSize();
//        $info->getExtension()   //判断图片文件的格式
//        $info->getSize()>10000000 //判断图片文件的格式
        if($info){
            $image_src = '/public/uploads/'.$info->getSaveName();
            image_png_size_add('.'.$image_src,'.'.$image_src);
            return $image_src;
        }else{
            return 0;
        }
    }else{
        return 0;
    }
}
function upload_photo($name){
    $image = \request()->file($name);
    if($image){
        /**
         * //判断图片文件的格式
         * 判断图片大小 最大为1mb
         */
        $info = $image->validate(['size'=>1000000,'ext'=>'jpg,png,jpeg'])->move(ROOT_PATH . 'public' . DS . 'photo');
//             echo $info->getSize();
//        $info->getExtension()   //判断图片文件的格式
//        $info->getSize()>10000000 //判断图片文件的格式
        if($info){
            $image_src = '/public/photo/'.$info->getSaveName();
            image_png_size_add('.'.$image_src,'.'.$image_src);
            return $image_src;
        }else{
            return 0;
        }
    }else{
        return 0;
    }
}
/**
 * desription 压缩图片
 * @param sting $imgsrc 图片路径
 * @param string $imgdst 压缩后保存路径
 */
function image_png_size_add($imgsrc,$imgdst){
    list($width,$height,$type)=getimagesize($imgsrc);
    $new_width = ($width)*0.7;
    $new_height =($height)*0.7;
//           $new_width = ($width>600?600:$width)*0.9;
//           $new_height =($height>600?600:$height)*0.9;
    switch($type){
        case 1:
            $giftype=check_gifcartoon($imgsrc);
            if($giftype){
//                       header('Content-Type:image/gif');
                $image_wp=imagecreatetruecolor($new_width, $new_height);
                $image = imagecreatefromgif($imgsrc);
                imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                imagejpeg($image_wp, $imgdst,75);
                imagedestroy($image_wp);
            }
            break;
        case 2:
//                   header('Content-Type:image/jpg');
            $image_wp=imagecreatetruecolor($new_width, $new_height);
            $image = imagecreatefromjpeg($imgsrc);
            imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
            imagejpeg($image_wp, $imgdst,75);
            imagedestroy($image_wp);
            break;
        case 3:
//                   header('Content-Type:image/png');
            $image_wp=imagecreatetruecolor($new_width, $new_height);
            $image = imagecreatefrompng($imgsrc);
            imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
            imagejpeg($image_wp, $imgdst,75);
            imagedestroy($image_wp);
            break;
    }
}
?>