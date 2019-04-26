<?php

define("TOKEN", "hhy1314");//自己定义的token 就是个通信的私钥

$wechatObj = new wechatCallbackapiTest();

if(!isset($_GET['echostr']))
{
    //调用响应消息函数
    $wechatObj->responseMsg();
}
else
{
    //实现网址接入，调用验证消息函数
    $wechatObj->valid();
}
//$wechatObj->valid();
//echo "hello world";
//$wechatObj->definedItem();
//$wechatObj->responseMsg();

class wechatCallbackapiTest
{

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

            $fromUsername = $postObj->FromUserName;

            $toUsername = $postObj->ToUserName;

            $keyword = trim($postObj->Content);

            $msgType = $postObj->MsgType;
            $time = time();

            // $file = fopen("text.txt", 'w+');
            // fwrite($file,$postStr);
            // fclose($file);

            $textTpl = "<xml>

            <ToUserName><![CDATA[%s]]></ToUserName>

            <FromUserName><![CDATA[%s]]></FromUserName>

            <CreateTime>%s</CreateTime>

            <MsgType><![CDATA[%s]]></MsgType>

            <Content><![CDATA[%s]]></Content>

            <FuncFlag>0<FuncFlag>

            </xml>";

            $imgTpl = "<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[%s]]></MsgType>
            <Image>
            <MediaId><![CDATA[%s]]></MediaId>
            </Image>
            </xml>";

            $voiceTpl = "<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[%s]]></MsgType>
            <Voice>
            <MediaId><![CDATA[%s]]></MediaId>
            </Voice>
            </xml>";
            $msgType = $postObj->MsgType;

            if($msgType == 'text')
            {

                $msgType = "text";

                $contentStr = '你好啊，屌丝';

                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);

                echo $resultStr;

            }
            else if ($msgType == 'image'){
                $msgType = "image";
                $mediaId = $postObj->MediaId;

                $resultStr = sprintf($imgTpl,$fromUsername,$toUsername,$time,$msgType,$mediaId);
                echo $resultStr;
            }
            else if ($msgType == 'voice'){
                $msgType = "voice";
                $mediaId = $postObj->MediaId;
                $format = $postObj->Format;
                $resultStr = sprintf($voiceTpl,$fromUsername,$toUsername,$time,$msgType,$mediaId);
                echo $resultStr;
            }

        }else {

            echo '咋不说哈呢';

            exit;

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

    public function  definedItem(){
        //创建微信菜单
        //目前微信接口的调用方式都是通过 curl post/get
        header('content-type:text/html;charset=utf-8');
        //$access_token=$this ->getWxAccessToken();
        //$file = fopen("text.txt", 'w+');
         //   fwrite($file,$access_token);
         //   fclose($file);

        $access_token = '20_ktMDxSMm7Qo2uSfqQhLOeJZrz4JIbnQFSOCTTirvxOzGDQlMWtBT1Oq5XKwPw0O42MfgkSKNOJK0d5jUsgk7tthf2zCE90RWpHrtuQn8PyHkaMQtPapmlF_x5pADZNhAHAJFT';

        $url ='https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$access_token;
        $postArr=array(
            'button'=>array(
                array(
                    'name'=>urlencode('菜单一'),
                    'type'=>'click',
                    'key'=>'item1',
                ),
                array(
                    'name'=>urlencode('菜单二'),
                    'sub_button'=>array(
                        array(
                            'name'=>urlencode('歌曲'),
                            'type'=>'click',
                            'key'=>'songs'
                        ),//第一个二级菜单
                        array(
                            'name'=>urlencode('电影'),
                            'type'=>'view',
                            'url'=>'http://www.baidu.com'
                        ),//第二个二级菜单
                    )
                ),

                array(
                    'name'=>urlencode('菜单三'),
                    'type'=>'view',
                    'url'=>'http://www.qq.com',
                ),//第三个一级菜单

        ));
        echo  $postJson = urldecode(json_encode($postArr));
        $res = $this->http_curl($url,'post','json',$postJson);

        var_dump($res);
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
    //返回access_token *session解决办法 存mysql memcache
    public function  getWxAccessToken(){
        if( $_SESSION['access_token'] && $_SESSION['expire_time']>time()){
          //如果access_token在session没有过期
            echo "111";
            echo $_SESSION['access_token'];;
            return $_SESSION['access_token'];
        }
        else{
            //如果access_token比存在或者已经过期，重新取access_token
            //1 请求url地址
            $AppId='wx7862699af0335b9a';
            $AppSecret='509a0ca98eead7b13bcf36a0419a9c5a';
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx7862699af0335b9a&secret=509a0ca98eead7b13bcf36a0419a9c5a";
            $res=$this->http_curl($url,'get','json');
            echo "res";
            echo $res;
            $access_token =$res['access_token'];
            //将重新获取到的aceess_token存到session
            $_SESSION['access_token']=$access_token;
            $_SESSION['expire_time']=time()+7000;
            echo "2222";
            echo $access_token;
            return $access_token;
        }
    }
}

?>