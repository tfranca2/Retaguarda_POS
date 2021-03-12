<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Etapa extends Model
{
    protected $table = 'etapas';
	protected $fillable = [ 'descricao', 'data' ];
}
