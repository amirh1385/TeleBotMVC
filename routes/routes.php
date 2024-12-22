<?php

use Libs\Router\CommandHandler;
use Controllers\start;

$router->addRoute(new CommandHandler("/start", [start::class, "start"]));