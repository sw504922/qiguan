<?php
/**
 * Created by date 2018/8/17.
 * Author: wei.sun
 * Type:
 ***/


function strReplace($str){
    $pos=strpos($str,".html");
    if ($pos!==false){
        $str=str_replace(".html","",$str);
    }

    return $str;
}