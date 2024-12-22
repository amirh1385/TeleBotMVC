<?php

namespace Controllers;

use Libs\TelegramDecoder;
use Libs\TelegramDecoder\TelegramResponse;

class start{
    public static function start(TelegramResponse $update){
        $view1 = view("welcome.html");
        error_log(json_encode($view1["keyboard"]));
        // $update->message->reply_text(json_encode($view1['keyboard']), $view1['keyboard']); 
    }
}