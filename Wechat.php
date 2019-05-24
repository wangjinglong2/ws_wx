<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/4/26
 * Time: 14:55
 */
require_once('WechatReply.php');
require_once('config.inc.php');
require_once('WechatApi.php');

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
                    $this->receiveEvent($postObj);
                    break;
                case 'text':
                    $this->receiveText($postObj);
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
    //接收文本消息
    public function receiveText($obj){
        //获取文本消息的内容
        $content = $obj->Content;
        $openid = $obj->FromUserName;
        global $db;
        //判断是不是绑定用户，绑定的回复不同信息，转多客服
        $sql ="SELECT a.XtUser,a.openid FROM XT_Wx2Xt a WHERE a.openid=? ";
        $state = '0';
        $error = '';
        if($stmt = $db->prepare($sql))
        {
            $stmt->bind_param ( 's', $openid );
            $stmt->execute ();
            $stmt->store_result ();
            $stmt->bind_result ( $XtUser, $openid );
            if ($stmt->fetch ()) {
                $state = 1;
             } else {
                $state = 2;
            }
            $stmt->close ();
        }else{
            $state = 3;
        }
        if($state == 1){
            //查询XT_WXSet 看是否有限制日期
            //中午12点-13.30
            //下午17.30-第二天的早上8.00
            //$content 现在为非工作时间，请在工作时间(周一到周六：8:00-12:00,13:30-17:30，节假日除外)内咨询，谢谢。
            $sql ="SELECT a.nonworkdays,a.comment FROM XT_WxSet a WHERE a.state=? and a.nonworkdays=?";
            $states = 0;
            $sta = 0 ;
            $cur_date = date("Y-m-d");
            $pdate = date("Y-m-d H:i:s");
            if($stmt = $db->prepare($sql))
            {
                $stmt->bind_param ( 'sss', $states, $cur_date );
                $stmt->execute ();
                $stmt->store_result ();
                $stmt->bind_result ( $nonworkdays, $comment );
                if ($stmt->fetch ()) {
                    $sta = 1 ;
                } else {
                    $sta = 2 ;
                }
                $stmt->close ();
            }else{
                $content = "错误：".$db->error;
                $result = WechatReply::replyText( $obj, $content );
                $db->error;
            }

            $d1 = date("H:i:s");
            if( ($d1 >= '00:00:00' && $d1 <= '08:00:00') || ($d1 >= '12:00:00' && $d1<= '13:30:00') || ($d1 >= '17:30:00' && $d1< '24:00:00')){
                $sta = 3 ;
            }
            if ($content == "时间" || $content == "测试") {
                $content = date ( "Y-m-d H:i:s", time () );
                $opid = "oKzdw1cb2uh3Ow5Wd-M9OMB2ObZw";
                $mymess = WechatReply::sendMessage($opid, "有1个客服请求待接入" );
                $result = WechatReply::replyText($obj,json_encode($mymess) );
                if($mymess['errcode']!=0){
                    $pdate = date("Y-m-d H:i:s");
                    $mymess =WechatReply::sendTemplateMessage("有1个客服请求待接入" ,$opid ,$pdate );
                }
                $result = WechatReply::replyText($obj, $content );
            }else if (strstr ( $content, "1" ) || strstr ( $content, "nihao" ) || strstr ( $content, "您好" ) || strstr ( $content, "你好" ) || strstr ( $content, "在吗" ) || strstr ( $content, "有人吗" ) || strstr ( $content, "售后咨询" ) || strstr ( $content, "工艺咨询" ) || strstr ( $content, "价格咨询" ) || strstr ( $content, "订单进度咨询" )|| strstr ( $content, "非标床垫与沙发申请" )|| strstr ( $content, "物流发货咨询" )|| strstr ( $content, "服务投诉" )|| strstr ( $content, "10天加急申请" )||  ( $content == "2" )||  ( $content == "3" )||  ( $content == "4" )||  ( $content == "5" )||  ( $content == "6" )||  ( $content == "7" ) ) {
                if($sta == 1 ){
                    //有设置自定义回复
                    $content = strlen($comment)>0?$comment:"您好，\n现在是休息时间，请在工作时间内进行人工咨询，谢谢理解与支持。";
                    $result = WechatReply::replyText( $obj, $content );
                }else if($sta == 3){
                    $content = "现在为非工作时间，请在工作时间(周一到周六：8:00-12:00,13:30-17:30，节假日除外)内咨询，谢谢。";
                    $result = WechatReply::replyText( $obj, $content );
                }else{
                    // 触发多客服模式
                    $result = WechatReply::transmitService ( $obj );
                }
            } else {
                $content = "欢迎使用维尚订单中心客服系统，请根据提示发送信息接入相应客服：\n1、售后咨询\n2、工艺咨询\n3、订单进度咨询\n4、非标床垫与沙发申请\n5、物流发货咨询\n6、10天加急申请\n7、服务投诉\n（下次使用时可直接发送以上指令接入相应客服）\n";
                $result = WechatReply::replyText( $obj, $content );
            }

        }else{
            $content = "欢迎关注WS订单系统\n请先绑定订单系统帐号";
            $result =  WechatReply::replyText($obj,$content);
        }
        echo $result;exit;
    }
    public function receiveEvent($obj)
    {
        global $db;
        switch ($obj->Event) {
            case 'subscribe':
                $openid = $obj->FromUserName;
                $access_token = WechatApi::get_access_token();

                $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=$openid&lang=zh_CN";
                $result = "";
                $ret = WechatApi::http_curl($url,$result);
                $userinfo = json_decode($result,true);
                $nickname = $userinfo["nickname"];
                $sex = $userinfo["sex"];
                $city = $userinfo["city"];
                $province = $userinfo['province'];
                $country = $userinfo['country'];
                $headimgUrl = $userinfo['headimgurl'];
                $subscribe_scene = $userinfo['subscribe_scene'];
                $subtime = $userinfo["subscribe_time"];
                $comment = '';
                $flag1 = 0;
                //先判断该openid是否已经存在 如果存在且flag=3 把flag改为1,自动分组	,如果flag=2  把flag改为0	如果没有，就插入
                $sqla = "SELECT openid,flag FROM XT_WxUser WHERE openid=? and flag in (2,3)";
                if($stmt = $db->prepare($sqla))
                {
                    $stmt->bind_param('s', $openid);
                    $stmt->execute();
                    $stmt->store_result();
                    $stmt->bind_result($openid1,$flag);
                    if($stmt->fetch()){
                        if($flag==3){
                            $sql ="UPDATE XT_WxUser a SET a.flag=1,nickname=?,sex=?,headimgurl=?,city=?,province=?,country=?,subscribe_time=?,comment=?,subscribe_scene=? WHERE a.openid=? ";
                            $res = WechatApi::update_user_group($openid);
                        }else {
                            $sql ="UPDATE XT_WxUser a SET a.flag=0,nickname=?,sex=?,headimgurl=?,city=?,province=?,country=?,subscribe_time=?,comment=?,subscribe_scene=? WHERE a.openid=? ";
                        }
                        $stmt = $db->prepare($sql);
                        $stmt->bind_param('ssssssssss', $nickname,$sex,$headimgUrl,$city,$province,$country,$subtime,$comment,$subscribe_scene,$openid);
                        $myres = $stmt->execute();
                    }else{
                        $sql = "insert into XT_WxUser(openid,nickname,sex,headimgurl,city,province,country,subscribe_time,comment,flag,subscribe_scene) value(?,?,?,?,?,?,?,?,?,?,?)";
                        $stmt = $db->prepare($sql);
                        $stmt->bind_param('sssssssssss', $openid,$nickname,$sex,$headimgUrl,$city,$province,$country,$subtime,$comment,$flag1,$subscribe_scene);
                        $myres = $stmt->execute();
                    }
                    if($myres){
                        $result = $nickname."您好！欢迎关注订单系统微信号，请在菜单处绑定所有系统帐号，以方便接收系统发来的消息。";
                    }else{
                        $result = "保存数据库失败，请取关后重新关注！".$db->error;
                    }
                    $stmt->close();
                }
                echo WechatReply::replyText($obj,$result);
                break;
            case 'unsubscribe':
                $openid = $obj->FromUserName;
                //如果关注未绑定账户 flag=2 ,如果有绑定的数据flag=3
                $sql ="SELECT a.XtUser,a.openid FROM XT_Wx2Xt a WHERE a.openid=? ";
                if($stmt = $db->prepare($sql))
                {
                    $stmt->bind_param('s', $openid);
                    $stmt->execute();
                    $stmt->store_result();
                    $stmt->bind_result($XtUser,$openid);
                    if($stmt->fetch()){
                        $sql = "UPDATE `XT_WxUser` SET `flag`='3' WHERE openid=? ";
                    }else{
                        $sql = "UPDATE `XT_WxUser` SET `flag`='2' WHERE openid=? ";
                    }
                    $stmt = $db->prepare($sql);
                    $stmt->bind_param('s', $openid);
                    $myres = $stmt->execute();
                    if($myres){
                        $result = "取消关注";
                    }else{
                        $result = "保存数据库失败";
                    }
                    $stmt->close();
                }
                echo WechatReply::replyText($obj,$result);
                break;
            case 'SCAN':
                break;
            case 'CLICK':
                switch ($obj->EventKey) {
                    case 'FAQ':
                        echo WechatReply::replyText($obj,"你的点击的是FAQ事件");
                        break;
                    case 'lianxikf':
                        $content = "欢迎使用维尚订单中心客服系统，请根据提示发送信息接入相应客服：\n1、售后咨询\n2、工艺咨询\n3、订单进度咨询\n4、非标床垫与沙发申请\n5、物流发货咨询\n6、10天加急申请\n7、服务投诉\n（下次使用时可直接发送以上指令接入相应客服）\n";
                        echo WechatReply::replyText($obj,$content);
                        break;
                    default:
                        echo WechatReply::replyText($obj,"你的点击的是其他事件");
                        break;
                }
                break;
        }
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