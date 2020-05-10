<?php

/**
 * Class Routes
 * User:  fomo3d.wiki
 * Email: fomo3d.wiki@gmail.com
 * Date: 2020/5/10
 */
class Routes
{
    public static $get  = [];
    public static $post = [];
    public static $any  = [];

    /**
     * User:  fomo3d.wiki
     * Email: fomo3d.wiki@gmail.com
     * Date: 2020/5/10
     * @param $route
     * @param $action
     */
    public static function get($route, $action)
    {
        if ($route[0] != '/') {
            $route = '/' . $route;
        }
        if ($route[strlen($route) - 1] != '/') {
            $route = $route . '/';
        }

        self::$get[$route] = $action;
    }

    /**
     * User:  fomo3d.wiki
     * Email: fomo3d.wiki@gmail.com
     * Date: 2020/5/10
     * @param $route
     * @param $action
     */
    public static function post($route, $action)
    {
        if ($route[0] != '/') {
            $route = '/' . $route;
        }
        if ($route[strlen($route) - 1] != '/') {
            $route = $route . '/';
        }
        self::$post[$route] = $action;
    }

    /**
     * User:  fomo3d.wiki
     * Email: fomo3d.wiki@gmail.com
     * Date: 2020/5/10
     * @param $route
     * @param $action
     */
    public static function any($route, $action)
    {
        if ($route[0] != '/') {
            $route = '/' . $route;
        }
        if ($route[strlen($route) - 1] != '/') {
            $route = $route . '/';
        }
        self::$any[$route] = $action;
    }

    /**
     * 路由 URL 规则化处理
     * User:  fomo3d.wiki
     * Email: fomo3d.wiki@gmail.com
     * Date: 2020/5/10
     * @param $str
     * @return string
     */
    public static function route_uri_format($str)
    {
        if ($str[0] != '/') {
            $str = '/' . $str;
        }
        if ($str[strlen($str) - 1] != '/') {
            $str = $str . '/';
        }
        return $str;
    }
}