<?php

use Libs\Router\CommandHandler;
use Controllers\start;
use Libs\Router\CallbackQueryHandler;
use Libs\Router\MessageHandler;
use Libs\Router\Conversation;
use Libs\Router\State;

$router->addRoute(new CommandHandler("/start", [start::class, "start"]));

$router->addRoute(new CallbackQueryHandler([start::class, "callback1"]));

$router->addRoute(new Conversation(
    [
        new CommandHandler("/startc", function ($update){
            $update->message->reply_text(text:"hello worldds");
            return 1;
        })
    ],
    [
        1 => new State([
            new CommandHandler("/startc2", function ($update){
                $update->message->reply_text(text:"hello world2");
                return 0;
            })
        ])
    ]
));