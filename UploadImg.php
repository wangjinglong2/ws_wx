<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/5/22
 * Time: 19:08
 */
require "WechatApi.php";

$path = "C:\wamp64\www\ws_wx\img\cover.jpg";
WechatApi::upload_img($path);
?>
