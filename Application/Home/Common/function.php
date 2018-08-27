<?php
/**
 * Created by date 2018/8/27.
 * Author: wei.sun
 * Type:
 ***/


function getdateYMD($str){
    return date("Ymd",strtotime($str));
}

function getdateFormatYMD($str){
    return date("Y-m-d",strtotime($str));
}