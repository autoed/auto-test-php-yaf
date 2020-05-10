<?php

use Yaf\Controller_Abstract;

class BaseController extends Controller_Abstract
{

    protected $homeUrl;
    protected $notifyData = [];
    protected $requestData = [];

    /**
     * User:  fomo3d.wiki
     * Email: fomo3d.wiki@gmail.com
     * Date: 2020/5/10
     * @param $str
     * @return array|string
     */
    function filterStr($str)
    {
        if (!is_array($str)) {
            return addslashes($str);
        }
        foreach ($str as $key => $val) {
            $str[$key] = $this->filterStr($val);
        }
        return $str;
    }

    /**
     * User:  fomo3d.wiki
     * Email: fomo3d.wiki@gmail.com
     * Date: 2020/5/10
     * @param $key
     * @param $default
     * @param bool $filter
     * @return array|string
     */
    public function get($key, $default = null, $filter = FALSE)
    {
        $value = $this->getRequest()->get($key);
        if ($filter) {
            $value = $this->filterStr($value);
        }
        if ($value == null) {
            $value = $default;
        }
        return $value;
    }

    /**
     * User:  fomo3d.wiki
     * Email: fomo3d.wiki@gmail.com
     * Date: 2020/5/10
     * @param $key
     * @param null $default
     * @return |null
     */
    public function getPost($key, $default = null)
    {
        $params = $this->getRequest()->getPost($key);
        if ($params === null) {
            $params = $default;
        }
        return $params;
    }

    /**
     * User:  fomo3d.wiki
     * Email: fomo3d.wiki@gmail.com
     * Date: 2020/5/10
     * @param $key
     * @param bool $filter
     * @return mixed
     */
    public function getParam($key, $filter = TRUE)
    {
        if ($this->getRequest()->isGet()) {
            if ($filter) {
                return $this->filterStr($this->getRequest()->get($key));
            } else {
                return $this->getRequest()->get($key);
            }
        } else {
            if ($filter) {
                return $this->filterStr($this->getRequest()->getPost($key));
            } else {
                return $this->getRequest()->getPost($key);
            }
        }
    }

    /**
     * User:  fomo3d.wiki
     * Email: fomo3d.wiki@gmail.com
     * Date: 2020/5/10
     * @param $key
     * @param bool $filter
     * @return array
     */
    public function getQuery($key, $filter = TRUE)
    {
        if ($filter) {
            return $this->filterStr($this->getRequest()->getQuery($key));
        } else {
            return $this->getRequest()->getQuery($key);
        }
    }

    public function getSession($key)
    {
        return Yaf_Session::getInstance()->__get($key);
    }

    /**
     * User:  fomo3d.wiki
     * Email: fomo3d.wiki@gmail.com
     * Date: 2020/5/10
     * @param $key
     * @param $val
     */
    public function setSession($key, $val)
    {
        return Yaf_Session::getInstance()->__set($key, $val);
    }

    /**
     * User:  fomo3d.wiki
     * Email: fomo3d.wiki@gmail.com
     * Date: 2020/5/10
     * @param $key
     * @return mixed
     */
    public function unsetSession($key)
    {
        return Yaf_Session::getInstance()->offsetUnset($key);
    }

    /**
     * Clear cookie
     * User:  fomo3d.wiki
     * Email: fomo3d.wiki@gmail.com
     * Date: 2020/5/10
     * @param $key
     */
    public function clearCookie($key)
    {
        setCookie($key, '');
    }

    /**
     * Set COOKIE
     * User:  fomo3d.wiki
     * Email: fomo3d.wiki@gmail.com
     * Date: 2020/5/10
     * @param $key
     * @param $value
     * @param int $expire
     * @param string $path
     * @param string $domain
     * @param bool $httpOnly
     */
    public function setCookie($key, $value, $expire = 3600, $path = '/', $domain = '', $httpOnly = FALSE)
    {
        setCookie($key, $value, CUR_TIMESTAMP + $expire, $path, $domain, $httpOnly);
    }

    /**
     * 获取cookie
     * User:  fomo3d.wiki
     * Email: fomo3d.wiki@gmail.com
     * Date: 2020/5/10
     * @param $key
     * @return string
     */
    public function getCookie($key)
    {
        return trim($_COOKIE[$key]);
    }

    /**
     * Get limit
     * User:  fomo3d.wiki
     * Email: fomo3d.wiki@gmail.com
     * Date: 2020/5/10
     * @param int $size
     * @return string
     */
    public function getLimit($size = 10)
    {
        $page = $this->get('page');
        if (!$page) {
            $page = $this->getPost('page');
        }

        $page = $page ? $page : 1;

        $start = ($page - 1) * $size;
        $limit = $start . ',' . $size;

        return $limit;
    }

    /**
     * 获取Pid列表
     * User:  fomo3d.wiki
     * Email: fomo3d.wiki@gmail.com
     * Date: 2020/5/10
     * @param $name
     * @return array
     */
    public function getPidList($name)
    {
        $result = array();
        exec('ps-ef|grep"' . $name . '"|grep-vgrep|grep-vvi|grep-vsudo|awk\'{print$2}\'|sort', $result);
        return $result;
    }

    /**
     * 检查进程是否已存在
     * User:  fomo3d.wiki
     * Email: fomo3d.wiki@gmail.com
     * Date: 2020/5/10
     * @param $name
     * @return bool
     */
    public function getPidExists($name)
    {
        $result = array();
        $pid = posix_getpid();
        exec("ps-ef|grep{$name}|grep-vgrep|grep-vsudo|grep-v{$pid}|grep-vvi|grep-v/bin/sh", $result);
        return empty($result) ? false : true;
    }
}