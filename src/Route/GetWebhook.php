<?php

namespace Application\Route;

use Application\Route\Route;
use Application\TelegramBot;
use Application\Web;

class GetWebhook extends Route
{
    public function execute()
    {
        $telegram = new TelegramBot(TELEGRAM_BOT_TOKEN);
        $result = $telegram->getWebhookInfo();
        Web::dump($result);
    }
}