<?php 
//声明命名空间
namespace Illuminate;
//定义最终的框架初始类
final class Kernel 
{
	//公共的静态的初始化方法
	public static function run()
	{
		self::initCharset();//初始化字符集设置
		self::initConfig();//初始化配置信息
		self::initRoute();//初始化路由参数
		self::initConst();//初始化目录常量设置
		self::initAutoLoad();//初始化类的自动加载
		self::initDispatch();//初始化请求分发
	}
	 
	//初始化字符集设置
	private static function initCharset()
	{
		header("content-type:text/html;charset=utf-8");
		//开启SESSION会话
		session_start();
	}

	//初始化配置信息
	private static function initConfig()
	{
		$GLOBALS['config'] = require_once(APP_PATH."config".DS."Config.php");
	}

	//初始化路由参数
	private static function initRoute()
	{
		//获取路由参数
		$p = $GLOBALS['config']['default_platform'];//平台参数
		$c = isset($_GET['c']) ? $_GET['c'] : $GLOBALS['config']['default_controller'];//控制器参数
		$a = isset($_GET['a']) ? $_GET['a'] : $GLOBALS['config']['default_action'];//用户动作参数
		define("PLAT", $p);//平台常量,常量可以在任何地方使用
		define("CONTROLLER", $c);
		define("ACTION", $a);
	}

	//初始化目录常量设置
	private static function initConst()
	{
		define("VIEW_PATH", APP_PATH."Views".DS);//Views目录
		define("FRAME_PATH", ROOT_PATH."Illuminate".DS);//Illuminate目录	
	}	

	//初始化类的自动加载
	private static function initAutoLoad()
	{
		//类的自动加载
		spl_autoload_register(function($className){
			$filename = ROOT_PATH.str_replace("\\", DS, $className).".php";
			if(file_exists($filename)) 
				{
					require_once($filename);
				}
		});
	}

	//初始化请求分发
	private static function initDispatch()
	{
		//构建控制器类名: app\Home\Controllers\IndexController
		$className = "app"."\\".PLAT."\\"."Controllers"."\\".CONTROLLER."Controller";
		//创建控制器类的对象
		$controllerObj = new $className();
		//调用控制器的方法
		$actionName = ACTION;
		$controllerObj->$actionName();
	}
}