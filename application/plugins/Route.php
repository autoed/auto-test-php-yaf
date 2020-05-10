<?php

use Yaf\Application;
use Yaf\Plugin_Abstract;
use Yaf\Registry;
use Yaf\Request_Abstract;
use Yaf\Response_Abstract;

class RoutePlugin extends Plugin_Abstract
{

    /**
     * User:  fomo3d.wiki
     * Email: fomo3d.wiki@gmail.com
     * Date: 2020/5/10
     * @param Request_Abstract $request
     * @param Response_Abstract $response
     */
    public function routerStartup(Request_Abstract $request, Response_Abstract $response)
    {
        //echo "Plugin routerStartup called <br/>\n";
        $uri = $request->getRequestUri();
        $uri = Routes::route_uri_format($uri);
        /**
         * 1、路由白名单 2、登录信息 3、缓存信息
         */
        Registry::set('request', $request);
        Registry::set('response', $response);
        Registry::set('url', $uri);
    }

    /**
     * User:  fomo3d.wiki
     * Email: fomo3d.wiki@gmail.com
     * Date: 2020/5/10
     * @param Request_Abstract $request
     * @param Response_Abstract $response
     * @throws Exception
     */
    public function routerShutdown(Request_Abstract $request, Response_Abstract $response)
    {
        //echo "Plugin routerShutdown called <br/>\n";
        if ($request->isCli()) {
            return;
        }
        $uri = $request->getRequestUri();
        $uri = Routes::route_uri_format($uri);
        $method    = $request->getMethod();
        $modules   = Application::app()->getModules();
        $realRoute = '';
        $realRoute = isset(Routes::$any[$uri]) ? Routes::$any[$uri] : $realRoute;
        if ($method == 'GET') {
            $realRoute = isset(Routes::$get[$uri]) ? Routes::$get[$uri] : $realRoute;
        }

        if ($method == 'POST') {
            $realRoute = isset(Routes::$post[$uri]) ? Routes::$post[$uri] : $realRoute;
        }

        if ($realRoute == '') {
            throw new Exception('this is not support route');
        }

        $text = explode('@', trim($realRoute));
        $realModule = 'index'; $realController = 'index'; $realAction = 'index';
        $realModule     = isset($text[0]) ? $text[0] : $realModule;
        $realController = isset($text[1]) ? $text[1] : $realController;
        $realAction     = isset($text[2]) ? $text[2] : $realAction;

        if (!in_array($realModule, $modules)) {
            throw new Exception('this is not support module');
        }

        $request->setModuleName($realModule);
        $request->setControllerName($realController);
        $request->setActionName($realAction);
    }

    /**
     * User:  fomo3d.wiki
     * Email: fomo3d.wiki@gmail.com
     * Date: 2020/5/10
     * @param Request_Abstract $request
     * @param Response_Abstract $response
     */
    public function dispatchLoopStartup(Request_Abstract $request, Response_Abstract $response)
    {
        //echo "Plugin DispatchLoopStartup called <br/>\n";
    }

    /**
     * User:  fomo3d.wiki
     * Email: fomo3d.wiki@gmail.com
     * Date: 2020/5/10
     * @param Request_Abstract $request
     * @param Response_Abstract $response
     */
    public function preDispatch(Request_Abstract $request, Response_Abstract $response)
    {
        //echo "Plugin PreDispatch called <br/>\n";
    }

    /**
     * User:  fomo3d.wiki
     * Email: fomo3d.wiki@gmail.com
     * Date: 2020/5/10
     * @param Request_Abstract $request
     * @param Response_Abstract $response
     */
    public function postDispatch(Request_Abstract $request, Response_Abstract $response)
    {
        //echo "Plugin postDispatch called <br/>\n";
    }

    /**
     * User:  fomo3d.wiki
     * Email: fomo3d.wiki@gmail.com
     * Date: 2020/5/10
     * @param Request_Abstract $request
     * @param Response_Abstract $response
     */
    public function dispatchLoopShutdown(Request_Abstract $request, Response_Abstract $response)
    {
        //echo "Plugin DispatchLoopShutdown called <br/>\n";
        Registry::set('response', $response);
        //执行测试模式
        $strDoc = '';
        if (AUTO_TEST_START) {
            $allRoute = array_flip(array_map(function ($item){
                $tmp = explode('@', $item);
                return strtolower($tmp[1].'Controller/'.$tmp[2].'Action');
            }, array_merge(Routes::$get, Routes::$post)));
            $realController = $request->getControllerName();
            $realAction     = $request->getActionName();
            $className      = $realController . 'Controller';
            $actionName     = $realAction . 'Action';
            $file =  APP_PATH . '/application/modules/'.$request->getModuleName().'/controllers/'.$realController.'.php';
            if (file_exists($file)) {
                require_once $file;
            }
            try{
                $strDoc = Auto\Auto::getStrDoc($className, $actionName, $allRoute);
            } catch (\Exception $e) {
                //记录执行错误Log
                echo $e->getMessage();die;
            }
        }
        if ($strDoc) {
            $response->appendBody($strDoc);
        }
    }
}
