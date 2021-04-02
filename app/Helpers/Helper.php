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
            utf8_decode(html_entity_decode($str)),
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

}
