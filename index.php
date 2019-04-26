<?php

require("Wechat.php");
$wechat = new Wechat();

if(!isset($_GET['echostr'])) $wechat->responseMsg();
else $wechat->valid();

?>