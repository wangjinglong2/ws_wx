<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/5/17
 * Time: 13:08
 */

$user = isset($_POST['user'])?$_POST['user']:"";
$pwd  = isset($_POST['passwd'])?$_POST['passwd']:"";
$openid = isset($_POST['openid'])?$_POST['openid']:"";
$user_info = isset($_POST['user_info'])?$_POST['user_info']:"";

if ($openid==""){
    echo json_encode(array('code'=>0,'msg'=>'用户openid为空'));
    exit;
}

?>
