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
        if($update->message->text == $this->command){
            call_user_func($this->func, $update);
        }
    }
}

class CallbackQueryHandler{
    public $func;
    public $callback_query;

    public function __construct($func, $callback_query = null) {
        $this->func = $func;
        $this->callback_query = $callback_query;
    }

    public function handle(TelegramResponse $update){
        if(!isset($update->callback_query)) return;
        if($this->callback_query == null) call_user_func($this->func, $update);;
        if($update->callback_query->data == $this->callback_query){
            call_user_func($this->func, $update);
        }
    }
}

class MessageHandler{
    public $func;
    public function __construct($func) {
        $this->func = $func;
    }

    public function handle(TelegramResponse $update){
        if(!isset($update->message)) return;
        call_user_func($this->func, $update);
    }
}