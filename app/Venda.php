<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Venda extends Model
{
    use SoftDeletes;
    
    protected $table = 'vendas';

	protected $fillable = [ 'dispositivo_id', 'etapa_id', 'matriz_id', 'nome', 'cpf', 'telefone', 'ip', 'ceder_resgate', 'confirmada', 'pdv', 'key', 'protocolo', 'cidade_id', 'cep', 'matriz' ];

    protected $hidden = [ 'dispositivo_id', 'etapa_id', 'matriz_id', 'ip', 'ceder_resgate', 'pdv', 'cidade_id', 'updated_at', 'deleted_at', 'matriz'];

	// JOINS
    public function dispositivo(){
        return $this->hasOne( 'App\Dispositivo', 'id', 'dispositivo_id' )->withTrashed()->with('distribuidor');
    }

    public function etapa(){
        return $this->hasOne( 'App\Etapa', 'id', 'etapa_id' );
    }

    public function matrizes(){
        // return $this->hasMany( 'App\VendaMatriz', 'venda_id', 'id' )->with('matriz');
        $matriz = 'matriz';
        if( $this->matriz )
            $matriz = $this->matriz;
        $matrizes = $this->hasMany( 'App\VendaMatriz', 'venda_id', 'id' )->with($matriz)->get();
        $response = array();
        foreach($matrizes as $m){
            $relation = $m->relations[$matriz]->attributes;
            unset($relation['id']);
            unset($relation['created_at']);
            unset($relation['updated_at']);
            $response[]['matriz'] = $relation;
        }
        return $response;
    }

}
