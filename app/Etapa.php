<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Etapa extends Model
{
    
    protected $table = 'etapas';

	protected $fillable = [ 'etapa', 'descricao','data','range_inicial','range_final','tipo', 'intervalo', 'valor_simples', 'valor_duplo', 'valor_triplo', 'v_comissao_simples', 'v_comissao_duplo', 'v_comissao_triplo', 'ativa' ];
}
