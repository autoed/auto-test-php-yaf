
# Auto Test for Yaf Demo
* Auto, 懒人化测试PHP代码

* 不仅仅只适合Yaf ( Not Just For Yaf ! ) 

* You can make it for other frameworks. Delivered as php-extensions.


## Install
* composer require auto/yaf
##### /\\^请多操作一次确保更新成功^/\
* composer update


## ADD  Auto Test  to Yaf 
* 在`index.php`文件
* 加入下面的代码
```
define("CUR_TIMESTAMP", time());
/**
 * 配置你的API地址(不分开部署也行)
 */
define("AUTO_TEST_API_HOST", 'http://www.lidi.yaf.com');
/**
 * 开启注释测试模式
 */
define("AUTO_TEST_START", true);
```

* 在`Bootstrap.php`文件
* 加入下面的代码
```
/**
     * 函数载入
     * User:  fomo3d.wiki
     * Email: fomo3d.wiki@gmail.com
     * Date: 2020/5/10
     */
    public function _initFunction()
    {
        Loader::import('Common/functions.php');
        Loader::import('Common/routes.php');
        Loader::import('Common/baseController.php');
    }

    /**
     * 路由载入
     * User:  fomo3d.wiki
     * Email: fomo3d.wiki@gmail.com
     * Date: 2020/5/10
     */
    public function _initRoute()
    {
        Loader::import(ROUTE_PATH . '/Product.php');
        Loader::import(ROUTE_PATH . '/Meeting.php');
    }
    /**
     * 插件载入(这是重点)
     * User:  fomo3d.wiki
     * Email: fomo3d.wiki@gmail.com
     * Date: 2020/5/10
     * @param Dispatcher $dispatcher
     */
	public function _initPlugin(Yaf\Dispatcher $dispatcher)
    {
        /**
         * @var RoutePlugin $register
         */
        $register = new RoutePlugin();
        $dispatcher->registerPlugin($register);
	}
```

* 在插件目录中`RoutePlugin.php`文件
* 加入下面的代码
```
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
         * (这是重点)
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
```

## Start Auto Test Demo ^_~:
### 举个栗子：
* 1、编辑`application\modules\Product\controllers\Product.php`
```
              <?php
              
              /**
               * Class ProductController
               * User:  fomo3d.wiki
               * Email: fomo3d.wiki@gmail.com
               * Date: 2020/5/10
               */
              class ProductController extends BaseController
              {
                  /**
                   * 开启auto API Document
                   */
                  use Auto\Api;
              
                  /**
                   * 出售商品
                   * @GET array('name'=>$data::name())
                   * User:  fomo3d.wiki
                   * Email: fomo3d.wiki@gmail.com
                   * Date: 2020/5/10
                   */
                  public function sellAction()
                  {
                      $name = $this->get('name', 'this is sell name');
                      $this->getResponse()->setBody(success(['str'=>$name]));
                  }
              
                  /**
                   * 统计商品
                   * @GET array('email'=>$data::email())
                   * User:  fomo3d.wiki
                   * Email: fomo3d.wiki@gmail.com
                   * Date: 2020/5/11
                   */
                  public function countAction()
                  {
                      $name = $this->get('email', 'this is count email');
                      $this->getResponse()->setBody(success(['str'=>$name]));
                  }
              
                  /**
                   * 买进商品
                   * @GET array('num'=>$data::bankAccountNumber())
                   * User:  fomo3d.wiki
                   * Email: fomo3d.wiki@gmail.com
                   * Date: 2020/5/11
                   */
                  public function buyAction()
                  {
                      $name = $this->get('num', 'this is buy num');
                      $this->getResponse()->setBody(success(['str'=>$name]));
                  }
              }
```
* 2、创建：视图目录中`api.php`文件
* 加入下面的代码
* 根据你自己的模板引擎自己配置： 
* notice 不可修改
* host 就是index.php 中定义的 AUTO_TEST_API_HOST
```
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Welcome to Yaf</title>
</head>
<body>
<div class="container">
    <h1>Welcome to auto api !</h1>
    <div id="body">
        <p>
            {{ notice }}
        </p>
        <p>
            <a href="{{ host }}/product/api">{{ host }}/product/api</a><br><br>
            <a href="{{ host }}/meeting/api">{{ host }}/meeting/api</a><br><br>
        </p>
    </div>
</div>
</body>
</html>
```

* 3、访问：http://www.lidi.yaf.com/product/api
##### 输出类似下面内容，恭喜你成功啦！
```
Welcome to auto api !
You should know , this api is useful !

http://www.lidi.yaf.com/product/api

http://www.lidi.yaf.com/meeting/api

```
## Notice Help:

### 目前支持3种格式
* 1、普通参数传递：@AD (发送请求为POST)
```
@AD array('say'=>$data::bankAccountNumber(),'name'=>$data::name())
```
or
```
@AD array('say'=>'我爱你,','name'=>'伟大的祖国！')
```
* 2、GET参数传递：@GET (发送请求为GET)
```
@GET array('num'=>$data::bankAccountNumber(),'email'=>$data::email())
```

* 3、POST参数传递：@POST (发送请求为POST)
```
@POST array('name'=>$data::name(),'address'=>$data::address())
```

访问：http://www.lidi.yaf.com/product/api
##### 输出类似下面内容，恭喜你成功啦！
```
Welcome to auto api !
You should know , this api is useful !

http://www.lidi.yaf.com/product/api

http://www.lidi.yaf.com/meeting/api


/**

* 出售商品

* @GET array('name'=>$data::name())

* User: fomo3d.wiki

* Email: fomo3d.wiki@gmail.com

* Date: 2020\/5\/10

*/
name:



发送请求: user/product

/**

* 统计商品

* @GET array('email'=>$data::email())

* User: fomo3d.wiki

* Email: fomo3d.wiki@gmail.com

* Date: 2020\/5\/11

*/
email:



发送请求: user/count

/**

* 买进商品

* @GET array('num'=>$data::bankAccountNumber())

* User: fomo3d.wiki

* Email: fomo3d.wiki@gmail.com

* Date: 2020\/5\/11

*/
num:



发送请求: user/buy
```
*
*
* 待续3...ing



