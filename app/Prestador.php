<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prestador extends Model
{
	
	// use SoftDeletes;

	protected $table = 'prestador';
    protected $fillable = [ 'nome', 'usuario_id', 'deleted_at', 'rg', 'cep', 'endereco', 'numero', 'bairro', 'cidade', 'estado', 'celular', 'fixo', 'observacoes', 'latitude', 'longitude', 'mae', 'pai', 'checkantecedentes' ];

    // JOINS
    public function usuario(){
        return $this->hasOne( 'App\User', 'id', 'usuario_id' );
    }

    public function servicos(){
        return $this->hasMany( 'App\PrestadorServico', 'prestador_id', 'id' )->with('servico');
    }

    public function bancos(){
        return $this->hasMany( 'App\PrestadorBanco', 'prestador_id', 'id' );
    }

    public function documentos(){
        return $this->hasMany( 'App\PrestadorDocumento', 'prestador_id', 'id' );
    }

    public function referencias(){
        return $this->hasMany( 'App\PrestadorReferencia', 'prestador_id', 'id' );
    }

}
