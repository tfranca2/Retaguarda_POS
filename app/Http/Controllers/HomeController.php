<?php

namespace App\Http\Controllers;

use DB;
use App\Cliente;
use App\Prestador;
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

        $totalClientes = Cliente::All()->count();
        $totalPrestadores = Prestador::select(['prestador.*'])
                        ->join('users', function($q){
                            $q->on('prestador.usuario_id', '=', 'users.id')
                              ->whereNull('users.deleted_at');
                        })
                        ->orderBy('users.name')
                        ->with('usuario')
                        ->with('servicos')
                        ->count();


        $meses = [ '', 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro' ];

        $varcolors = [ "#F7464A", "#46BFBD", "#FDB45C", "#949FB1", "#4D5360", "#b15bef", "#25bc0d", "#baa03f", "#9ed965", "#370d72", "#d2179e", "#ddee9a", "#08e23e", "#1912cc", "#92a434" ];


        // FATURAMENTO

        $intervalo = 12; // meses

        $totalFaturamento = Helper::formatDecimalToView( 
            DB::select("SELECT SUM(valorLiquido) AS total FROM payments WHERE status IN ( 3, 4 ) AND date BETWEEN DATE_SUB( ( SELECT MAX(date) FROM payments ), INTERVAL ". $intervalo ." MONTH ) AND ( SELECT MAX(date) FROM payments )")[0]->total
        );

        $graficoFaturamento = array(
            'labels' => [],
            'datasets' => [
                (object) array(
                    'backgroundColor' => '',
                    'data' => [],
                ),
            ]
        );

        $m = [];
        for( $i=($intervalo-1); $i>0; $i-- ){
          $atual = date('n', strtotime("-$i month"));
          $m[$atual] = array('mes'=>$meses[$atual],'total'=>0);
        }
        $m[date('n')] = array('mes'=>$meses[date('n')],'total'=>0);

        $result = DB::select("SELECT DATE_FORMAT( date, '%m' ) AS mes, SUM( valorLiquido ) AS total FROM payments WHERE status IN ( 3, 4 ) AND date BETWEEN DATE_SUB( ( SELECT MAX(date) FROM payments ), INTERVAL ". $intervalo ." MONTH ) AND ( SELECT MAX(date) FROM payments ) GROUP BY DATE_FORMAT( date, '%m' ) ");
        foreach( $result as $data ){
            if( isset($m[ intval( $data->mes ) ]) )
              $m[ intval( $data->mes ) ]['total'] = number_format( $data->total, 2, '.', '' );
        }
        foreach( $m as $e ){
            $graficoFaturamento['labels'][] = $e['mes'];
            $graficoFaturamento['datasets'][0]->data[] = number_format( $e['total'], 2, '.', '');
            $graficoFaturamento['datasets'][0]->backgroundColor = $varcolors;
        }


        // CONTRATACOES

        $intervalo = 6; // meses

        $totalContratacoes = Helper::formatDecimalToView( DB::select("SELECT COUNT(*) AS total FROM payments WHERE status IN ( 3, 4 ) AND date BETWEEN DATE_SUB( ( SELECT MAX(date) FROM payments ), INTERVAL ". $intervalo ." MONTH ) AND ( SELECT MAX(date) FROM payments )")[0]->total, 0 );

        $graficoContratacoes = array(
            'labels' => [],
            'datasets' => [
                (object) array(
                    'backgroundColor' => '',
                    'data' => [],
                ),
            ]
        );

        $m = [];
        for( $i=($intervalo-1); $i>0; $i-- ){
          $atual = date('n', strtotime("-$i month"));
          $m[$atual] = array('mes'=>$meses[$atual],'total'=>0);
        }
        $m[date('n')] = array('mes'=>$meses[date('n')],'total'=>0);

        $result = DB::select("SELECT DATE_FORMAT( date, '%m' ) AS mes, COUNT(*) AS total FROM payments WHERE status IN ( 3, 4 ) AND date BETWEEN DATE_SUB( ( SELECT MAX(date) FROM payments ), INTERVAL ". $intervalo ." MONTH ) AND ( SELECT MAX(date) FROM payments ) GROUP BY DATE_FORMAT( date, '%m' ) ");
        foreach( $result as $data ){
            if( isset($m[ intval( $data->mes ) ]) )
              $m[ intval( $data->mes ) ]['total'] = $data->total;
        }
        foreach( $m as $e ){
            $graficoContratacoes['labels'][] = $e['mes'];
            $graficoContratacoes['datasets'][0]->data[] = $e['total'];
            $graficoContratacoes['datasets'][0]->backgroundColor = $varcolors;
        }

        return view('home',[
            'clientes' => $totalClientes,
            'prestadores' => $totalPrestadores,
            
            'faturamento' => $totalFaturamento,
            'graficofaturamento' => json_encode($graficoFaturamento),

            'contratacoes' => $totalContratacoes,
            'graficocontratacoes' => json_encode($graficoContratacoes),
        ]);

    }

}
