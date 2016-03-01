<?php

define('RUN_START_TIME', time());

// 定时路径
define('ROOT_PATH', dirname(__FILE__)  . DIRECTORY_SEPARATOR);
define('LIB_PATH',  ROOT_PATH . 'lib'  . DIRECTORY_SEPARATOR);
define('CONF_PATH', ROOT_PATH . 'conf' . DIRECTORY_SEPARATOR);
define('LOG_PATH',  ROOT_PATH . 'log'  . DIRECTORY_SEPARATOR);
define('TASK_PATH', ROOT_PATH . 'task' . DIRECTORY_SEPARATOR);

// 设置时区
date_default_timezone_set('Asia/Shanghai');

// 设置自动加载
function my_loader($class) {
    $classFile = str_replace('_', DIRECTORY_SEPARATOR, $class).'.php';
    require_once LIB_PATH.$classFile;
}
spl_autoload_register('my_loader');

function useage() {
    printf("php -f cli.php class_name method_name\n");
}

if ($_SERVER['argc'] < 3) {
    useage();
    exit(0);
}

$className  = $_SERVER['argv'][1].'Task';
$methodName = $_SERVER['argv'][2].'Action';

$classFile = TASK_PATH . $className.'.php';
if (!file_exists($classFile)) {
    printf("class file[%s] not found\n", $classFile);
    useage();
    exit(0);
}
require_once $classFile;

if (!class_exists($className)) {
    printf("invalid class\n");
    useage();
    exit(0);
}

$cliTask = new $className();
if (!method_exists($cliTask, $methodName)) {
    printf("invalid method\n");
    useage();
    exit(0);
}

$cliTask->$methodName();

