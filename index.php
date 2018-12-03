<?php
require 'telegram.php';

define('TELEGRAM_BOT_API_URI', 'https://api.telegram.org/bot');

$bot_token = getenv('TELEGRAM_BOT_TOKEN');
$app_name = getenv('HEROKU_APP_NAME');

$uri = $_SERVER['REQUEST_URI'];
switch($uri)
{
    case '/'.$bot_token:
    {
        $result = request();
        $telegram = new telegram($bot_token);
        $telegram->parse($result);
        break;
    }
    case '/getMe':
    {
        $telegram = new telegram($bot_token);
        $result = $telegram->getMe();
        dump($result);
        break;
    }
    case '/getWebhook':
    {
        $telegram = new telegram($bot_token);
        $result = $telegram->getWebhookInfo();
        dump($result);
        break;
    }
    case '/setWebhook':
    {
        $bot_webhook = "https://" . $app_name . '.herokuapp.com/' . $bot_token;
        $telegram_url = TELEGRAM_BOT_API_URI.$bot_token;
        $result = file_get_contents($telegram_url.'/setWebhook?url=' . $bot_webhook);
        dump($result);
        break;
    }
    case '/unsetWebhook':
    {
        $params = array(
            'url' => null
        );
        $telegram = new telegram($bot_token);
        $result = $telegram->setWebhook($params);
        dump($result);
        break;
    }
    default:
    {
        echo 'ok';
    }
}
function request()
{
	$postdata = file_get_contents("php://input");
	$json = json_decode($postdata, true);
	if($json)
		return $json;
	return $postdata;
}
function dump($var){
    echo "<pre>";
    print_r($var);
    echo "</pre>";
}