<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/5/6
 * Time: 17:19
 */
require("config.inc.php");
require("JsSDK.php");

$code = isset($_GET['code'])?$_GET['code']:"";
$token_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$GLOBALS['app_id']."&secret=".$GLOBALS['app_secret']."&code=".$code."&grant_type=authorization_code";

$reslut = "";
$ret = WechatApi::http_curl($token_url,$reslut);
$js_data = json_decode($reslut,true);

$access_token = $js_data['access_token'];
$openid = $js_data['openid'];
$refsh_token = $js_data['refresh_token'];

$usr_url = "https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$openid."&lang=zh_CN";
$user_info = "";
$ret = WechatApi::http_curl($usr_url,$user_info);

$sdk = new JsSDK();
$sgn = $sdk->getSignPackage();
?>

<!DOCTYPE html>
<html>
<body>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
    <title>绑定用户</title>
</head>
<div class="container">
    <div class="page">
        <div class="page__hd"></div>
        <div class="page_bd">
            <div class="weui-cells weui-cells_form">
                <div class="weui-cells">
                    <div class="weui-cell__bd">
                        <label>用户名:</label>
                        <input class="weui-input" type="text" placeholder="请输入用户名" size="30">
                    </div>
                    <div class="weui-cell__bd">
                        <label>&nbsp;&nbsp;&nbsp;&nbsp;密码:</label>
                        <input class="weui-input" type="text" placeholder="请输入密码" size="30">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>
<script type="text/javascript" src="https://res.wx.qq.com/open/js/jweixin-1.3.2.js"></script>
<link type="stylesheet" href="./weui-master/dist/style/weui.min.css"></link>
<script>
    wx.config({
        debug: false,
        appId: '<?php echo $sgn['appId']?>',
        timestamp: <?php echo $sgn['timestamp']?>,
        nonceStr: '<?php echo $sgn['nonceStr']?>',
        signature: '<?php echo $sgn['signature']?>',
        jsApiList: [
            'checkJsApi',
            'onMenuShareTimeline',
            'onMenuShareAppMessage',
            'onMenuShareQQ',
            'onMenuShareWeibo',
            'onMenuShareQZone',
            'hideMenuItems',
            'showMenuItems',
            'hideAllNonBaseMenuItem',
            'showAllNonBaseMenuItem',
            'translateVoice',
            'startRecord',
            'stopRecord',
            'onVoiceRecordEnd',
            'playVoice',
            'onVoicePlayEnd',
            'pauseVoice',
            'stopVoice',
            'uploadVoice',
            'downloadVoice',
            'chooseImage',
            'previewImage',
            'uploadImage',
            'downloadImage',
            'getNetworkType',
            'openLocation',
            'getLocation',
            'hideOptionMenu',
            'showOptionMenu',
            'closeWindow',
            'scanQRCode',
            'chooseWXPay',
            'openProductSpecificView',
            'addCard',
            'chooseCard',
            'openCard'
        ]
    });
    wx.ready(function () {
        wx.checkJsApi({
            jsApiList: ['checkJsApi','openLocation'],
            success: function (res) {}
        });
    });
    wx.error(function(res){
        console.log(res);
    });
    // $('.btn2').click(function () {
    //     wx.openLocation({
    //         latitude: 22.545538,
    //         longitude: 114.054565,
    //         name: '这里填写位置名',
    //         address: '位置名的详情说明',
    //         scale: 10,
    //     });
    // })
</script>
</html>
