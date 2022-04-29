<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Venda extends Model
{
    use SoftDeletes;
    
    protected $table = 'vendas';

	protected $fillable = [ 'dispositivo_id', 'etapa_id', 'matriz_id', 'nome', 'cpf', 'telefone', 'ip', 'ceder_resgate', 'confirmada', 'pdv', 'key', 'protocolo', 'cidade_id', 'cep' ];

    protected $hidden = [ 'dispositivo_id', 'etapa_id', 'matriz_id', 'ip', 'ceder_resgate', 'pdv', 'cidade_id', 'updated_at', 'deleted_at'];

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
