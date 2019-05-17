<?php
require_once("config.inc.php");

class JsSDK
{
    public function __construct()
    {

    }
    public function getSignPackage()
    {
        $jsapiTicket = $this->getJsApiTicket();

        // 注意 URL 一定要动态获取，不能 hardcode.
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $timestamp = time();
        $nonceStr = $this->createNonceStr();

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
        $signature = sha1($string);
        $signPackage = array(
          "appId"     => $GLOBALS['app_id'],
          "nonceStr"  => $nonceStr,
          "timestamp" => $timestamp,
          "url"       => $url,
          "signature" => $signature,
          "rawString" => $string
        );
        return $signPackage;
    }
    private function createNonceStr($length = 16)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
          $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }
    private function getJsApiTicket()
    {
        $tkt_path = "./jsapi_ticket.json";
        $res = file_get_contents($tkt_path);
        $result = json_decode($res, true);
        $expire_time = $result["expire_time"];
        $jsapi_ticket = $result["ticket"];
        if (time() > $expire_time){
            $accessToken = WechatApi::get_access_token();
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
            $ret = WechatApi::http_curl($url,$res);
            $result = json_decode($res, true);
            $jsapi_ticket = $result["ticket"];
            $expire_time = time()+3600;
            file_put_contents($tkt_path, '{"ticket": "'.$jsapi_ticket.'", "expire_time": '.$expire_time.'}');
        }
        return $jsapi_ticket;
    }
}

