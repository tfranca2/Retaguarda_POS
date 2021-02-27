<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrestadorDocumento extends Model
{

	protected $table = 'prestador_documento';
    protected $fillable = [ 'prestador_id', 'documento', 'numeracao', 'foto' ];

}
