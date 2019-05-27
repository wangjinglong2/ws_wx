<?php
defined('TOKEN') or define('TOKEN','hhy1314');

$root_url = "http://dbmonitor.natapp1.cc/ws_wx/";
$app_id = "wx7862699af0335b9a";
$app_secret = "509a0ca98eead7b13bcf36a0419a9c5a";
$my_openid = "oKzdw1cb2uh3Ow5Wd-M9OMB2ObZw";

//$db_host = "192.168.0.71";
//$db_user = "wjl";
//$db_pwd = "wjl123456WJL";
//$db_name = "ws_wx";
//$db_port = 3306;

$db_host = "localhost";
$db_user = "root";
$db_pwd = "";
$db_name = "OrderMan";
$db_port = 3306;

$db = new mysqli($db_host,$db_user,$db_pwd,$db_name,$db_port);
if (mysqli_connect_errno()) die("数据库连接失败,msg:".mysqli_connect_error()."errno:".mysqli_connect_errno());
?>

