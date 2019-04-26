<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/4/23
 * Time: 15:41
 */

require("wechat_api.php");

$access_token = wechat_api::get_access_token();
$curl = curl_init();

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

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=$access_token",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_SSL_VERIFYPEER=>false,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS =>$menu,
  CURLOPT_HTTPHEADER => array(
    "Accept: */*",
    "Cache-Control: no-cache",
    "Connection: keep-alive",
    "Content-Type: text/plain",
    "Host: api.weixin.qq.com",
    "User-Agent: wxtest",
    "accept-encoding: gzip, deflate",
    "cache-control: no-cache",
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}
?>
