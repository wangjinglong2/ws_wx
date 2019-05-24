<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/5/23
 * Time: 12:51
 */
require_once "config.inc.php";
global $db;
$datas = array();
$sql = "SELECT * FROM XT_WxWenDa WHERE 1";
$res = $db->query($sql);

$i=0;
foreach ($res as $v3){
//    if($searchInput != ''&&strstr($v3['keywords'],$searchInput)) {
//        $newarr[$i]['keywords'] = str_replace($searchInput, "<font color='red'>" . $searchInput . "</font>", $v3['keywords']);
//    }else
    $newarr[$i]['keywords'] = $v3['keywords'];
    $newarr[$i]['protype'] = $v3['protype'];
    $newarr[$i]['question'] = $v3['question'];
    $newarr[$i]['answer'] = $v3['answer'];
    $newarr[$i]['url'] = $GLOBALS['root_url']."Answer.php?id=".$v3['id'];
    $i++;
}

?>

<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0,viewport-fit=cover">
    <script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>
    <link rel="stylesheet" href="../ws_wx/weui-master/dist/style/weui.css"/>
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
        <!--  <h1 class="page__title">历史消息</h1> -->
        <!-- p class="page__desc">历史消息</p -->

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
						<div class="weui-media-box__hd" style="background-color:#dddddd;">'.$row["protype"].'</div>
						<div class="weui-media-box__bd">
						<h4 class="weui-media-box__title">'.$row["question"].'</h4>						
						
						</div>
						</a><br />';
                    $i++;
                }
                ?>

            </div>
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