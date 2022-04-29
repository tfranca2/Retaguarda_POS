<?php

namespace App\Helpers;

use Auth;
use App\Permissions;

class Helper
{

	public static function grupos(){
	    return array(
	        "empresas" 		=> [ 'listar', 'incluir', 'editar', 'excluir', 'gerenciar' ],
	        "usuarios" 		=> [ 'listar', 'incluir', 'editar', 'excluir', 'gerenciar' ],
	        "perfis" 		=> [ 'listar', 'incluir', 'editar', 'excluir', 'gerenciar' ],
	        "configuracoes"	=> [ 'listar', 'incluir', 'editar', 'excluir', 'gerenciar' ],
	    );
	}

    public static function temPermissao($role){
    	if(!$role) return true;
    	if( Auth::user()->permissions()->where(['role'=>$role])->first() )
			return true;
		return false;
	}

    public static function perfilTemPermissao($perfil, $role){
    	if( Permissions::where(['role'=>$role, 'perfil_id'=>$perfil])->first() )
			return true;
		return false;
	}

	public static function onlyNumbers($str){
        return preg_replace('/\D/', '', $str);
    }

    public static function mask($val, $mask){
		$maskared = '';
    	if( $val ){
			$k = 0;
			for($i = 0; $i<=strlen($mask)-1; $i++) {
				if($mask[$i] == '#') {
					if(isset($val[$k]))
						$maskared .= $val[$k++];
				} else {
					if(isset($mask[$i]))
						$maskared .= $mask[$i];
				}
			}
    	}
		return $maskared;
	}

	public static function formatCpfCnpj($cp){
		return Self::mask( Self::onlyNumbers($cp), ((strlen( Self::onlyNumbers($cp) )==11)?'###.###.###-##':'##.###.###/####-##') );
	}

	public static function formatTelefone($telefone){
		return Self::mask( Self::onlyNumbers($telefone), '(##) # ####-####' );
	}

	public static function formatDecimalToDb( $valor, $precision = 2 ){
		if( $valor ){
			if( str_contains($valor, ',') ){
				$v = explode(',', $valor);
				$val = 0;
				if($v[0])
					$val = Self::onlyNumbers($v[0]);
				if( isset($v[1]) )
					$val .= '.'.Self::onlyNumbers($v[1]);

				return floatval( number_format($val, $precision, '.', '') );
			} else {
				return floatval( number_format($valor, $precision, '.', '') );
			}
		}
		return 0.00;
	}

	public static function formatDecimalToView( $valor, $precision = 2 ){
		if( $valor ){
			if( str_contains($valor, ',') ){
				$v = explode(',', $valor);
				$val = 0;
				if($v[0])
					$val = Self::onlyNumbers($v[0]);
				if( isset($v[1]) )
					$val .= '.'.Self::onlyNumbers($v[1]);

				return number_format($val, $precision, ',', '.');
			} else {
				return number_format($valor, $precision, ',', '.');
			}
		}
		return '0,00';
	}

    public static function sanitizeString($str){
        return preg_replace('{\W }', '', strtr(
            utf8_decode(html_entity_decode(trim($str))),
            utf8_decode('ÀÁÃÂÉÊÍÓÕÔÚÜÇÑàáãâéêíóõôúüçñ'),
            'AAAAEEIOOOUUCNaaaaeeiooouucn'));
    }

    public static function convertDate( $date, $printHour = true ){
		$twopoints = strpos( $date, ':' );
		$dash = strpos( $date, '-' );
		if( $dash === false ){
			$d = explode(' ', $date);
			$date = explode('/', $d[0]);
			$date = $date[2].'-'.$date[1].'-'.$date[0].' '.$d[1];
		}

        if( $printHour and $twopoints !== false ){
			if( $dash === false )
				$newdate = date("Y-m-d H:i:s", strtotime($date));
			else
				$newdate = date("d/m/Y H:i:s", strtotime($date));
		} else {
			if( $dash === false )
				$newdate = date("Y-m-d", strtotime($date));
			else
				$newdate = date("d/m/Y", strtotime($date));
        }

		return $newdate;
	}

	public static function validaNome( $nome ){
		if( preg_match( "/^[A-ZÀ-Ÿ][A-zÀ-ÿ']+\s([A-zÀ-ÿ']\s?)*[A-ZÀ-Ÿ][A-zÀ-ÿ']+$/", trim( $nome ) ) )
			return true;
		return false;
	}

	public static function validaCPF($cpf) {
		$cpf = Self::onlyNumbers($cpf);
		if( strlen($cpf) != 11 ) 
			return false;
		// Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
		if( preg_match( '/(\d)\1{10}/', $cpf ) ) 
			return false;
		// Faz o calculo para validar o CPF
		for( $t = 9; $t < 11; $t++ ){
			for( $d = 0, $c = 0; $c < $t; $c++ )
				$d += $cpf[$c] * (($t + 1) - $c);
			$d = ((10 * $d) % 11) % 10;
			if( $cpf[$c] != $d )
				return false;
		}
		return true;
	}

	public static function validaCelular($telefone){
		//*/
		$telefone = Self::onlyNumbers( $telefone );
		if( strlen($telefone) < 10 or strlen($telefone) > 11 )
			return false;
		return true;
		/*/
		if( preg_match( '/[0-9]{2}[0-9]{3,4}[0-9]{4}/', Self::onlyNumbers( $telefone ) ) )
			return true;
		return false;
		//*/
	}

	public static function validaCep($cep){
		$cep = Self::onlyNumbers( $cep );
		if( strlen($cep) != 8 )
			return false;
		return true;
	}

}
