<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VendaMatriz extends Model
{
    use SoftDeletes;
    
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
