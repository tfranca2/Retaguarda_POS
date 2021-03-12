<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Token_fcm extends Model
{
    protected $table = 'token_fcm';
    
    protected $fillable = [
        'user_id', 'fcm_token', 'device_token'
    ];
}
