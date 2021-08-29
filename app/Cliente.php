<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Cliente extends Model
{
    protected $table = 'clientes';

    protected $fillable = [ 'CPF', 'nome', 'email','DDD','telefone','is_whatsapp','cidade_id' ];

    // JOINS
    public function cidade(){
        return $this->hasOne( 'App\Cidade', 'id', 'cidade_id' );
    }
}
