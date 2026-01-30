<?php

namespace App\Http\Controllers;

use App\User;
use App\Etapa;
use Validator;
use App\Sequencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
class SequenciaController extends Controller
{
    public function get(Request $request)
    {
        if( ! Etapa::ativa() )
            return response()->json(['error'=>['etapa'=>['Etapa não localizada.']]],400);

        // etapa passou do prazo
        if( strtotime( Etapa::ativa()->data.' 23:59:59' ) < strtotime( date('Y-m-d H:i:s') ) )
            return response()->json(['error'=>['etapa'=>['Etapa inválida.']]],400);

        $validator = Validator::make($request->all(),[
            'quantidade' => 'nullable|integer|min:1',
            'numeros' => 'nullable|array|size:6', // max:6
            'numeros.*' => 'integer|between:1,60',
        ]);
        if( $validator->fails() )
            return response()->json(['error' => $validator->messages()],400);

        $quantidade = 1;
        if( $request->has('quantidade') )
            $quantidade = intval( $request->quantidade );

        $numerosInformados = [];
        if( $request->has('numeros') ){
            $numerosInformados = $request->numeros;

            $numerosInformados = array_unique($numerosInformados);
            sort($numerosInformados);

            $fixosCount = count($numerosInformados);
            $faltantes  = 7 - $fixosCount;

            // Total de combinações possíveis
            $totalPossiveis = Self::combinacao(60 - $fixosCount, $faltantes);

            // Conta quantas já existem no banco para esses fixos
            $prefixo = implode('-', $numerosInformados);
            $jaGeradas = Sequencia::where('etapa_id', Etapa::ativa()->id)->where('sequencia', 'like', $prefixo.'%')->count();

            if ($jaGeradas >= $totalPossiveis)
                return response()->json(['error' => ['numeros'=>['Não existem mais sequências disponíveis para esses números fixos.']]],400);
        }

        \DB::beginTransaction();
        try {
            $sequenciasGeradas = [];
            foreach( range(1, $quantidade) as $i )
                $sequenciasGeradas[] = Self::gerarSequenciaUnica($numerosInformados);

            // formatar a saida da sequencia
            foreach( $sequenciasGeradas as $i => $sequencia ){
                $seq = explode('-', $sequencia->sequencia);
                foreach( $seq as $j => $s )
                    $seq[$j] = str_pad($s, 2, '0', STR_PAD_LEFT);

                $sequenciasGeradas[$i]->sequencia = implode('-', $seq);
            }

            \DB::commit();
            return response()->json($sequenciasGeradas, 201);
        } catch( \Exception $e ){
            \DB::rollback();
            return response()->json([ 'error' => $e->getMessage() ],400);
        }
    }

    private function gerarSequenciaUnica(array $numerosInformados = []) {
        $tentativas = 0;
        while ($tentativas < 1000) {
            $tentativas++;

            $disponiveis = array_diff(range(1, 60), $numerosInformados);
            shuffle($disponiveis);

            $gerados = array_slice($disponiveis, 0, ( 7 - count( $numerosInformados ) ));
            $sequencia = array_merge($numerosInformados, $gerados);

            sort($sequencia);
            $sequenciaString = implode('-', $sequencia);

            $check = Sequencia::where('etapa_id', Etapa::ativa()->id)->where('sequencia', $sequenciaString)->count();
            if ($check == 0) {
                $sequencia = Sequencia::create([
                    'etapa_id' => Etapa::ativa()->id,
                    'user_id' => \Auth::user()->id,
                    'sequencia' => $sequenciaString,
                ]);

                $sequencia->bilhete = $sequencia->id;
                $sequencia->etapa = $sequencia->etapa()->first();
                return $sequencia;
            }
        }

        throw new \Exception("Falha ao gerar sequência.");
    }

    private function combinacao($n, $r) {
        if ($r > $n) return 0;
        return Self::fatorial($n) / ( Self::fatorial($r) * Self::fatorial($n - $r) );
    }

    private function fatorial($n) {
        $resultado = 1;
        for ($i = 2; $i <= $n; $i++) {
            $resultado *= $i;
        }
        return $resultado;
    }

}
