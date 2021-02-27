<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faq extends Model
{
	use SoftDeletes;
	protected $table = 'faq';
    protected $fillable = ['titulo','descricao'];
}
