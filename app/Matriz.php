<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Matriz extends Model
{
    protected $table = env('MATRIZ', 'matrizes');
	protected $fillable = [ 'bilhete', 'combinacoes' ];
}
