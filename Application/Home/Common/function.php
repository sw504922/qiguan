<?php

use Think\Log;

/**
 * Created by date 2018/8/27.
 * Author: wei.sun
 * Type:
 ***/


function getdateYMD($str)
{
    return date("Ymd", strtotime($str));
}

function getdateFormatYMD($str)
{
    return date("Y-m-d", strtotime($str));
}

/***
 * 将数组组成字符串
 ****/
function getInArray($arr)
{
    $str = implode(",", $arr);
    $str = str_replace(",", "\",\"", $str);
    return $str;
}

/***
 * 将字符串转成加密的16字符
 ****/
function getKey($str)
{
    $rand=rand();
    Log::write($rand."swRand".$str);
    $rowkey = md5($rand.$str);
    return $rowkey;
}
