<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contratacao extends Model
{

	protected $table = 'contratacao';
    protected $fillable = [ 'cliente_id', 'servico_id', 'prestador_id', 'inicio', 'fim', 'tipo_imovel', 'valor', 'gorjeta', 'observacoes', 'campos', 'iniciado', 'concluido', 'pos_servico', 'fotos', 'transaction_code', 'rating', 'justificativa', 'recorrencias' ];

	// JOINS
    public function cliente(){
        return $this->hasOne( 'App\Cliente', 'id', 'cliente_id' );
    }

    public function servico(){
        return $this->hasOne( 'App\Servico', 'id', 'servico_id' );
    }

    public function prestador(){
        return $this->hasOne( 'App\Prestador', 'id', 'prestador_id' );
    }

    public function pagamento(){
        return $this->hasOne( 'App\Payment', 'transaction_code', 'transaction_code' );
    }

}
