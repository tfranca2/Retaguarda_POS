<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Perfil extends Model
{
	
	// use SoftDeletes;

	protected $table = 'perfil';
    protected $fillable = [ 'nome', 'deleted_at' ];

}
