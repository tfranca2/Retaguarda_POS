<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mural extends Model
{
	
	use SoftDeletes;

	protected $table = 'mural';
    protected $fillable = [ 'user_id', 'tipo', 'titulo', 'descricao', 'observacao', 'nome', 'telefone', 'email', 'fotos', 'inicio', 'fim', 'deleted_at' ];

    // JOINS
    public function usuario(){
        return $this->hasOne( 'App\User', 'id', 'user_id' );
    }

}
