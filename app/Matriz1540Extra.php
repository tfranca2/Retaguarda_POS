<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Matriz1540Extra extends Model
{
    protected $table = 'matriz_15_40_extra';
	protected $fillable = [ 'bilhete', 'combinacoes' ];
	protected $hidden = [ 'id', 'created_at', 'updated_at' ];
}
