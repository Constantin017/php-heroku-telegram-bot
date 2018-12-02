<?php
class telegram
{
    private $uri = 'https://api.telegram.org/bot';

    public function __construct($bot_token)
    {
        $this->uri .= $bot_token;
    }

    public function __call($name, $args)
    {
        return $this->call($name, count($args) === 0 ? [] : $args[0]);
    }

    public function call($method, $params = array())
    {
        $handle = curl_init($this->uri);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($handle, CURLOPT_TIMEOUT, 60);
        
        curl_setopt($handle, CURLOPT_POST, true);
        curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($params));

        $response = curl_exec($handle);

        return ($response) ? json_decode($response, true) : false;
    }

}