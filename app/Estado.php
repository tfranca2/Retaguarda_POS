<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{

    protected $table = 'estados';

	protected $fillable = [ 'nome', 'uf' ];

	// JOINS
    public function cidades(){
        return $this->hasMany( 'App\Cidade', 'id', 'cidade_id' );
    }

}
