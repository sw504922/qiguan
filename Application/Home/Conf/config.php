<?php
return array(
    //'配置项'=>'配置值'
    'TMPL_PARSE_STRING' => array(
        '__IMG__' => __ROOT__ . '/Public/' . MODULE_NAME . '/images',
        '__CSS__' => __ROOT__ . '/Public/' . MODULE_NAME . '/css',
        '__JS__' => __ROOT__ . '/Public/' . MODULE_NAME . '/js',
    ),

    //设置css、js、img样式地址
    //默认指向错误模板
    'TMPL_ACTION_ERROR' => MODULE_PATH . 'View/Login/error.html',
    //默认指向成功模板
    'TMPL_ACTION_SUCCESS' => MODULE_PATH . 'View/Login/success.html',
);