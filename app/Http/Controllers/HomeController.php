<?php

namespace App\Http\Controllers;

use DB;
use Session;
use App\User;
use App\Venda;
use App\Etapa;
use App\Tracking;
use App\Helpers\Helper;
use App\Helpers\UserSystemInfoHelper;
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
        $this->middleware('auth', ['except' => ['home', 'tracking']]);
      
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function home( Request $request ){
        return redirect()->route( env('HOME', 'home') );
    }

    public function index( Request $request ){
        if( !$request->has('etapa_id') )
            $request->merge([ 'etapa_id' => Etapa::ativa()->id ]);

        $etapas = Etapa::orderBy('id','DESC')->get();

        return view('home', array_merge( 
            ( (array) Self::dashinfo( $request )->getData() ),
            [
                'etapas' => $etapas,
                'colors' => [
                    Self::adjustBrightness( \Auth::user()->empresa()->menu_background, 40 ),
                    Self::adjustBrightness( \Auth::user()->empresa()->menu_background, 20 ),
                    Self::adjustBrightness( \Auth::user()->empresa()->menu_background, 0 ),
                    Self::adjustBrightness( \Auth::user()->empresa()->menu_background, -20 ),
                    Self::adjustBrightness( \Auth::user()->empresa()->menu_background, -40 ),
                ],
            ])
        );
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

    public function dashinfo( Request $request ){

        if( !$request->has('etapa_id') )
            $request->merge([ 'etapa_id' => Etapa::ativa()->id ]);

        $etapa = Etapa::find( $request->etapa_id );

        $valor = 0;
        switch( $etapa->tipo ){
            case '1': $valor = $etapa->valor_simples; break;
            case '2': $valor = $etapa->valor_duplo; break;
            case '3': $valor = $etapa->valor_triplo; break;
        }

        $vendas = Venda::where( 'etapa_id', $request->etapa_id )->where('confirmada', 1)
                ->join('payments','payments.venda_id','=','vendas.id')
                ->get()
                ->pluck('venda_id')
                ->toArray();
        $quantidade = count( $vendas );
        $total = 'R$ '. Helper::formatDecimalToView( $quantidade * $valor );

        $leads = Venda::withTrashed()->whereNotNull('cpf')
                ->whereNotIn('id', $vendas)
                ->where('etapa_id', $request->etapa_id)->count();

        $acessos = Tracking::whereRaw('DATE( updated_at ) = DATE( NOW() )')->count();
        $online = Tracking::where('updated_at', '>=', ( new \DateTime )->modify('-30 seconds')->format('Y-m-d H:i:s') )->count();

        $labels = [ 'Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado' ];
        $data = [];
        foreach( $labels as $label )
            $data[] = 0;
        $color = Self::adjustBrightness( \Auth::user()->empresa()->menu_background, 40);
        $vendas = DB::select("  SELECT
                                    DAYOFWEEK(created_at) AS dia,
                                    COUNT(*) AS quantidade
                                FROM vendas
                                WHERE
                                        deleted_at IS NULL
                                    AND confirmada = 1
                                    AND etapa_id = {$request->etapa_id}
                                GROUP BY DAYOFWEEK(created_at);");
        foreach( $vendas as $venda ){
            $data[ ( $venda->dia - 1 ) ] = intval( $venda->quantidade );
        }
        $vendas_por_dia = [
            'labels' => $labels,
            'datasets' => [ (object) [
                'label' => 'Vendas',
                'data' => $data,
                'backgroundColor' => $color,
            ], ],
        ];

        $labels = [];
        $time = strtotime('00:00:00');
        while( $time < strtotime('23:59:59') ){
            $labels[] = date('H:i', $time);
            $time = strtotime('+30 minutes', $time);
        }

        $data = [];
        foreach( $labels as $label )
            $data[] = 0;

        $color = Self::adjustBrightness( \Auth::user()->empresa()->menu_background, 20);
        $vendas = DB::select("  SELECT
                                    DATE_FORMAT( created_at - INTERVAL MINUTE( created_at ) % 30 MINUTE, '%H:%i' ) AS hora,
                                    COUNT(*) AS quantidade
                                FROM vendas
                                WHERE
                                        deleted_at IS NULL
                                    AND confirmada = 1
                                    AND etapa_id = {$request->etapa_id}
                                GROUP BY hora
                                ORDER BY hora ASC;");
        foreach( $vendas as $venda ){
            $data[ array_search($venda->hora, $labels) ] = intval( $venda->quantidade );
        }
        $vendas_por_hora = [
            'labels' => $labels,
            'datasets' => [ (object) [
                'label' => 'Vendas',
                'data' => $data,
                'borderColor' => $color,
                'borderWidth' => 3,
            ], ],
        ];

        $ulimas10Etapas = DB::select("SELECT GROUP_CONCAT(id) AS ids FROM ( SELECT id FROM etapas ORDER BY id DESC LIMIT 10 ) x")[0]->ids;

        $labels = [];
        $data = [];
        $color = Self::adjustBrightness( \Auth::user()->empresa()->menu_background, -40);
        $vendas = DB::select("  SELECT
                                    etapas.descricao AS etapa,
                                    COUNT(*) AS quantidade,
                                    SUM(
                                        IF( etapas.tipo = 1, etapas.valor_simples,
                                            IF( etapas.tipo = 2, etapas.valor_duplo,
                                                IF( etapas.tipo = 3, etapas.valor_triplo, 0 ) ) )
                                    ) AS valor
                                FROM vendas
                                JOIN etapas ON etapas.id = vendas.etapa_id
                                WHERE
                                        deleted_at IS NULL
                                    AND confirmada = 1
                                    AND etapa_id IN ({$ulimas10Etapas})
                                GROUP BY etapa_id;");
        foreach( $vendas as $venda ){
            $labels[] = $venda->etapa;
            $data[] = floatval( number_format( $venda->valor, 2, '.', '' ) );
        }
        $vendas_por_etapa = [
            'labels' => $labels,
            'datasets' => [ (object) [
                'label' => 'Faturamento',
                'data' => $data,
                'backgroundColor' => $color,
            ], ],
        ];

        return response()->json( [
            'vendasCount' => $quantidade,
            'vendasTotal' => $total,
            'leads' => $leads,
            'online' => $online,
            'acessos' => $acessos,
            'vendas_por_dia' => $vendas_por_dia,
            'vendas_por_hora' => $vendas_por_hora,
            'vendas_por_etapa' => $vendas_por_etapa,
        ], 200 );
    }

    public function tracking( Request $request ){

        $tracking = Tracking::updateOrCreate([
            'session_id' => UserSystemInfoHelper::get_session_id(),
        ],[
            'ip' => UserSystemInfoHelper::get_ip(),
            'referer' => UserSystemInfoHelper::get_referer(),
            'device' => UserSystemInfoHelper::get_device(),
            'os' => UserSystemInfoHelper::get_os(),
            'browser' => UserSystemInfoHelper::get_browsers(),
            'agent' => UserSystemInfoHelper::get_user_agent(),
            'location' => ( ( isset( $request->location ) ) ? $request->location : '' ),
            'resolution' => ( ( isset( $request->resolution ) ) ? $request->resolution : '' ),
            'orientation' => ( ( isset( $request->orientation ) ) ? $request->orientation : '' ),
        ]);
        $tracking->touch();

    }

}
