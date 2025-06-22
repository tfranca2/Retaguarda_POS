<?php

namespace App;

use Helper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Etapa extends Model
{
    
    protected $table = 'etapas';
	protected $fillable = [ 'etapa', 'descricao', 'data', 'ativa', 'codigo_susep', 'frequencia' ];
	protected $hidden = [ 'id', 'ativa', 'created_at', 'updated_at', 'deleted_at' ];
	protected $appends = [ 'elegibilidade' ];

	public const FREQUENCIAS = [
		'semanal' => "SEMANAL",
		'mensal' => "MENSAL",
	];

	public const TIPOS = [
		1 => [ 
				'descricao' => 'SIMPLES',
				'quantidade' => 1,
				'intervalo' => 0,
		],
		2 => [
				'descricao' => 'DUPLA',
				'quantidade' => 2,
				'intervalo' => 500000,
		],
		3 => [
				'descricao' => 'TRIPLA',
				'quantidade' => 3,
				'intervalo' => 300000,
		],
		4 => [
				'descricao' => 'QUADRUPLA',
				'quantidade' => 4,
				'intervalo' => 250000,
		],
		5 => [
				'descricao' => 'QUINTUPLA',
				'quantidade' => 5,
				'intervalo' => 200000,
		],
		6 => [
				'descricao' => 'SEXTUPLA',
				'quantidade' => 6,
				'intervalo' => 100000,
		],
		7 => [
				'descricao' => 'SEPTUPLA',
				'quantidade' => 7,
				'intervalo' => 100000,
		],
		8 => [
				'descricao' => 'OCTUPLA',
				'quantidade' => 8,
				'intervalo' => 100000,
		],
		9 => [
				'descricao' => 'NONUPLA',
				'quantidade' => 9,
				'intervalo' => 100000,
		],
		10 => [
				'descricao' => 'DECTUPLA',
				'quantidade' => 10,
				'intervalo' => 100000,
		],
		11 => [
				'descricao' => 'UNDECUPLA',
				'quantidade' => 11,
				'intervalo' => 50000,
		],
		12 => [
				'descricao' => 'DUODECUPLA',
				'quantidade' => 12,
				'intervalo' => 50000,
		],
	];

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

	public function ranges(){
		return $this->hasMany('App\Range', 'etapa_id', 'id')->orderBy('chances', 'ASC');
	}

}
