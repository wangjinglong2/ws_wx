<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/4/26
 * Time: 14:55
 */
require('WechatReply.php');
require('config.inc.php');

define('TOKEN','hhy1314');

class Wechat
{
    public function __construct()
    {
    }
    //入口验证
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }
    public function responseMsg()
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if (!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            switch ($postObj->MsgType){
                case 'event':
                    if ($postObj->Event == "subscribe"){
                        $content = "欢迎关注我们的微信公众账号[".$postObj->FromUserName."],".$postObj->ToUserName;
                        echo WechatReply::replyText($postObj,$content);
                    }
                    break;
                case 'text':
                    $content = '你好啊，屌丝';
                    echo WechatReply::replyText($postObj,$content);
                    break;
                case 'image':
                    echo WechatReply::replyImage($postObj,$postObj->MediaId);
                    break;
                case 'voice':
                    echo WechatReply::replyVoice($postObj,$postObj->MediaId);
                    break;
                default:
                    $contentStr = '未知的消息类型';
                    echo WechatReply::replyText($postObj,$contentStr);
                    break;
            }
        }else echo "debug";
    }
    public static function createMenu()
    {
        $access_token = WechatApi::get_access_token();
        $menu ='{
             "button":[
                {
                   "name":"测试",
                   "sub_button":[
                   {
                        "type":"miniprogram",
                        "name":"万年历",
                        "url":"http://mp.weixin.qq.com",
                        "appid":"wx286b93c14bbf93aa",
                        "pagepath":"pages/lunar/index"
                    },{
                        "type":"click",
                        "name":"联系客服",
                        "key":"lianxikf"
                  },{
                        "type":"view",
                        "name":"绑定用户",
                        "url":'.$root_url.'login.php
                  }]
               },{
                    "name":"状态",
                    "sub_button":[
                    {
                        "type":"view",
                        "name":"从库延迟",
                        "url":"http://www.baidu.com"
                    },{
                        "type":"view",
                        "name":"TPS",
                        "url":"http://www.baidu.com"
                    },{
                        "type":"view",
                        "name":"连接数",
                        "url":"http://www.baidu.com"
                    }]          
               },{
                    "name":"管理",
                    "sub_button":[
                    {
                        "type":"view",
                        "name":"登陆",
                        "url":"http://www.baidu.com"
                    },{
                        "type":"view",
                        "name":"停止/启动复制",
                        "url":"http://www.baidu.com"
                    },{
                        "type":"view",
                        "name":"主库只读/取消",
                        "url":"http://www.baidu.com"
                    }]  
               }]
            }';
            $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token;
            $ret_str = WechatApi::http_curl($url,"post","json",$menu);
            echo $ret_str;
    }
    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $token =TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
}