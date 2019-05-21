<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/5/22
 * Time: 15:32
 */
require_once("config.inc.php");
require_once("WechatApi.php");
require_once("WechatReply.php");

global $db;

$datas = array();
$type = isset($_GET['type'])?$_GET['type']:1;

$sql = "SELECT a.url,a.title,a.digest,a.thumb_url,a.media_id FROM XT_WxMaterial a WHERE a.showflag=1 and a.menutype=?";
$stmt = $db->prepare($sql);
$stmt->bind_param('s',$type);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($url,$title,$digest,$thumb_url,$media_id);
$i = 0;
if($stmt->num_rows>0){
    while ($stmt->fetch()){
        $datas[$i]['url'] = $url;
        $datas[$i]['title'] = $title;
        $datas[$i]['digest'] = $digest;
        $datas[$i]['thumb_url'] = $thumb_url;
        $datas[$i]['media_id']=$media_id;
        $i++;
    }
}
$stmt->close();
$searchInput = isset($_GET['searchInput'])?$_GET['searchInput']:"";
$i = 0;
$newarr = array();

$data = WechatApi::get_material_count();
WechatReply::sendMessage($GLOBALS['my_openid'],$data);
exit;

foreach ($datas as $v3){
    if($searchInput != ''&&strstr($v3['title'],$searchInput)) {
        $newarr[$i]['title'] = str_replace($searchInput, "<font color='red'>" . $searchInput . "</font>", $v3['title']);
    }else{
        $newarr[$i]['title'] = $v3['title'];
    }
    $newarr[$i]['url'] = $v3['url'];
    $newarr[$i]['thumb_url'] = $v3['thumb_url'];
    $newarr[$i]['digest'] = $v3['digest'];
    $path = WechatApi::download_img($v3['thumb_url'],$v3['media_id']);

    $basestr = base64_encode($path);
    $newarr[$i]['thumb_url0'] = $basestr;
    $basestr = chunk_split($basestr,76);
    $newarr[$i]['thumb_url1'] = $basestr;
    $newarr[$i]['thumb_url2'] = $path;
    $i++;
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0,viewport-fit=cover">
    <script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>
    <link rel="stylesheet" href="../wx/weui-master/dist/style/weui.css"/>
    <style type="text/css">
        .weui-search-bar__box{
            height:5%;
        }
    </style>
    <title>维尚订单服务中心</title>
</head>
<body>

<div class="page">
    <div class="page__hd">
        <h1 class="page__title">历史消息</h1>
    </div>
    <div class="page__bd">

        <div class="weui-search-bar" id="searchBar">
            <form class="weui-search-bar__form">
                <div class="weui-search-bar__box">
                    <i class="weui-icon-search"></i>
                    <input type="search" class="weui-search-bar__input" id="searchInput" name="searchInput" placeholder="搜索" required/>
                    <a href="javascript:" class="weui-icon-clear" id="searchClear"></a>
                </div>
                <label class="weui-search-bar__label" id="searchText">
                    <i class="weui-icon-search"></i>
                    <span>搜索</span>
                </label>
            </form>
            <a href="javascript:" class="weui-search-bar__cancel-btn" id="searchCancel">取消</a>
        </div>
        <div class="weui-cells searchbar-result" id="searchResult">
            <div class="weui-cell weui-cell_access">
                <div class="weui-cell__bd weui-cell_primary">
                    <p></p>
                </div>
            </div>

        </div>

        <div class="weui-panel weui-panel_access">
            <!-- div class="weui-panel__hd">图文组合列表</div -->
            <div class="weui-panel__bd">

                <?php

                foreach ($newarr as $row){
                    echo '<a href="'.$row["url"].'" class="weui-media-box weui-media-box_appmsg">
						<div class="weui-media-box__hd">

						<img class="weui-media-box__thumb" src="data:image/png;base64,'.$row["thumb_url0"].'" alt="">
						</div>
						<div class="weui-media-box__bd">
						<h4 class="weui-media-box__title">'.$row["title"].'</h4>						
						<p class="weui-media-box__desc">'.$row["digest"].'</p>
						</div>
						</a><br />';
                    $i++;

                }
                ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function(){
        var $searchBar = $('#searchBar'),
            $searchResult = $('#searchResult'),
            $searchText = $('#searchText'),
            $searchInput = $('#searchInput'),
            $searchClear = $('#searchClear'),
            $searchCancel = $('#searchCancel');

        function hideSearchResult(){
            $searchResult.hide();
            $searchInput.val('');
        }
        function cancelSearch(){
            hideSearchResult();
            $searchBar.removeClass('weui-search-bar_focusing');
            $searchText.show();
        }

        $searchText.on('click', function(){
            $searchBar.addClass('weui-search-bar_focusing');
            $searchInput.focus();
        });
        $searchInput
            .on('blur', function () {
                if(!this.value.length) cancelSearch();
            })
            .on('input', function(){
                if(this.value.length) {
                    $searchResult.show();
                } else {
                    $searchResult.hide();
                }
            })
        ;
        $searchClear.on('click', function(){
            hideSearchResult();
            $searchInput.focus();
        });
        $searchCancel.on('click', function(){
            cancelSearch();
            $searchInput.blur();
        });
    });
</script>

</body>
</html>
