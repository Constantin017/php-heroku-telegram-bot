<?php
require 'app.php';
require 'telegram.php';

define('TELEGRAM_BOT_API_URI', 'https://api.telegram.org/bot');

$bot_token = getenv('TELEGRAM_BOT_TOKEN');
$bot_name = getenv('TELEGRAM_BOT_NAME');
$app_name = getenv('HEROKU_APP_NAME');

app::setName($app_name);
app::setToken($bot_token);


if ( $_SERVER['REQUEST_URI'] == '/'.$bot_token ){
    $telegram = new TelegramBot($bot_token, $bot_name);
    $telegram->serve();
    exit();
}

if ( app::access() ){

    switch($_SERVER['REQUEST_URI'])
    {
        case '/getMe':
        {
            $telegram = new TelegramBot($bot_token, $bot_name);
            $result = $telegram->getMe();
            app::dump($result);
            break;
        }
        case '/getWebhook':
        {
            $telegram = new TelegramBot($bot_token, $bot_name);
            $result = $telegram->getWebhookInfo();
            app::dump($result);
            break;
        }
        case '/setWebhook':
        {
            $bot_webhook = "https://" . $app_name . '.herokuapp.com/' . $bot_token;
            $telegram_url = TELEGRAM_BOT_API_URI.$bot_token;
            $result = file_get_contents($telegram_url.'/setWebhook?url=' . $bot_webhook);
            app::dump($result);
            break;
        }
        case '/unsetWebhook':
        {
            $telegram = new TelegramBot($bot_token, $bot_name);
            $result = $telegram->setWebhook(['url'=>null]);
            app::dump($result);
            break;
        }
        case '/deleteWebhook':
        {
            $telegram = new TelegramBot($bot_token, $bot_name);
            $result = $telegram->deleteWebhook ();
            app::dump($result);
            break;
        }
        case '/getUpdates':
        {
            $telegram = new TelegramBot($bot_token, $bot_name);
            $result = $telegram->getUpdates();
            app::dump($result);
        }
        default:
        {
            exit("ACCESS GRANTED");
        }
    } // switch
    exit();
}

exit('OK');