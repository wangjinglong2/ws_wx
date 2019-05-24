<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/5/17
 * Time: 13:08
 */
require_once("config.inc.php");
global $db;

$user = isset($_POST['user'])?$_POST['user']:"";
$pwd  = isset($_POST['passwd'])?$_POST['passwd']:"";
$openid = isset($_POST['openid'])?$_POST['openid']:"";
$user_info = isset($_POST['user_info'])?$_POST['user_info']:"";

if ($openid==""){
    echo json_encode(array('code'=>0,'msg'=>'用户openid为空'));
    exit;
}

try{
    $query = "SELECT a.loginName,loginPwd,PASSWORD('$pwd') as mloginPwd,a.trueName FROM LoginUser a WHERE a.loginName= ?";
    if ($stmt = $db->prepare($query)) {
        $stmt->bind_param('s', $user);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($loginName,$loginPwd,$mloginPwd,$trueName);
        if($stmt->fetch()) {
            $stmt->close();
            if($loginPwd!=$mloginPwd){
                $arr = array(
                    "info" => '你输入的账户名或密码错误',
                    "name" => 'response1',
                    "state" => '0'
                );
                echo json_encode($arr);
                exit();
            }
        }else{
            $stmt->close();
            $arr = array(
                "info" => '你输入的账户名或密码错误',
                "name" => 'response1',
                "state" => '0'
            );
            echo json_encode($arr);
            exit();
        }
    }
}catch (\Exception $exception){
    $msg = "error_code:".$exception->getCode().",msg:".$exception->getMessage();
    echo json_encode(array("info"=>$msg,"state"=>0));
    exit;
}


//查询XT_Wx2Xt 表里是否有记录
//如果有记录 1 修改XT_WxUser 旧的openid 的flag=0，把新的users对应的openid改为1，，修改XT_Wx2Xt的openid
//检测微信号和帐号有没绑定
try{
    $sqla ="SELECT count(*) count FROM XT_Wx2Xt a WHERE a.XtUser=? AND a.openid=? ";
    if ($stmt = $db->prepare($sqla)) {
        $stmt->bind_param('ss', $user,$openid);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($count);
        $row = $stmt->fetch();
        $Oldopenid="";
        if($count > 0){
            $arr = array(
                "info" => '该用户名已经绑定微信号',
                "name" => 'response2',
                "state" => '0'
            );

        }else{
            //判断账户有没绑定过微信号 绑定过的修改，未绑定过的新增
            $sqla ="SELECT a.XtUser,a.openid FROM XT_Wx2Xt a WHERE a.XtUser=? ";
            if ($stmt = $db->prepare($sqla)){
                $stmt->bind_param('s', $users);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($XtUser,$Oldopenid);
                $row = $stmt->fetch();
                if($row){
                    //如果只绑定过一个帐号可以直接修改旧的openid 多条则不改旧的openid
                    $sqlb ="SELECT a.openid FROM XT_Wx2Xt a WHERE a.openid=? ";
                    $stmt = $db->prepare($sqlb);
                    $stmt->bind_param("s", $Oldopenid);
                    $stmt->execute();
                    $stmt->store_result();
                    $rows = $stmt->num_rows;
                    $db->autocommit(false);
                    //有一条，可以直接更新Oldopenid 的flag
                    if($rows == 1){
                        $sql = "UPDATE XT_WxUser a SET a.flag=0 WHERE a.openid=?";
                        $stmt = $db->prepare($sql);
                        $stmt->bind_param("s", $Oldopenid);
                        $myres =$stmt->execute();
                    }
                    //更新帐号微信对照表
                    $sql1 = "UPDATE XT_Wx2Xt a SET a.openid='".$openid."' WHERE a.XtUser=?";
                    $stmt = $db->prepare($sql1);
                    $stmt->bind_param("s", $XtUser);
                    $myres1 =$stmt->execute();

                    //更新新的openid
                    $sql2 = "UPDATE XT_WxUser a SET a.flag=1 WHERE a.openid=?";
                    $stmt = $db->prepare($sql2);
                    $stmt->bind_param("s", $openid);
                    $myres2 =$stmt->execute();

                    if($rows == 1){
                        if($myres && $myres1 && $myres2){
                            $db->commit();
                            $arr = array(
                                "info" => '绑定成功',
                                "name" => 'response3',
                                "state" => '1'
                            );
                        }else{
                            $db->rollback();
                            $arr = array(
                                "info" => '绑定失败'.$db->error,
                                "name" => 'response5',
                                "state" => '0'
                            );
                        }
                    }else{
                        if($myres1 && $myres2){
                            $db->commit();
                            $arr = array(
                                "info" => '绑定成功',
                                "name" => 'response3',
                                "state" => '1'
                            );
                        }else{
                            $db->rollback();
                            $arr = array(
                                "info" => '绑定失败'.$db->error,
                                "name" => 'response5',
                                "state" => '0'
                            );
                        }
                    }
                }else{
                    $db->autocommit(false);
                    $sql1 = "INSERT INTO `XT_Wx2Xt`(`XtUser`, `openid`) VALUES (?,?)";
                    $stmt = $db->prepare($sql1);
                    $stmt->bind_param("ss", $user, $openid);
                    $myres1 =$stmt->execute();

                    //修改微信用户表flag
                    $sql2 = "UPDATE XT_WxUser a SET a.flag=1 WHERE a.openid= ?";
                    $stmt = $db->prepare($sql2);
                    $stmt->bind_param("s", $openid);
                    $myres2 =$stmt->execute();


                    if($myres1 && $myres2){
                        $db->commit();
                        $arr = array(
                            "info" => '绑定成功',
                            "name" => 'response3',
                            "state" => '1'
                        );
                    }else{
                        $db->rollback();
                        $arr = array(
                            "info" => 'here'.$db->error,
                            "name" => 'response3',
                            "state" => '0'
                        );
                    }
                }
            }
        }
        $stmt->close();
    }
}catch (\Exception $exception){
    die("error_code:".$exception->getCode().",msg:".$exception->getMessage());
}
echo json_encode($arr);

//调换分组
//$access_token = WechatApi::get_access_token();
//$url = "https://api.weixin.qq.com/cgi-bin/groups/members/update?access_token=".$access_token;
//$data = '{"openid":"'.$openid.'","to_groupid":100}';
//$ret = WechatApi::http_curl($url,$output,'post',$data);
//
//if($Oldopenid){
//    $url = "https://api.weixin.qq.com/cgi-bin/groups/members/update?access_token=".$access_token;
//    $data = '{"openid":"'.$Oldopenid.'","to_groupid":0}';
//    $ret = WechatApi::http_curl($url,$output,'post',$data);
//}
////修改备注姓名_店名
//$ShopName = "";
//if($ShopName!=''){
//    $url ="https://api.weixin.qq.com/cgi-bin/user/info/updateremark?access_token=".$access_token;
//    $remark = $trueName.'_'.$ShopName;
//    $remark = substr($remark,0,30);
//    $data ='{"openid":"'.$openid.'","remark":"'.$remark.'"}';
//    $ret = WechatApi::http_curl($url,$output,'post',$data);
//}
?>
