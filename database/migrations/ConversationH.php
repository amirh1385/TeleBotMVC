<?php
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

// اجرای مایگریشن برای ساخت جدول users
Capsule::schema()->create(' ConversationH', function (Blueprint $table) {
    $table->increments('id');
    $table->string('user_id');
    $table->string('chat_id');
    $table->string('conversation_id');
    $table->timestamps();
});