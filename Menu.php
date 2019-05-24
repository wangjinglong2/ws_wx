<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/5/6
 * Time: 17:27
 */
require_once("config.inc.php");
require_once("WechatApi.php");

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
            "name":"店面秘籍",
            "sub_button":[
            {
                "type":"view",
                "name":"工艺下单标准",
                "url":"'.$GLOBALS['root_url'].'MaterialList?type=2"
            },{
                "type":"view",
                "name":"安装售后保养",
                "url":"'.$GLOBALS['root_url'].'MaterialList?type=3"
            },{
                "type":"view",
                "name":"常见问题处理",
                "url":"'.$GLOBALS['root_url'].'MaterialList?type=4"
            },{
                "type":"view",
                "name":"新产品介绍",
                "url":"'.$GLOBALS['root_url'].'MaterialList?type=5"
            },{
                "type":"view",
                "name":"配套产品详解",
                "url":"'.$GLOBALS['root_url'].'MaterialList?type=6"
            }]          
       },{
            "name":"精选干货",
            "sub_button":[
            {
                "type":"view",
                "name":"尚品美家",
                "url":"https://shr.yfway.com/index.php?s=/Home/Topline/index/brand/0/v/3/share_from/12"
            },{
                "type":"view",
                "name":"维意美家",
                "url":"https://shr.yfway.com/index.php?s=/Home/Topline/index/brand/1/v/3/share_from/13"
            },{
                "type":"view",
                "name":"精选干货",
                "url":"'.$GLOBALS['root_url'].'MaterialList?type=1"
            },{
                "type":"miniprogram",
                 "name":"量尺宝",
                 "url":"http://dpf.yfway.com/index.php?s=/Weixin/Neoweb/liangchibao_miniprogrampages",
                 "appid":"wx530ad84be2e64aeb",
                 "pagepath":"pages/index/index"
            },{
                "type":"view",
                "name":"整装售后问答",
                "url":"'.$GLOBALS['root_url'].'QACenter.php"
            }]  
       }]
       }';
WechatApi::create_menu($menu);
?>
