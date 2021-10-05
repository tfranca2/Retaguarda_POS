<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Matriz extends Model
{
    // protected $table = 'matrizes';
    protected $table = 'matriz_50_15';
	protected $fillable = [ 'bilhete', 'combinacoes' ];
}
