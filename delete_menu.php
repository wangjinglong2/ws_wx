<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/4/23
 * Time: 16:08
 */
require("wechat_api.php");

$access_token = wechat_api::get_access_token();
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=$access_token",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_POSTFIELDS => "",
  CURLOPT_SSL_VERIFYPEER=>false,
  CURLOPT_HTTPHEADER => array(
    "Accept: */*",
    "Cache-Control: no-cache",
    "Connection: keep-alive",
    "Host: api.weixin.qq.com",
    "User-Agent: wxtest",
    "accept-encoding: gzip, deflate",
    "cache-control: no-cache"
  )
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
