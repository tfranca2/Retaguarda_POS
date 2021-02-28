<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menus';
    protected $fillable = [ 'permission', 'parent', 'ordem', 'icon', 'label', 'link' ];

    public function getSubmenus(){
    	if( $this->parent )
    		return [];
    	return Menu::where('parent',$this->id)->orderByRaw(' case when ordem is null then 9999999999 else ordem end asc ')->orderBy('label')->get();
    }
}
