<?php

namespace Application\Route;

use Application\Route\Route;
use Application\TelegramBot;
use Application\Web;

class UnsetWebhook extends Route
{
    public function execute()
    {
        $telegram = new TelegramBot(Web::getToken());
        $result = $telegram->setWebhook(['url'=>null]);
        Web::dump($result);
    }
}