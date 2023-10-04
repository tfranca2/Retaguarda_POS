<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VendaMatriz extends Model
{
    use SoftDeletes;
    
    protected $table = 'venda_matriz';
    protected $fillable = [ 'venda_id', 'matriz_id' ];
    protected $hidden = [ 'id', 'venda_id', 'matriz_id', 'created_at', 'updated_at', 'deleted_at' ];

    // JOINS
    public function venda(){
        return $this->hasOne( 'App\Venda', 'id', 'venda_id' );
    }

    public function matriz(){
        return $this->hasOne( 'App\Matriz', 'id', 'matriz_id' );
    }
    
    public function matrizes(){
        return $this->hasOne( 'App\Matriz', 'id', 'matriz_id' );
    }
    
    public function matriz_50_15(){
        return $this->hasOne( 'App\Matriz5015', 'id', 'matriz_id' );
    }
    
    public function matriz_60_20_extra(){
        return $this->hasOne( 'App\Matriz6020Extra', 'id', 'matriz_id' );
    }
    
    public function matriz_15_30_extra(){
        return $this->hasOne( 'App\Matriz1530Extra', 'id', 'matriz_id' );
    }
}
