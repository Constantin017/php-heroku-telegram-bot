<?php

use Application\Web;
use Application\TelegramBot;
use Application\Route\GetMe;
use Application\Route\GetWebhook;
use Application\Route\SetWebhook;
use Application\Route\UnsetWebhook;

define('TELEGRAM_BOT_API_URI', 'https://api.telegram.org/bot');
define('TELEGRAM_BOT_TOKEN', getenv('TELEGRAM_BOT_TOKEN'));
define('TELEGRAM_BOT_NAME', getenv('TELEGRAM_BOT_NAME'));
define('APPLICATION_NAME', getenv('HEROKU_APP_NAME'));

$bot_token = getenv('TELEGRAM_BOT_TOKEN');
$bot_name = getenv('TELEGRAM_BOT_NAME');
$app_name = getenv('HEROKU_APP_NAME');

Web::setName($app_name);
Web::setToken($bot_token);


if ($_SERVER['REQUEST_URI'] == '/'.$bot_token) {
    $telegram = new TelegramBot($bot_token, $bot_name);
    $telegram->serve();
    exit();
}

Web::request($_SERVER['REQUEST_URI']);
Web::route(new GetMe(true, '/getMe'));
Web::route(new GetWebhook(true, '/getWebhook'));
Web::route(new SetWebhook(true, '/setWebhook'));
Web::route(new UnsetWebhook(true, '/unsetWebhook'));
Web::serve();
