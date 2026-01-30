<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sequencia extends Model
{
    protected $table = 'sequencias';
    protected $fillable = [ 'etapa_id', 'user_id', 'sequencia' ];
    protected $hidden = [ 'id', 'etapa_id', 'user_id', 'created_at', 'updated_at', 'deleted_at' ];

    public function etapa(){
        return $this->hasOne( 'App\Etapa', 'id', 'etapa_id' );
    }

    public function user(){
        return $this->hasOne( 'App\User', 'id', 'user_id' );
    }
}
