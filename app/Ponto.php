<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ponto extends Model
{
	
    protected $table = 'pontos';

	protected $fillable = [ 'tipo', 'nome', 'responsavel', 'endereco', 'telefone', 'telefone2', 'cpf_cnpj', 'rg', 'funcionamento', 'encerramento', 'ponto_referencia', 'observacao', 'distribuidor_id', 'cidade_id', 'bairro_id' ];

	// JOINS
    public function distribuidor(){
        return $this->hasOne( 'App\Distribuidor', 'id', 'distribuidor_id' );
    }

    public function cidade(){
        return $this->hasOne( 'App\Cidade', 'id', 'cidade_id' );
    }

    public function bairro(){
        return $this->hasOne( 'App\Bairro', 'id', 'bairro_id' );
    }
    
}
