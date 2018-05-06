<?php 
//返回框架初始化的配置数组
return array(
	//数据库配置信息
	'db_type' => 'mysql',//数据库类型
	'db_host' => 'localhost',//主机名
	'db_user' => 'homestead',//用户名
	'db_pass' => 'secret',//密码
	'db_name' => 'blog',//数据库名
	'db_port' => '33060',//端口号
	'charset' => 'utf8',//字符集

	//框架初始化
	'default_platform'   => 'Admin',//默认应用
	'default_controller' => 'Index',//默认控制器
	'default_action'     => 'index',//默认动作
);