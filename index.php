<?php

require("Wechat.php");
$wechat = new Wechat();
//echo "test"
if(!isset($_GET['echostr'])) $wechat->responseMsg();
else $wechat->valid();

?>