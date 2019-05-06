<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/5/6
 * Time: 17:27
 */
require_once("config.inc.php");

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
                "url":"'.$GLOBALS['root_url'].'OAuth2.php"
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
WechatApi::create_menu($menu);
?>
