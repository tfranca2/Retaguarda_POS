<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendaMatriz extends Model
{
    protected $table = 'venda_matriz';
    protected $fillable = [ 'venda_id', 'matriz_id' ];

    // JOINS
    public function venda(){
        return $this->hasOne( 'App\Venda', 'id', 'venda_id' );
    }

    public function matriz(){
        return $this->hasOne( 'App\Matriz', 'id', 'matriz_id' );
    }
}
