<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bairro extends Model
{

    protected $table = 'bairros';

	protected $fillable = [ 'nome', 'cidade_id' ];

	// JOINS
    public function cidade(){
        return $this->hasOne( 'App\Cidade', 'id', 'cidade_id' );
    }
    
}