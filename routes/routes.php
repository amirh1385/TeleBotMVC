<?php

use Libs\Router\CommandHandler;
use Controllers\start;
use Libs\Router\CallbackQueryHandler;
use Libs\Router\MessageHandler;

$router->addRoute(new MessageHandler([start::class, "start"]));

$router->addRoute(new CallbackQueryHandler([start::class, "callback1"]));