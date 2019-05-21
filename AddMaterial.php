<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/5/22
 * Time: 18:32
 */
require_once "config.inc.php";
global $db;
$query = "select title,thumb_media_id,author,digest,show_cover_pic,content,content_source_url from XT_WxMaterial";
$res = $db->query($query);
if ($db->errno) die("查询失败".$db->error);
$datas = array();
while($row = $res->fetch_array()){
    $data = array();
    $data['title']=$row['title'];
    $data['thumb_media_id']=$row['thumb_media_id'];
    $data['author']=$row['author'];
    $data['digest']=$row['digest'];
    $data['show_cover_pic']=$row['show_cover_pic'];
    $data['content']=$row['content'];
    $data['content_source_url']=$row['content_source_url'];
}
/*
 * {
    "articles": [{
     "title": TITLE,
    "thumb_media_id": THUMB_MEDIA_ID,
    "author": AUTHOR,
    "digest": DIGEST,
    "show_cover_pic": SHOW_COVER_PIC(0 / 1),
    "content": CONTENT,
    "content_source_url": CONTENT_SOURCE_URL,
    "need_open_comment":1,
    "only_fans_can_comment":1
},
    //若新增的是多图文素材，则此处应还有几段articles结构
]
}
 *
 * */
?>
