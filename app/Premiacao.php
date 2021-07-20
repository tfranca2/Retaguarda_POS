<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Premiacao extends Model
{
    protected $table = 'premiacoes';

    protected $fillable = [ 'seq', 'premiacao', 'descricao','bruto','liquido','etapa_id' ];

    public function etapa(){
        return $this->hasOne( 'App\Etapa', 'id', 'etapa_id' );
    }
}
