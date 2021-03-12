<?php

namespace App\Http\Controllers;

use App\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class MenuController extends Controller
{
    
    public function getAll( Request $request ){
        $menus = Menu::orderBy('label')->paginate(10);
        return response()->json( $menus, 200 );
    }

    public function index( Request $request ){
        $menus =  Menu::orderByRaw(' CASE WHEN parent IS NULL THEN (id-1) ELSE parent END ASC ')
                    ->orderByRaw(' CASE WHEN ordem IS NULL THEN 9999999999 ELSE ordem END ASC ')
                    ->orderBy('label')
                    ->paginate(10);
        return view('menu.index',[ 'menus' => $menus ]);
    }

    public function create( Request $request ){
        $menus = Menu::whereNull('parent')->orderBy('label')->get();
        return view('menu.form',['menus' => $menus]);
    }
    
    public function store( Request $request ){
        $menu = Menu::create( Input::except( 'id', '_method', '_token' ) );
        return response()->json([ 
            'message' => 'Criado com sucesso', 
            'redirectURL' => url('/menus'), 
            'menu' => $menu 
        ], 201 );
    }
    
    public function show( Request $request, $id ){
        try {
            return response()->json( Menu::findOrFail($id) );
        } catch( \Exception $e ){
            return response()->json([ 'error' => $e->getMessage() ], 404 );
        }
    }
    
    public function edit( Request $request, $id ){
        $menus = Menu::whereNull('parent')->orderBy('label')->get();
        $menu = Menu::findOrFail($id);
        return view('menu.form',[ 'menus' => $menus, 'menu' => $menu ]);
    }
    
    public function update( Request $request, $id ){

        $menu = Menu::find($id);
        $inputs = Input::except( 'id', '_method', '_token' );
        foreach( $inputs as $key => $value ){
            $menu->$key = $value;
        }
        $menu->save();
        return response()->json([ 
            'message' => 'Atualizado com sucesso', 
            'redirectURL' => url('/menus'), 
            'menu' => $menu 
        ], 200 );
    }
    
    public function destroy( Request $request, $id ){
        $menu = Menu::findOrFail($id);
        $menu->delete();
        return response()->json([ 'message' => 'Deletado com sucesso' ], 204 );
    }
}
