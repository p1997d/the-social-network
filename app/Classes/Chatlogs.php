<?php

namespace App\Classes;

class Chatlogs
{
    public $id, $type, $avatar, $online, $title, $lastMessage, $unreadMessagesCount;

    public function __construct($model, $type)
    {
        $this->id = $model->id;
        $this->type = $type;
        $this->avatar = $model->avatar();
        $this->online = class_basename($model) == "User" ? $model->online() : null;
        $this->title = class_basename($model) == "User" ? "$model->firstname $model->surname" : $model->title;
        $this->lastMessage = $model->lastMessage();
        $this->unreadMessagesCount = $model->unreadMessagesCount();
    }
}
