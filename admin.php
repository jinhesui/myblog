<?php
define("DS", DIRECTORY_SEPARATOR);//目录分割符
define("ROOT_PATH", getcwd().DS);//网站根目录
define("APP_PATH", ROOT_PATH."app".DS."Admin".DS);
//包含框架初始类
require_once(ROOT_PATH."Illuminate".DS."Kernel.php");
//调用框架初始化方法 嘟嘟嘟，你拨打的用户已接通
\Illuminate\Kernel::run();