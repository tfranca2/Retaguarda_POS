  
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cidade extends Model
{

    protected $table = 'cidades';

    protected $fillable = [ 'nome', 'estado_id', 'populacao' ];

    // JOINS
    public function bairros(){
        return $this->hasMany( 'App\Bairro', 'bairro_id', 'id' );
    }
    
    public function estado(){
        return $this->hasOne( 'App\Estado', 'id', 'estado_id' );
    }

}