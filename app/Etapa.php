<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Etapa extends Model
{
    
    protected $table = 'etapas';

	protected $fillable = [ 'etapa', 'descricao','data','range_inicial','range_final','tipo', 'intervalo', 'valor_simples', 'valor_duplo', 'valor_triplo', 'v_comissao_simples', 'v_comissao_duplo', 'v_comissao_triplo', 'ativa' ];

	public static function ativa(){
		return \DB::table( with( new Etapa )->getTable() )->where('ativa','1')->first();
	}

	public function premiacao(){
		return $this->hasMany('App\Premiacao', 'etapa_id', 'id')->orderBy('seq', 'ASC');
	}

	public function premiacaoEletronica(){
		return $this->hasMany('App\PremiacaoEletronica', 'etapa_id', 'id')->orderBy('numero', 'ASC');
	}
}
