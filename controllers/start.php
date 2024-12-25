<?php

namespace Controllers;

use Libs\TelegramDecoder\TelegramResponse;
use Libs\View\View;

class start{
    public static function start(TelegramResponse $update){
       $view1 = View::returnView("welcome");
       $update->message->reply_text(text:$view1->text, reply_keyboard:$view1->inline_keyboard)->text;
    }

    public static function callback1(TelegramResponse $update){
        $update->callback_query->answer();
        $update->callback_query->message->chat->sendMessage("hello");
        $update->callback_query->message->deleteMessage();
    }
}