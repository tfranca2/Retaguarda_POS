<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Matriz1530Extra extends Model
{
    protected $table = 'matriz_15_30_extra';
	protected $fillable = [ 'bilhete', 'combinacoes' ];
	protected $hidden = [ 'id', 'created_at', 'updated_at' ];
}
