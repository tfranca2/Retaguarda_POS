<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cidade extends Model
{

    protected $table = 'cidades';

	protected $fillable = [ 'nome', 'populacao' ];

	// JOINS
    public function bairros(){
        return $this->hasMany( 'App\Bairro', 'bairro_id', 'id' );
    }

}
