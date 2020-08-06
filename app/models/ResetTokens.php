<?php

use Phalcon\Mvc\Model;

class ResetTokens extends Model
{
    public $id;
    public $userId;
    public $expiration;
}