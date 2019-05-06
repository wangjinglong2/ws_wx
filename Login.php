<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/5/6
 * Time: 17:19
 */
require("config.inc.php");

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.weixin.qq.com/sns/userinfo?access_token=21_xsPwVyZtHGLcVsxyI6g-VwYKXOINEoeRi0KypRD1Oml_jDzUSdpbxpLkjS9d121HIXoL0TKv-66s2IvufmhX1zYteAcY94DAmjMIeOJQ2aY&openid=oKzdw1cb2uh3Ow5Wd-M9OMB2ObZw",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_POSTFIELDS => "",
    CURLOPT_SSL_VERIFYPEER=>false,
    CURLOPT_SSL_VERIFYHOST=>false,
    CURLOPT_HTTPHEADER => array(
        "Accept: */*",
        "Cache-Control: no-cache",
        "Connection: keep-alive",
        "Host: api.weixin.qq.com",
        "Postman-Token: 86fd9dac-e7e9-4955-a68b-91c2475e18cd,94f5a93b-0ba3-430d-a35b-a124268c7fa0",
        "User-Agent: PostmanRuntime/7.11.0",
        "accept-encoding: gzip, deflate",
        "cache-control: no-cache"
    ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
} else {
    echo $response;
}

//$code = isset($_GET['code'])?$_GET['code']:"";
//$token_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$GLOBALS['app_id']."&secret=".$GLOBALS['app_secret']."&code=".$code."&grant_type=authorization_code";
//
//$reslut = WechatApi::http_curl($token_url);
//$js_data = json_decode($reslut);
//
//$access_token = $js_data['access_token'];
//$openid = $js_data['openid'];
//$refsh_token = $js_data['refresh_token'];
//die($openid);
//$refresh_url = "https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=".$GLOBALS['app_id']."&grant_type=refresh_token&refresh_token=$refsh_token";
//$result = WechatApi::http_curl($refresh_url);
//$js_data = json_decode($reslut);
//$access_token = $js_data['access_token'];
//$openid = $js_data['openid'];
//$access_token = "21_4tv8aIC4AdnX0O2yjx4OkB2W3bBENqkxGEgfeNF73UyKeMyQnFXaTcucS7zIfz1z_-QVapwGzHGCzta3_jjpdUVZiPaF2K1bFnjD4X_KbEo";
//$openid = "oKzdw1cb2uh3Ow5Wd-M9OMB2ObZw";
//$usr_url = "https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$openid."&lang=zh_CN";
//$user_info = WechatApi::http_curl($usr_url);
//echo $user_info;
?>
