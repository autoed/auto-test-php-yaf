<?php

use Yaf\Bootstrap_Abstract;
use Yaf\Dispatcher;
use Yaf\Loader;

/**
 * Class Bootstrap
 * User:  fomo3d.wiki
 * Email: fomo3d.wiki@gmail.com
 * Date: 2020/5/5
 */
class Bootstrap extends Bootstrap_Abstract
{
    /**
     * User:  fomo3d.wiki
     * Email: fomo3d.wiki@gmail.com
     * Date: 2020/5/10
     */
	private $config;

    /**
     * 初始化错误
     * User:  fomo3d.wiki
     * Email: fomo3d.wiki@gmail.com
     * Date: 2020/5/10
     */
    public function _initErrors()
    {
        //如果为开发环境,打开所有错误提示
        if (Yaf\ENVIRON === 'develop') {
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
        }
    }

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
     * vendor载入
     * User:  fomo3d.wiki
     * Email: fomo3d.wiki@gmail.com
     * Date: 2020/5/10
     */
    public function _initLoader()
    {
        Loader::import(APP_PATH . '/vendor/autoload.php');
    }

    /**
     * 配置载入
     * User:  fomo3d.wiki
     * Email: fomo3d.wiki@gmail.com
     * Date: 2020/5/10
     */
    public function _initConfig()
    {
		//把配置保存起来
		$this->config = \Yaf\Application::app()->getConfig();
        \Yaf\Registry::set('config', $this->config);
	}

    /**
     * 日志载入
     * User:  fomo3d.wiki
     * Email: fomo3d.wiki@gmail.com
     * Date: 2020/5/10
     * @param Dispatcher $dispatcher
     * @throws Exception
     */
	public function _initLogger(\Yaf\Dispatcher $dispatcher)
    {
        //SocketLog
        if (Yaf\ENVIRON === 'develop') {
            if ($this->config->socketlog->enable) {
                //载入
                Loader::import('Common/Logger/slog.function.php');
                //配置SocketLog
                slog($this->config->socketlog->toArray(),'config');
            }
        }
    }

    /**
     * 插件载入
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


    /**
     * 模板引擎
     * User:  fomo3d.wiki
     * Email: fomo3d.wiki@gmail.com
     * Date: 2020/5/10
     * @param Dispatcher $dispatcher
     */
    public function _initView(\Yaf\Dispatcher $dispatcher)
    {
        $view_engine = $this->config->application->view->engine;
        if ($view_engine == 'twig') {//twig模板引擎
            $twig = new \Twig\Adapter(APP_PATH . "/application/views/", $this->config->get("twig")->toArray());
            $dispatcher->setView($twig);
        } elseif ($view_engine == 'smarty') {//smarty模板引擎
            $smarty = new \Smarty\Adapter(null, $this->config->smarty->toArray());
            $dispatcher->setView($smarty);
        }
    }

    /**
     * 数据库分发
     * User:  fomo3d.wiki
     * Email: fomo3d.wiki@gmail.com
     * Date: 2020/5/10
     */
    public function _initDefaultDbAdapter()
    {
        //初始化 illuminate/database
        $capsule = new \Illuminate\Database\Capsule\Manager;
        $capsule->addConnection($this->config->database->toArray());
        $capsule->setEventDispatcher(new \Illuminate\Events\Dispatcher(new \Illuminate\Container\Container));
        $capsule->setAsGlobal();
        //开启Eloquent ORM
        $capsule->bootEloquent();
        class_alias('\Illuminate\Database\Capsule\Manager', 'DB');
    }
}
