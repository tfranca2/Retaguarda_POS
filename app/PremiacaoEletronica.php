<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PremiacaoEletronica extends Model
{
    protected $table = 'premiacoes_eletronica';

    protected $fillable = [ 'numero', 'descricao','bruto','liquido','etapa_id' ];

    public function etapa(){
        return $this->hasOne( 'App\Etapa', 'id', 'etapa_id' );
    }
}
