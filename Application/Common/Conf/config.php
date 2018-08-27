<?php
return array(
    // MySQL默认配置
    'DB_TYPE' => 'mysql',
    'DB_HOST' => '192.168.5.105',
    'DB_NAME' => 'report',
    'DB_USER' => 'root',
    'DB_PWD' => 'zunjiazichan123',
    'DB_PORT' => '3306',
    'DB_PREFIX' => '',
    'DB_CHARSET' => 'utf8',

    'DB_APPDATA' => array(
        'db_type'  => 'mysql',
        'db_user'  => 'root',
        'db_pwd'   => 'zunjiazichan123',
        'db_host'  => '192.168.5.106',
        'db_port'  => '3306',
        'db_name'  => 'app_data',
        'db_charset'=> 'utf8',
    ),

    'US_DB_APPDATA' => array(
        'db_type'  => 'mysql',
        'db_user'  => 'root',
        'db_pwd'   => 'zunjiazichan123',
        'db_host'  => '192.168.5.106',
        'db_port'  => '3306',
        'db_name'  => 'us_data',
        'db_charset'=> 'utf8',
    ),
    'APP_DB_DATA' => array(
        'db_type'  => 'mysql',
        'db_user'  => 'select_153',
        'db_pwd'   => 'zunjiazichan.123',
        'db_host'  => '192.168.5.153',
        'db_port'  => '3306',
        'db_name'  => 'miningstock',
        'db_charset'=> 'utf8',
    ),
    'Oracle_DATA'=>array(
        'DB_TYPE' => 'oracle',
        'DB_HOST' => '192.168.2.248',
        'DB_NAME' => 'zunjia',
        'DB_USER' => 'dp',
        'DB_PWD' => 'dp',
        'DB_PORT' => '1521',

    ),

    // 页面Trace
    'SHOW_PAGE_TRACE' => false,
    // session
    'SESSION_AUTO_START' => true,
    'SESSION_OPTIONS' => array(
        'expire' => 24 * 3600
    ),
    // 默认模块
    'DEFAULT_MODULE' => 'Home',	//默认目录
    'SERVING_HOST' => 'http://t.financialdatamining.com/news/',	//默认目录
    'MODULE_ALLOW_LIST' => array('Home','Admin'),


);