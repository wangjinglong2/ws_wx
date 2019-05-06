<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/5/6
 * Time: 16:56
 */
require_once("config.inc.php");

$redirect_url = $GLOBALS['root_url']."Login.php";
$auth_url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$GLOBALS['app_id']."&redirect_uri=".$redirect_url."&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";

header("Location:".$auth_url);
?>
