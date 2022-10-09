<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{

    protected $table = 'payments';
	protected $fillable = ['venda_id', 'cliente_id', 'transaction_code', 'status', 'valor_bruto', 'taxa', 'valor_liquido'];
    protected $hidden = [ 'id', 'updated_at', 'deleted_at' ];

    // JOINS
    public function venda(){
        return $this->hasOne( 'App\Venda', 'id', 'venda_id' );
    }

}
