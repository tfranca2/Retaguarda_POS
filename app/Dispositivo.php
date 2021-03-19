<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dispositivo extends Model
{
	
	use SoftDeletes;

    protected $table = 'dispositivos';

	protected $fillable = [ 'nome', 'code', 'mac', 'ip', 'distribuidor_id', 'deleted_at' ];

	// JOINS
    public function distribuidor(){
        return $this->hasOne( 'App\User', 'id', 'distribuidor_id' );
    }
    
}
