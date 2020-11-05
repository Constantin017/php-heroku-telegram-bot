<?php

namespace Application\Route;

use Application\Route\Route;
use Application\Web;

class UnsetWebhook extends Route
{    
    public function execute()
    {
        $bot_webhook = "https://".APPLICATION_NAME.".herokuapp.com/";
        $bot_webhook .= TELEGRAM_BOT_TOKEN;
        $telegram_url = TELEGRAM_BOT_API_URI.TELEGRAM_BOT_TOKEN;
        $result = file_get_contents($telegram_url.'/setWebhook?url=' . $bot_webhook);
        Web::dump($result);
    }
}
