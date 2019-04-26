<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/4/23
 * Time: 18:58
 */

class WechatApi
{
    public static $appId = "wx7862699af0335b9a";
    public static $appSecret = "509a0ca98eead7b13bcf36a0419a9c5a";
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
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".self::$appId."&secret=".self::$appSecret;
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
    public function http_curl($url,$type='get',$res='json',$arr=''){

        //1.初始化curl
        $ch  =curl_init();

        //2.设置curl的参数
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

        if($type == 'post'){
            curl_setopt($ch,CURLOPT_POST,1);
            curl_setopt($ch,CURLOPT_POSTFIELDS,$arr);
        }
        //3.采集
        $output =curl_exec($ch);

        //4.关闭
        curl_close($ch);
        if($output =='json'){
            if(curl_error($ch)){
                //请求失败，返回错误信息
                return curl_error($ch);
            }else{
                //请求成功，返回错误信息

                return json_decode($output,true);
            }
        }
        echo var_dump( $output );
    }
}