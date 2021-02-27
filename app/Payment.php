<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{

	protected $table = 'payments';
    protected $fillable = [ 'user_id', 'cliente_id', 'contratacao_id', 'plan_id', 'transaction_code', 'status', 'message', 'date', 'valorBruto', 'taxa', 'valorLiquido', 'valorExtra ' ];

    // JOINS
    public function usuario(){
        return $this->hasOne( 'App\User', 'id', 'user_id' );
    }
	// JOINS
    public function cliente(){
        return $this->hasOne( 'App\Cliente', 'id', 'cliente_id' );
    }
    // JOINS
    public function contratacao(){
        return $this->hasOne( 'App\Contratacao', 'transaction_code', 'transaction_code' );
    }

}
