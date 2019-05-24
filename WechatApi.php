<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/4/23
 * Time: 18:58
 */
require_once("config.inc.php");
require_once("WechatReply.php");
require_once("common.php");

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
        if (time() > $expire_time){
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
            $expire_time = time()+3600;
            file_put_contents('./access_token.json', '{"access_token": "'.$access_token.'", "expire_time": '.$expire_time.'}');
        }
        return $access_token;
    }
    /**
     * 模拟浏览器发送 get post请求
     * @param string $url request url
     * @param string OUT $output json string output
     * @param string $post_data json string post data input
     * @param bool $vert_peer whether vertify client ca
     * @return bool
    */
    public static function http_curl($url,&$output,$post_data='',$vert_peer=false){

        $output="";
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
        if($post_data!=""){
            curl_setopt($ch,CURLOPT_POST,1);
            curl_setopt($ch,CURLOPT_POSTFIELDS,$post_data);
        }
        //返回值为json字符串
        $output =curl_exec($ch);
        $errno = curl_errno($ch);
        $error = curl_error($ch);
        curl_close($ch);
        if ($errno) {
            $output = json_encode(array('errno'=>$errno,'error'=>$error));
            return false;
        }
        return true;
    }
    public static function create_menu($menu)
    {
        $access_token = WechatApi::get_access_token();
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token;
        $output = "";
        $ret = WechatApi::http_curl($url,$output,$menu);
        echo $output;
    }
    //测试号未开通多客服功能
    public static function create_kf_account()
    {

    }
    /**
     * 获取永久素材总数
     * @return json string
     */
    public static function get_material_count(){
        $access_token = self::get_access_token();
        $url = "https://api.weixin.qq.com/cgi-bin/material/get_materialcount?access_token=".$access_token;
        $ret = self::http_curl($url,$datas);
        if (!$ret) $datas=json_encode(array());
        return  $datas;
    }
    /**
     * 获取永久素材列表
     * data 参数	是否必须	说明
    type	是	素材的类型，图片（image）、视频（video）、语音 （voice）、图文（news）
    offset	是	从全部素材的该偏移位置开始返回，0表示从第一个素材 返回
    count	是	返回素材的数量，取值在1到20之间,
     */
    public static function batchget_material($type,$offset=0,$count){
        $access_token =self::get_access_token();
        $url = "https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=".$access_token;
        $data = '{
					    "type":"'.$type.'",
					    "offset":"'.$offset.'",
					    "count":"'.$count.'"
					}';
        $output="";
        $ret = self::http_curl($url,$output,$data);
        if (!$ret) $output=json_encode(array());
        return  $output;
    }

    /**
     *
     *获取永久素材
     *参数	是否必须	说明
    access_token	是	调用接口凭证
    media_id	是	要获取的素材的media_id
    视频消息素材：
    {
    "title":TITLE,
    "description":DESCRIPTION,
    "down_url":DOWN_URL,
    }
     */
    public static function get_material($media_id){
        $access_token = self::get_access_token();
        $url = "https://api.weixin.qq.com/cgi-bin/material/get_material?access_token=".$access_token;
        $data = '{
					    "media_id":"'.$media_id.'"
					}';
        $output= "";
        $ret = self::http_curl($url,$output,$data);
        if (!$ret) $output=json_encode(array());
        return  $output;
    }
    /*
     *         WechatReply::sendMessage($GLOBALS['my_openid'],$filename);
        exit;
     * */
    public static function download_img($url = "", $filename = ""){
        $ret = WechatApi::http_curl($url,$file);
        $filename = pathinfo($filename, PATHINFO_BASENAME);
        $temp_dir = sys_get_temp_dir();
        $file_path = sprintf('%s%s.png',$temp_dir,$filename);
        if (IsWindows()) $file_path=str_replace('/','\\',$file_path);
        $resource = fopen($file_path, 'a');
        fwrite($resource, $file);
        fclose($resource);
        return $file;
    }
    /**
     * 上传图片永久素材
    */
    public static function upload_img($file_path)
    {
        $curl = curl_init();
        $url = "https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token=".self::get_access_token();
        if (class_exists('\CURLFile')) {
            curl_setopt($curl, CURLOPT_SAFE_UPLOAD, true);
            $data = array('file' => new \CURLFile(realpath($file_path)));//>=5.5
        } else {
            if (defined('CURLOPT_SAFE_UPLOAD')) {
                curl_setopt($curl, CURLOPT_SAFE_UPLOAD, false);
            }
            $data = array('file' => '@' . realpath($file_path));//<=5.5
        }
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1 );
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($curl, CURLOPT_USERAGENT,"TEST");
        $result = curl_exec($curl);
        if (curl_errno($curl)){
            $result = curl_error($curl);
            WechatReply::sendMessage($GLOBALS['my_openid'],"上传图片有误,msg:".$result.",code:".curl_errno($curl));
            curl_close($curl);
            exit;
        }
        curl_close($curl);
        $json_obj = json_decode($result);
        WechatReply::sendMessage($GLOBALS['my_openid'],"上传图片成功,url:".$json_obj->url);
        exit;
    }
    /**
     * 上传临时图片素材
    */
    public static function upload_timg($file_path)
    {
        $curl = curl_init();
        $url = "https://api.weixin.qq.com/cgi-bin/media/upload?access_token=".self::get_access_token()."&type=image";
        if (class_exists('\CURLFile')) {
            curl_setopt($curl, CURLOPT_SAFE_UPLOAD, true);
            $data = array('file' => new \CURLFile(realpath($file_path)));//>=5.5
        } else {
            if (defined('CURLOPT_SAFE_UPLOAD')) {
                curl_setopt($curl, CURLOPT_SAFE_UPLOAD, false);
            }
            $data = array('file' => '@' . realpath($file_path));//<=5.5
        }
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1 );
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($curl, CURLOPT_USERAGENT,"TEST");
        $result = curl_exec($curl);
        if (curl_errno($curl)){
            $result = curl_error($curl);
            WechatReply::sendMessage($GLOBALS['my_openid'],"上传图片有误,msg:".$result.",code:".curl_errno($curl));
            curl_close($curl);
            exit;
        }
        curl_close($curl);
        //返回值为json字符串，需通过json_decode解析为json对象
        $json_obj = json_decode($result);
        WechatReply::sendMessage($GLOBALS['my_openid'],"上传图片成功,media_id:".$json_obj->media_id);
    }
    /**
     * 更新微信用户分组
    */
    public static function update_user_group($openid){
        $access_token = self::get_access_token();
        $url = "https://api.weixin.qq.com/cgi-bin/groups/members/update?access_token=".$access_token;
        $data = '{"openid":"'.$openid.'","to_groupid":100}';
        $output = "";
        $ret = self::http_curl($url,$output,$data);
        if (!$ret) $output=json_encode(array());
        return  $output;
    }
}