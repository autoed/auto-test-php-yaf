<?php
use Yaf\Registry;

header('content-type:text/html;charset=utf-8');

define("CUR_TIMESTAMP", time());

//API 测试
define("AUTO_TEST_API_HOST", 'http://www.lidi.yaf.com');
//开启测试
define("AUTO_TEST_START", true);

define("APP_PATH",  realpath(dirname(__FILE__) . '/../'));

define("ROUTE_PATH", APP_PATH . "/application/routes");

//加载配置
$app  = new Yaf\Application(APP_PATH . "/conf/application.ini");

//关闭视图
$app->getDispatcher()->disableView();

//启动应用
try {
    $app->bootstrap()->run();
} catch (Exception $e) {
    $response = Registry::get('response');
    $response->setBody(error(['notice'=>$e->getMessage()]));
    $response->response();
} catch (Throwable $e) {
    $response = Registry::get('response');
    $response->setBody(error(['notice'=>$e->getMessage()]));
    $response->response();
}
?>
