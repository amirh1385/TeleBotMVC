<?php

namespace Controllers;

use Libs\TelegramDecoder;
use Libs\TelegramDecoder\TelegramResponse;

class start{
    public static function start(TelegramResponse $update){
        $view1 = view("welcome");
        $update->message->reply_text($view1, [
            [['text' => 'دکمه 1', 'callback_data' => 'action_1']],
            [['text' => 'دکمه 2', 'callback_data' => 'action_2']]
        ]); 
    }
}