<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Premiacao extends Model
{
    protected $table = 'premiacoes';
    protected $fillable = [ 'seq', 'premiacao', 'descricao','bruto','liquido','etapa_id' ];
    protected $hidden = [ 'id', 'etapa_id', 'created_at', 'updated_at', 'deleted_at' ];

    public function etapa(){
        return $this->hasOne( 'App\Etapa', 'id', 'etapa_id' );
    }
}
