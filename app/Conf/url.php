<?php 
return array (
  	'URL_MODEL'=>2,//U函数格式
	'URL_ROUTER_ON'   => true,//URL重写
	'URL_HTML_SUFFIX' => '',//伪静态
    'APP_SUB_DOMAIN_DEPLOY'=>1, //开启子域名
    'APP_SUB_DOMAIN_RULES'=>array(
			'mis'=>array('mis/'),
			'api'=>array('api/'),
			'www'=>array('www/'),
			),
	'URL_ROUTE_RULES' =>array(
		'/^i\/(\w+)$/'=>'go?id=:1',//w字符 d数字
	)
);