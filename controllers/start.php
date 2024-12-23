<?php

namespace Controllers;

use Libs\TelegramDecoder\TelegramResponse;

class start{
    public static function start(TelegramResponse $update){
        $view1 = view("welcome.html");
        $update->message->reply_text($view1["text"], $view1['keyboard']);
    }
}