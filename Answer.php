<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/5/23
 * Time: 13:07
 */
require_once "config.inc.php";
global $db;

$id = isset($_GET['id'])?$_GET['id']:"";
if ($id=="") die("ID为空");
$sql = "select question,answer from XT_WxWenDa where id=?";
$statement = $db->prepare($sql);
$statement->bind_param('i',$id);
$statement->execute();
$statement->store_result();
$statement->bind_result($question,$answer);
$statement->fetch();
$statement->close();
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0,viewport-fit=cover">
    <script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>
    <link rel="stylesheet" href="../ws_wx/weui-master/dist/style/weui.css"/>
    <style type="text/css">
        .weui-search-bar__box{
            height:5%;
        }
    </style>
    <title>维尚订单服务中心</title>

</head>
<body>

<div class="page">
    <div class="page__hd">
    </div>
    <div class="page__bd">
        <div class="weui-panel weui-panel_access">
            <div class="weui-panel__bd">
            <h1><?php echo $question;?></h1>
                <p><?php echo $answer;?></p>
        </div>
    </div>
</div>
</body>

