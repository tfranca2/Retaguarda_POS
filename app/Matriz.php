<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Matriz extends Model
{
    protected $table = 'matrizes';
	protected $fillable = [ 'bilhete', 'combinacoes' ];
	protected $hidden = [ 'id', 'created_at', 'updated_at' ];

	function __construct(){
	    $this->table = env('MATRIZ', 'matrizes');
	}
}
