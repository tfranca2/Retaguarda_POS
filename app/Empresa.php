<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empresa extends Model
{
	
	// use SoftDeletes;

	protected $table = 'empresa';
    protected $fillable = [ 'nome', 'cnpj', 'deleted_at', 'main_logo', 'favicon', 'menu_logo', 'contracted_menu_logo', 'menu_background', 'menu_color', 'google_maps_api_key', 'fcm_server_key', 'mail_driver', 'mail_host', 'mail_port', 'mail_username', 'mail_password', 'mail_encryption', 'mail_from_name', 'mail_from_address', 'descontos' ];

}
