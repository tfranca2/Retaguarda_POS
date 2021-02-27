<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrestadorBanco extends Model
{

	protected $table = 'prestador_banco';
    protected $fillable = [ 'prestador_id', 'banco', 'agencia', 'conta', 'operacao' ];

}
