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

    //回复多客服消息
    public static function transmitService($object)
    {
        $xmlTpl = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[transfer_customer_service]]></MsgType>
						</xml>";
        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    //客服接口发信息
    public static function sendMessage($openid,$message){
        $access_token = WechatApi::get_access_token();
        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$access_token;
        $data = '{
					    "touser":"'.$openid.'",
					    "msgtype":"text",
					    "text":
					    {
					         "content":"'.$message.'"
					    }
					}';
        $ret = WechatApi::http_curl($url,$output,'post',$data);
        return json_decode($output,true);
    }

    //模版接口发信息
    public static function sendTemplateMessage($message,$openid,$pdate){
        $access_token = WechatApi::get_access_token();
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token;
        $data = '{
			           "touser":"'.$openid.'",
			           "template_id":"QP_BJ3qCOV8ZSjPDLmRpf4Ys6OPNET72P2Ed8clMxWE",
			           "url":"",            
			           "data":{
			                   "status":{
			                       "value":"新消息",
			                       "color":"#173177"
			                   },
			                   "cur_date": {
			                       "value":"'.$pdate.'",
			                       "color":"#173177"
			                   },
			                   "content": {
			                       "value":"'.$message.'",
			                       "color":"#173177"
			                   }
			           }
			       }';
        $ret = WechatApi::http_curl($url,$output,'post',$data);
        return  json_decode($output,true);;
    }
    //获取客服列表
    function getkfopenids(){
       // include "cmysqli.php";
        $aa = '';
//        $sql = "SELECT openid FROM XT_WxUser WHERE wxkf=1 and flag =1 ";
//        if ($stmt = $db->prepare ( $sql )) {
//            // $stmt->bind_param('s', 'oi4yrs9hoa_2BIFamBkNsTwU7ILg');
//            $stmt->execute ();
//            $stmt->store_result ();
//            $stmt->bind_result ( $openid );
//
//            //$i = 0;
//            while ( $stmt->fetch () ) {
//                //$aa [$i] ['openid'] = $openid;
//                $aa[] = $openid;
//                //$i ++;
//            }
//            $stmt->close ();
//            $mysqli->close ();
//        } else {
//            die ( $mysqli->error );
//        }
        return $aa;
    }
}