<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrestadorReferencia extends Model
{

	protected $table = 'prestador_referencia';
    protected $fillable = [ 'prestador_id', 'empresa', 'contato', 'email', 'telefone' ];

}
