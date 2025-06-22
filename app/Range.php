<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Range extends Model
{
    protected $table = 'range';
	protected $fillable = [ 'etapa_id', 'tipo', 'chances', 'inicio', 'final', 'intervalo', 'valor', 'comissao' ];
	protected $hidden = [ 'id', 'etapa_id', 'created_at', 'updated_at' ];
}
