<?php

use Libs\Router\CommandHandler;
use Controllers\start;

// call_user_func([start::class, "start"], "");
error_log("fgfld");

$router->addRoute(new CommandHandler("/start", [start::class, "start"]));