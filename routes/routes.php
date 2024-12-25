<?php

use Libs\Router\CommandHandler;
use Controllers\start;
use Libs\Router\CallbackQueryHandler;

$router->addRoute(new CommandHandler("/start", [start::class, "start"]));

$router->addRoute(new CallbackQueryHandler([start::class, "callback1"]));