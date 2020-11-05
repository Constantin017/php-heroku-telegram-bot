<?php

namespace Application\Route;

use Application\Route\Route;
use Application\TelegramBot;
use Application\Web;

class GetMe extends Route
{
    public function execute()
    {
        $telegram = new TelegramBot(Web::getToken());
        $result = $telegram->getMe();
        Web::dump($result);
    }
}