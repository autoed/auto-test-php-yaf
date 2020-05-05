<?php
/**
 * @name IndexController
 * @author root
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class IndexController extends \Yaf\Controller_Abstract
{
    public function indexAction()
    {
        $request = $this->getRequest();
        var_dump($request);
        echo "<h1></h1>";
        $response = $this->getResponse();
        var_dump($response);
        echo "<h1></h1>";
        var_dump($this->_router);

        $this->getView()->assign("content", "Hello Yaf  : index!");
    }
}
