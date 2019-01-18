<?php
return array(
    // MySQL默认配置
    'DB_TYPE' => 'mysql',
    'DB_HOST' => '47.92.75.8',
    'DB_NAME' => 'jrqg',
    'DB_USER' => 'root',
    'DB_PWD' => 'qiguan',
    'DB_PORT' => '3306',
    'DB_PREFIX' => '',
    'DB_CHARSET' => 'utf8',


    // 页面Trace
    'SHOW_PAGE_TRACE' => false,
    // session
    'SESSION_AUTO_START' => true,
    'SESSION_OPTIONS' => array(
        'expire' => 24 * 3600
    ),
    // 默认模块
    'DEFAULT_MODULE' => 'Home',	//默认目录
    'MODULE_ALLOW_LIST' => array('Home','Admin'),


    'replaceYPath'=>'/qiguan/discover/',
    'newsurl'=>'http://47.92.75.8:8080//jr//jrqg/stream/streamContent/',

);