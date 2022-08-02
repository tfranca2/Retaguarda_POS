<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Matriz5015 extends Model
{
    protected $table = 'matriz_50_15';
	protected $fillable = [ 'bilhete', 'combinacoes' ];
	protected $hidden = [ 'id', 'created_at', 'updated_at' ];
}
