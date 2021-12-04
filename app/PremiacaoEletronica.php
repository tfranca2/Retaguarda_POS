<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PremiacaoEletronica extends Model
{
    protected $table = 'premiacoes_eletronica';
    protected $fillable = [ 'numero', 'descricao','bruto','liquido','etapa_id' ];
    protected $hidden = [ 'id', 'etapa_id', 'created_at', 'updated_at', 'deleted_at' ];

    public function etapa(){
        return $this->hasOne( 'App\Etapa', 'id', 'etapa_id' );
    }
}
