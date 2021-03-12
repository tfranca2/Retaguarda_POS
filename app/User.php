<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'perfil_id', 'empresa_id', 'cpf', 'imagem', 'deleted_at' 
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    // JOINS
    
    public function perfil()
    {
        return $this->hasOne('App\Perfil', 'id', 'perfil_id')->first();
    }

    public function permissions()
    {
        return $this->hasMany('App\Permissions', 'perfil_id', 'perfil_id');
    }

    public function empresa()
    {
        return $this->hasOne('App\Empresa', 'id', 'empresa_id')->first();
    }


}
