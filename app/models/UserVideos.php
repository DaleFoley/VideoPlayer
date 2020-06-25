<?php

use Phalcon\Mvc\Model;

class UserVideos extends Model
{
    public $id;
    public $user_id;
    public $name;
    public $length;
    public $size;
    public $created;
}