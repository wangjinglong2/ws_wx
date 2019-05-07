<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/4/23
 * Time: 18:58
 */
require_once("config.inc.php");

class WechatApi
{
    public function __construct()
    {
    }
    public static function get_access_token()
    {
        $token_file = "./access_token.json";
        $expire_time = 0;
        if (file_exists($token_file)){
            $res = file_get_contents('./access_token.json');
            $result = json_decode($res, true);
            $expire_time = $result["expire_time"];
            $access_token = $result["access_token"];
        }
        if (time() > ($expire_time + 3600)){
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$GLOBALS['app_id']."&secret=".$GLOBALS['app_secret'];
            $ch = curl_init();
            curl_setopt($ch,CURLOPT_URL,$url);
            curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
            curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
            $res = curl_exec($ch);
            if (curl_errno($ch)) {
                return curl_error($ch);
            }
            curl_close($ch);
            $result = json_decode($res, true);
            $access_token = $result["access_token"];
            $expire_time = time();
            file_put_contents('./access_token.json', '{"access_token": "'.$access_token.'", "expire_time": '.$expire_time.'}');
        }
        return $access_token;
    }
    public static function http_curl($url,&$output,$type='get',$arr='',$vert_peer=false){

        //1.初始化curl
        $ch  =curl_init();

        //2.设置curl的参数
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_ENCODING,"");
        curl_setopt($ch,CURLOPT_MAXREDIRS,10);
        curl_setopt($ch,CURLOPT_TIMEOUT,30);
        curl_setopt($ch,CURLOPT_HTTP_VERSION,CURL_HTTP_VERSION_1_1);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,$vert_peer);
//        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
        if($type == 'post'){
            curl_setopt($ch,CURLOPT_POST,1);
            curl_setopt($ch,CURLOPT_POSTFIELDS,$arr);
        }
        $output =curl_exec($ch);
        $errno = curl_errno($ch);
        $error = curl_error($ch);
        curl_close($ch);
        if ($errno) {
            $output = $error;
            return false;
        }
        return true;
    }
    public static function create_menu($menu)
    {
        $access_token = WechatApi::get_access_token();
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token;
        $output = "";
        $ret = WechatApi::http_curl($url,$output,"post",$menu,false);
        echo $output;
    }
}