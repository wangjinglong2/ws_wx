<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/4/26
 * Time: 14:55
 */
require('WechatReply.php');
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
                case 'text':
                    $contentStr = '你好啊，屌丝';
                    $replyXml = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[text]]></MsgType>
						<Content><![CDATA[%s]]></Content>
						</xml>";
                    $resultStr = sprintf($replyXml,$postObj->FromUserName,$postObj->ToUserName,time(),$contentStr);
                    echo $resultStr;
                    break;
                case 'image':
                    //echo WechatReply::replyImage($postObj,$postObj->MediaId);
                    break;
                case 'voice':
                    //echo WechatReply::replyVoice($postObj,$postObj->MediaId);
                    break;
                default:
                    $contentStr = '未知的消息类型';
                    //echo WechatReply::replyText($postObj,$contentStr);
                    break;
            }
        }else echo "debug";
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