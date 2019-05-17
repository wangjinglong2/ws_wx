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
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>
    <link rel="stylesheet" href="../ws_wx/weui-master/dist/style/weui.css"></link>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
    <title>绑定用户</title>
</head>
<div class="container" align="center">
    <div class="weui-cell weui-cell_vcode">
        <div class="weui-cell__hd">
            <label class="weui-label">帐号:</label>
        </div>
        <div class="weui-cell__bd">
            <input class="weui-input" id="user" type="text" name="user" placeholder="请输入订单系统帐号">
        </div>

    </div>
    <div class="weui-cell weui-cell_vcode">
        <div class="weui-cell__hd">
            <label class="weui-label">密码:</label>
        </div>
        <div class="weui-cell__bd">
            <input class="weui-input" id="passwd" type="password" name="passwd"  placeholder="请输入密码">
        </div>

    </div>
    <div class="button-sp-area">
        <a href="javascript:;" class="weui-btn weui-btn_block weui-btn_primary" onclick="bind_user()">确定</a>
    </div>
</div>
</body>
<script>
    function bind_user()
    {
        var user = $('#user').val();
        if (user==""){
            alert('请输入账号');
            $('#user').focus();
            return;
        }
        var pwd = $('#passwd').val();
        if (pwd == ""){
            alert('请输入密码');
            $('#passwd').focus();
            return;
        }
        var openid = "<?php echo $openid; ?>" ;
        var user_info = <?php echo $user_info; ?>;
        $.ajax({
            type: "post",
            url: "Login2.php",
            dataType: "json",
            data: {'user':user,'passwd':pwd,'openid':openid,'user_info':user_info},
            success: function(msg){
                alert(msg.info);
                if(msg.state==1){
                    WeixinJSBridge.call('closeWindow');
                }
            }
        });
    }
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
