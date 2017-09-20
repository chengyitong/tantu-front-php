<?php
//  全局utf-8编码
header("Content-Type:text/html;charset=utf-8");
//  配置参数
return array(
    'APP_GROUP_LIST' => 'www,mis,api', //分组
    'DEFAULT_GROUP' => 'www', //默认分组
    'DEFAULT_MODULE' => 'index', //默认控制器
    'LOAD_EXT_CONFIG' => 'url,db', //加载自定义配置文件
    'LOAD_EXT_FILE' => 'bd_helper', //加载自定义函数文件
    'URL_CASE_INSENSITIVE'=>0,// 大小写
    'SHOW_PAGE_TRACE' => 1,// 调试
);
?>