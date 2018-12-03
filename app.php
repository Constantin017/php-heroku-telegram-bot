<?php

class app
{

    public static function dump($var){
        echo "<pre>";
        print_r($var);
        echo "</pre>";
    }

    public static function access($bot_token)
    {
        return ( isset($_COOKIE['token']) && $_COOKIE['token'] == $bot_token ) ? true : false;
    }
}