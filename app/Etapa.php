<?php

namespace App;

use Helper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Etapa extends Model
{
    
    protected $table = 'etapas';
	protected $fillable = [ 'etapa', 'descricao', 'data', 'range_inicial', 'range_final', 'tipo', 'intervalo', 'valor_simples', 'valor_duplo', 'valor_triplo', 'v_comissao_simples', 'v_comissao_duplo', 'v_comissao_triplo', 'ativa', 'codigo_susep', 'frequencia' ];	
	protected $hidden = [ 'id', 'range_inicial', 'range_final', 'tipo', 'intervalo', 'valor_simples', 'valor_duplo', 'valor_triplo', 'v_comissao_simples', 'v_comissao_duplo', 'v_comissao_triplo', 'ativa', 'created_at', 'updated_at', 'deleted_at' ];
	protected $appends = ['valor', 'elegibilidade'];
	public function getElegibilidadeAttribute()
    {
    	$previousEtapa = \DB::table( with( new Etapa )->getTable() )->where('ativa', '0')->where('frequencia', $this->attributes['frequencia'])->where('data', '<', $this->attributes['data'])->orderBy('data', 'DESC')->first();
    	
    	if( !$previousEtapa ){
    		$etapa = Etapa::ativa( $this->attributes['frequencia'] );
    		$previousEtapa = (Object) array( 'data' => Carbon::parse($etapa->data)->subDays(7)->format('Y-m-d') );
    	}

        return $this->attributes['elegibilidade'] = [ 
        	'inicio' => Carbon::parse($previousEtapa->data)->addDay()->format('d/m/Y'), 
        	'final' => Carbon::parse($this->attributes['data'])->subDay()->format('d/m/Y') ] ;
    }

	public static function ativa( $frequencia = 'semanal' ){
		return \DB::table( with( new Etapa )->getTable() )->where('frequencia', $frequencia)->where('ativa', '1')->first();
	}

	public function premiacao(){
		return $this->hasMany('App\Premiacao', 'etapa_id', 'id')->orderBy('seq', 'ASC');
	}

	public function premiacaoEletronica(){
		return $this->hasMany('App\PremiacaoEletronica', 'etapa_id', 'id')->orderBy('numero', 'ASC');
	}

	public function getvalorAttribute()
	{
		$valor = 0;
		switch( $this->attributes['tipo'] ){
			case '1': $valor = $this->attributes['valor_simples']; break;
			case '2': $valor = $this->attributes['valor_duplo']; break;
			case '3': $valor = $this->attributes['valor_triplo']; break;
		}

		return $this->attributes['valor'] = Helper::formatDecimalToDb($valor);
	}

	public function getcomissaoAttribute()
	{
		$comissao = 0;
		switch( $this->attributes['tipo'] ){
			case '1': $comissao = $this->attributes['v_comissao_simples']; break;
			case '2': $comissao = $this->attributes['v_comissao_duplo']; break;
			case '3': $comissao = $this->attributes['v_comissao_triplo']; break;
		}

		return $this->attributes['comissao'] = Helper::formatDecimalToDb($comissao);
	}
}
