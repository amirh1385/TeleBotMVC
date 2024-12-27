<?php
namespace Models;

use Illuminate\Database\Eloquent\Model;

class ConversationH extends Model{
    protected $fillable = ['user_id', 'chat_id', 'conversation_id'];
}