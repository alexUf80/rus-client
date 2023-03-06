<?php

class UsersORM extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 's_users';
    protected $guarded = [];
    public $timestamps = false;
}