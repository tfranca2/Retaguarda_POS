<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Servico extends Model
{
	
	use SoftDeletes;

	protected $table = 'servico';
    protected $fillable = [ 'nome', 'campos', 'pos_servico', 'deleted_at' ];

    public function contratacoes(){
        return $this->hasMany( 'App\Contratacao', 'servico_id', 'id' );
    }

}
