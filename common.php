<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/5/23
 * Time: 10:43
 */
function IsWindows()
{
    return strtoupper(strstr(PHP_OS,0,3))=="WIN";
}
?>
