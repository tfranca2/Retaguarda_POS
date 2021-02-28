<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venda extends Model
{

    protected $table = 'vendas';

	protected $fillable = [ 'dispositivo_id', 'etapa_id' ];

	// JOINS
    public function dispositivo(){
        return $this->hasMany( 'App\Dispositivo', 'id', 'dispositivo_id' );
    }

    public function etapa(){
        return $this->hasMany( 'App\Etapa', 'id', 'etapa_id' );
    }

}
