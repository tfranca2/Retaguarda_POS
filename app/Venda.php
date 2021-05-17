<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venda extends Model
{

    protected $table = 'vendas';

	protected $fillable = [ 'dispositivo_id', 'etapa_id', 'matriz_id', 'nome', 'cpf', 'telefone', 'ip', 'ceder_resgate' ];

	// JOINS
    public function dispositivo(){
        return $this->hasOne( 'App\Dispositivo', 'id', 'dispositivo_id' )->withTrashed()->with('distribuidor');
    }

    public function etapa(){
        return $this->hasOne( 'App\Etapa', 'id', 'etapa_id' );
    }

    public function matrizes(){
        return $this->hasMany( 'App\VendaMatriz', 'venda_id', 'id' )->with('matriz');
    }

}
