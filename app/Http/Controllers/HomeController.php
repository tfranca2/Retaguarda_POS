<?php

namespace App\Http\Controllers;

use DB;
use App\User;
use App\Venda;
use App\Etapa;
use App\Helpers\Helper;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(){

        $etapa = Etapa::ativa();

        $valor = 0;
        switch( $etapa->tipo ){
            case '1': $valor = $etapa->valor_simples; break;
            case '2': $valor = $etapa->valor_duplo; break;
            case '3': $valor = $etapa->valor_triplo; break;
        }

        $vendas = Venda::where( 'etapa_id', $etapa->id )
                ->join('payments','payments.venda_id','=','vendas.id')
                ->get()
                ->pluck('venda_id')
                ->toArray();

        $total = count( $vendas ) * $valor;

        $leads = Venda::whereNotNull('cpf')
                ->whereNotIn('id', $vendas)
                ->where('etapa_id', $etapa->id)->count();

        $acessos = 0;
        $online = 0;
        $sessoes = glob( session_save_path().'/sess_*' );
        foreach( $sessoes as $sessao ){
            if( date( 'Y-m-d', filemtime($sessao)) == date('Y-m-d') ){
                $acessos++;
                if( filesize($sessao) ){
                    $old_data = filemtime($sessao);
                    $now = strtotime("-10 minutes");
                    if( $now > strtotime($old_data) ){
                        $online++;
                    }
                }
            }
        }


        return view('home',[
            'vendasCount' => count( $vendas ),
            'vendasTotal' => $total,
            'leads' => $leads,
            'online' => $online,
            'acessos' => $acessos,
            'colors' => [
                Self::adjustBrightness( \Auth::user()->empresa()->menu_background, 40 ),
                Self::adjustBrightness( \Auth::user()->empresa()->menu_background, 20 ),
                Self::adjustBrightness( \Auth::user()->empresa()->menu_background, 0 ),
                Self::adjustBrightness( \Auth::user()->empresa()->menu_background, -20 ),
                Self::adjustBrightness( \Auth::user()->empresa()->menu_background, -40 ),
            ],
        ]);

    }

    public function adjustBrightness( $hex, $steps ) {
        // Steps should be between -255 and 255. Negative = darker, positive = lighter
        $steps = max(-255, min(255, $steps));

        // Normalize into a six character long hex string
        $hex = str_replace('#', '', $hex );
        if (strlen($hex) == 3) {
            $hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2);
        }

        // Split into three parts: R, G and B
        $color_parts = str_split($hex, 2);
        $return = '#';

        foreach ($color_parts as $color) {
            $color   = hexdec($color); // Convert to decimal
            $color   = max(0,min(255,$color + $steps)); // Adjust color
            $return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Make two char hex code
        }

        return $return;
    }

}
