<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/4/26
 * Time: 15:05
 */

class WechatReply
{
    public function __construct()
    {
    }
    //发送文本消息
    public static function replyText($obj,$content){
        $replyXml = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[text]]></MsgType>
						<Content><![CDATA[%s]]></Content>
						</xml>";
        $resultStr = sprintf($replyXml,$obj->FromUserName,$obj->ToUserName,time(),$content);
        return $resultStr;
    }
    //发送图片消息
    public static function replyImage($obj,$mediaID){
        $replyXml = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[image]]></MsgType>
						<Image>
						<MediaId><![CDATA[%s]]></MediaId>
						</Image>
						</xml>";
        $resultStr = sprintf($replyXml,$obj->FromUserName,$obj->ToUserName,time(),$mediaID);
        return $resultStr;
    }
    //回复语音消息
    public static function replyVoice($obj,$MediaId)
    {
        $replyXml = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[voice]]></MsgType>
						<Voice>
						<MediaId><![CDATA[%s]]></MediaId>
						</Voice>
						</xml>";
        $resultStr = sprintf($replyXml,$obj->FromUserName,$obj->ToUserName,time(),$MediaId);
        return $resultStr;
    }
    //回复视频消息
    public static function replyVideo($obj,$mediaID){
        $replyXml = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[video]]></MsgType>
						<Video>
						<MediaId><![CDATA[%s]]></MediaId>
						</Video>
						</xml>";
        //返回一个进行xml数据包

        $resultStr = sprintf($replyXml,$obj->FromUserName,$obj->ToUserName,time(),$mediaID);
        return $resultStr;
    }
    //回复音乐消息
    public static function  replyMusic($obj,$musicArr)
    {
        $replyXml = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[music]]></MsgType>
						<Music>
						<Title><![CDATA[%s]]></Title>
						<Description><![CDATA[%s]]></Description>
						<MusicUrl><![CDATA[%s]]></MusicUrl>
						<HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
						<ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
						</Music>
						</xml>";
        //返回一个进行xml数据包

        $resultStr = sprintf($replyXml,$obj->FromUserName,$obj->ToUserName,time(),$musicArr['Title'],$musicArr['Description'],$musicArr['MusicUrl'],$musicArr['HQMusicUrl'],$musicArr['ThumbMediaId']);
        return $resultStr;
    }
    //回复图文消息
    public static function replyNews($obj,$newsArr){
        $itemStr = "";
        if(is_array($newsArr))
        {
            foreach($newsArr as $item)
            {
                $itemXml ="<item>
						<Title><![CDATA[%s]]></Title>
						<Description><![CDATA[%s]]></Description>
						<PicUrl><![CDATA[%s]]></PicUrl>
						<Url><![CDATA[%s]]></Url>
						</item>";
                $itemStr .= sprintf($itemXml,$item['Title'],$item['Description'],$item['PicUrl'],$item['Url']);
            }

        }

        $replyXml = "<xml>
		<ToUserName><![CDATA[%s]]></ToUserName>
		<FromUserName><![CDATA[%s]]></FromUserName>
		<CreateTime>%s</CreateTime>
		<MsgType><![CDATA[news]]></MsgType>
		<ArticleCount>%s</ArticleCount>
		<Articles>
		{$itemStr}
		</Articles>
		</xml> ";
        //返回一个进行xml数据包

        $resultStr = sprintf($replyXml,$obj->FromUserName,$obj->ToUserName,time(),count($newsArr));
        return $resultStr;
    }
}