<?php

namespace Libs\Router;

use Libs\TelegramDecoder;
use Libs\TelegramDecoder\TelegramResponse;
class Router{
    public $routes = [];

    public function addRoute ($handler){
        $this->routes[] = $handler;
    }

    public function handle($update){
        foreach ($this->routes as $key => $value) {
            $value->handle($update);
        }
    }
}

class CommandHandler{
    public string $command;
    public $func;
    public function __construct($command, $func) {
        $this->command = $command;
        $this->func = $func;
    }

    public function handle(TelegramResponse $update){
        if(!isset($update->message)) return;
        if(str_starts_with($update->message->text, $this->command)){
            call_user_func($this->func, $update);
        }
    }
}