<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrestadorServico extends Model
{

	protected $table = 'prestador_servico';
    protected $fillable = [ 'prestador_id', 'servico_id', 'campos', 'experiencia' ];

    // JOINS
    public function servico(){
        return $this->hasOne( 'App\Servico', 'id', 'servico_id' );
    }

}
