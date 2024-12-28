<?php

namespace Libs\Router;

use Libs\TelegramDecoder;
use Libs\TelegramDecoder\TelegramResponse;
use Models\ConversationH;

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
        if(!isset($update->message)) return null;
        if($update->message->text == $this->command){
            return call_user_func($this->func, $update);
        }
        return null;
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
        if(!isset($update->callback_query)) return null;
        if($this->callback_query == null) return call_user_func($this->func, $update);
        if($update->callback_query->data == $this->callback_query){
            return call_user_func($this->func, $update);
        }
        return null;
    }
}

class MessageHandler{
    public $func;
    public function __construct($func) {
        $this->func = $func;
    }

    public function handle(TelegramResponse $update){
        if(!isset($update->message)) return null;
        return call_user_func($this->func, $update);
    }
}

class State{
    public $handlers = [];

    public function __construct($handlers) {
        $this->handlers = $handlers;
    }

    public function handle(TelegramResponse $update){
        foreach ($this->handlers as $key => $value) {
            $result = $value->handle($update);
            if ($result !== null) {
                return $result;
            }
        }
        return null;
    }
}

class Conversation{
    public $start;
    public $states;

    public function __construct($start, $states) {
        $this->start = $start;
        $this->states = $states;
    }

    public function handle(TelegramResponse $update){
        $chat_id = isset($update->message) ? 
            $update->message->chat->id : 
            $update->callback_query->message->chat->id;
        
        $user_id = isset($update->message) ? 
            $update->message->from->id : 
            $update->callback_query->from->id;

        // Get current state from cache
        $current_state = \BotCache\BotCache::getCache($chat_id, $user_id, 'conversation_state');

        // If no state or invalid state, run start handlers
        if ($current_state === null || !isset($this->states[$current_state])) {
            // اجرای تمام هندلرهای استارت
            foreach ($this->start as $handler) { 
                $result = $handler->handle($update);
                
                if ($result !== null) {
                    if ($result === 0) {
                        // End conversation
                        \BotCache\BotCache::setCache($chat_id, $user_id, 'conversation_state', null);
                        return;
                    } elseif (is_numeric($result)) {
                        // Save new state
                        \BotCache\BotCache::setCache($chat_id, $user_id, 'conversation_state', $result);
                        return;
                    }
                }
            }
            return;
        }

        // Handle current state
        $result = $this->states[$current_state]->handle($update);
        
        if ($result === 0) {
            // End conversation
            \BotCache\BotCache::setCache($chat_id, $user_id, 'conversation_state', null);
        } elseif (is_numeric($result)) {
            // Update state
            \BotCache\BotCache::setCache($chat_id, $user_id, 'conversation_state', $result);
        }
    }
}