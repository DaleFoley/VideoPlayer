<?php

use Phalcon\Mvc\Model;

class UserVideos extends Model
{
    public $id;
    public $user_id;
    public $name;
    public $thumbnail;
    public $length;
    public $size;
    public $path;
    public $created;
}