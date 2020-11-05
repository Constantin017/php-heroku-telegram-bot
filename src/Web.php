<?php

namespace Application;

use Application\Route\Route;

class Web
{

    protected static $name = '';
    protected static $bot_token = '';
    protected static $request = '';
    protected static $routes = [];

    public static function request(string $request)
    {
        self::$request = $request;
    }

    public static function setName(string $name)
    {
        self::$name = $name;
    }

    public static function setToken(string $bot_token)
    {
        self::$bot_token = $bot_token;
    }

    public static function getToken()
    {
        return self::$bot_token;
    }

    public static function dump($var){
        echo "<pre>";
        print_r($var);
        echo "</pre>";
    }

    public static function access()
    {
        return ( isset($_COOKIE['token']) && $_COOKIE['token'] == self::$bot_token ) ? true : false;
    }
    
    public static function route(Route $route)
    {
        self::$routes[$route->getRoute()] = $route;
    }

    public static function serve()
    {
        if (self::$routes[self::$request]) {
          $route = self::$routes[self::$request];
          $route->execute();
        }
    }

}