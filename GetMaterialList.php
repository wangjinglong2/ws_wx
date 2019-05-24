<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/5/22
 * Time: 19:12
 */
require "WechatApi.php";
/*
 *"voice_count":COUNT,
  "video_count":COUNT,
  "image_count":COUNT,
  "news_count":COUNT
*/
$act = isset($_GET['act'])?$_GET['act']:"";
if ($act=="") die("请输入参数act");
if($act == 'batch'){
    $temp_str = WechatApi::get_material_count();
    $get_materialcount = json_decode($temp_str);
    if (!isset($get_materialcount["news_count"])) die("获取素材数目失败");
    $news_count = $get_materialcount["news_count"];
    $offset = 0;
    $datas = array();
    if($news_count >= 20){
        $p = ceil($news_count/20);
        for($i = 0; $i < $p; $i++){
            $offset = $i * 20;
            $datas[] = json_decode(WechatApi::batchget_material($type,$offset, 20),true);
        }
    }else{
        $datas[] = json_decode(WechatApi::batchget_material($type,$offset, 20));
    }
    echo json_encode($datas);
}else if($act == 'one'){
    $media_id = isset($_GET['media_id'])?$_GET['media_id']:'';
    if($media_id=="") die("获取的media_id为空");
    $get_material = WechatApi::get_material($media_id);
    echo json_encode($get_material);
}
?>
