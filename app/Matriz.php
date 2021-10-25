<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Matriz extends Model
{
    protected $table = 'matrizes';
	protected $fillable = [ 'bilhete', 'combinacoes' ];

	function __construct(){
	    $this->table = env('MATRIZ', 'matrizes');
	}
}
