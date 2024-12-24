<?php

namespace Controllers;

use Libs\TelegramDecoder\TelegramResponse;

class start{
    public static function start(TelegramResponse $update){
        $view1 = view("welcome.html");
        $reply_keyboard = [
            [
                ['text' => 'Hello', 'callback_data' => 'callback_hello']
            ]
        ];
        $update->message->reply_text(text:"xd");
    }
}