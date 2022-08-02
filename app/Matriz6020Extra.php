<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Matriz6020Extra extends Model
{
    protected $table = 'matriz_60_20_extra';
	protected $fillable = [ 'bilhete', 'combinacoes' ];
	protected $hidden = [ 'id', 'created_at', 'updated_at' ];
}
