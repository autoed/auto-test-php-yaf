<?php

use Yaf\Bootstrap_Abstract;

/**
 * Class Bootstrap
 * User:  fomo3d.wiki
 * Email: fomo3d.wiki@gmail.com
 * Date: 2020/5/5
 */
class Bootstrap extends Bootstrap_Abstract
{
    /** @var object config */
	private $config;

    /**
     * 初始化错误,要放在最前面
     */
    public function _initErrors()
    {
        //如果为开发环境,打开所有错误提示
        if (Yaf\ENVIRON === 'develop') {
            error_reporting(E_ALL);//使用error_reporting来定义哪些级别错误可以触发
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
        }
    }

    /**
     * 加载vendor下的文件
     */
    public function _initLoader()
    {
        \Yaf\Loader::import(APP_PATH . '/vendor/autoload.php');
    }

    /**
     * 配置
     */
    public function _initConfig()
    {
		//把配置保存起来
		$this->config = \Yaf\Application::app()->getConfig();
        \Yaf\Registry::set('config', $this->config);
	}

    /**
     * 日志
     * @param \Yaf\Dispatcher $dispatcher
     */
	public function _initLogger(\Yaf\Dispatcher $dispatcher)
    {
        //SocketLog
        if (Yaf\ENVIRON === 'develop') {
            if ($this->config->socketlog->enable) {
                //载入
                \Yaf\Loader::import('Common/Logger/slog.function.php');
                //配置SocketLog
                slog($this->config->socketlog->toArray(),'config');
            }
        }
    }

    /**
     * 插件
     * @param \Yaf\Dispatcher $dispatcher
     */
	public function _initPlugin(Yaf\Dispatcher $dispatcher)
    {
        /**
         * @var UserPlugin $userRegister
         */
        $userRegister = new UserPlugin();
        $dispatcher->registerPlugin($userRegister);
	}

    /**
     * 路由
     * @param \Yaf\Dispatcher $dispatcher
     */
	public function _initRoute(\Yaf\Dispatcher $dispatcher)
    {
		//在这里注册自己的路由协议,默认使用简单路由
	}

    /**
     * LocalName
     */
	public function _initLocalName()
    {

	}


    /**
     * View
     * @param \Yaf\Dispatcher $dispatcher
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
     * 初始化数据库分发器
     * @function _initDefaultDbAdapter
     * @author   jsyzchenchen@gmail.com
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


    /**
     * 公用函数载入
     */
    public function _initFunction()
    {
        \Yaf\Loader::import('Common/functions.php');
    }
}
