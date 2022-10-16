<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tracking extends Model
{
    protected $table = 'tracking';
    protected $fillable = [ 'session_id', 'ip', 'location', 'referer', 'device', 'os', 'browser', 'agent', 'resolution', 'orientation', 'updated_at' ];
    protected $hidden = [ 'id', 'created_at', 'updated_at', 'deleted_at'];
}
