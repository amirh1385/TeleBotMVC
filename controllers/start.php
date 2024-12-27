<?php

namespace Controllers;

use Libs\TelegramDecoder\TelegramResponse;
use Libs\View\View;

class start{
    public static function start(TelegramResponse $update){
    // $update->message->reply_text(text:"hello world");
    }

    public static function callback1(TelegramResponse $update){
        $update->callback_query->answer();
        $update->callback_query->message->chat->sendMessage("hello");
        $update->callback_query->message->deleteMessage();
    }
}