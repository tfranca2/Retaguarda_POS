<?php

namespace App\Helpers;

class Pix
{
	const ID_PAYLOAD_FORMAT_INDICATOR = '00';
	const ID_POINT_OF_INITIATION_METHOD = '01';
	const ID_MERCHANT_ACCOUNT_INFORMATION = '26';
	const ID_MERCHANT_ACCOUNT_INFORMATION_GUI = '00';
	const ID_MERCHANT_ACCOUNT_INFORMATION_KEY = '01';
	const ID_MERCHANT_ACCOUNT_INFORMATION_DESCRIPTION = '02';
	const ID_MERCHANT_ACCOUNT_INFORMATION_URL = '25';
	const ID_MERCHANT_CATEGORY_CODE = '52';
	const ID_TRANSACTION_CURRENCY = '53';
	const ID_TRANSACTION_AMOUNT = '54';
	const ID_COUNTRY_CODE = '58';
	const ID_MERCHANT_NAME = '59';
	const ID_MERCHANT_CITY = '60';
	const ID_ADDITIONAL_DATA_FIELD_TEMPLATE = '62';
	const ID_ADDITIONAL_DATA_FIELD_TEMPLATE_TXID = '05';
	const ID_CRC16 = '63';

	private $pixKey;
	private $description;
	private $merchantName;
	private $merchantCity;
	private $txid;
	private $amount;
	private $uniquePayment = false;
	private $url;

	public function setPixKey($pixKey){
		$this->pixKey = $pixKey;
		return $this;
	}

	public function setUniquePayment($uniquePayment){
		$this->uniquePayment = $uniquePayment;
		return $this;
	}

	public function setUrl($url){
		$this->url = $url;
		return $this;
	}

	public function setDescription($description){
		$this->description = $description;
		return $this;
	}

	public function setMerchantName($merchantName){
		$this->merchantName = $merchantName;
		return $this;
	}

	public function setMerchantCity($merchantCity){
		$this->merchantCity = $merchantCity;
		return $this;
	}

	public function setTxid($txid){
		$this->txid = $txid;
		return $this;
	}

	public function setAmount($amount){
		$this->amount = (string)number_format($amount,2,'.','');
		return $this;
	}

	private function getValue($id,$value){
		$size = str_pad(mb_strlen($value),2,'0',STR_PAD_LEFT);
		return $id.$size.$value;
	}

	private function getMerchantAccountInformation(){
		//DOMÍNIO DO BANCO
		$gui = $this->getValue(self::ID_MERCHANT_ACCOUNT_INFORMATION_GUI,'br.gov.bcb.pix');

		//CHAVE PIX
		$key = strlen($this->pixKey) ? $this->getValue(self::ID_MERCHANT_ACCOUNT_INFORMATION_KEY,$this->pixKey) : '';

		//DESCRIÇÃO DO PAGAMENTO
		$description = strlen($this->description) ? $this->getValue(self::ID_MERCHANT_ACCOUNT_INFORMATION_DESCRIPTION,$this->description) : '';

		//URL DO QR CODE DINÂMICO
		$url = strlen($this->url) ? $this->getValue(self::ID_MERCHANT_ACCOUNT_INFORMATION_URL,preg_replace('/^https?\:\/\//','',$this->url)) : '';

		//VALOR COMPLETO DA CONTA
		return $this->getValue(self::ID_MERCHANT_ACCOUNT_INFORMATION,$gui.$key.$description.$url);
	}

	private function getAdditionalDataFieldTemplate(){
		//TXID
		$txid = $this->getValue(self::ID_ADDITIONAL_DATA_FIELD_TEMPLATE_TXID,$this->txid);

		//RETORNA O VALOR COMPLETO
		return $this->getValue(self::ID_ADDITIONAL_DATA_FIELD_TEMPLATE,$txid);
	}

	private function getUniquePayment(){
		return $this->uniquePayment ? $this->getValue(self::ID_POINT_OF_INITIATION_METHOD,'12') : '';
	}

	public function getPayload(){
		//CRIA O PAYLOAD
		$payload = $this->getValue(self::ID_PAYLOAD_FORMAT_INDICATOR,'01').
					$this->getUniquePayment().
					$this->getMerchantAccountInformation().
					$this->getValue(self::ID_MERCHANT_CATEGORY_CODE,'0000').
					$this->getValue(self::ID_TRANSACTION_CURRENCY,'986').
					$this->getValue(self::ID_TRANSACTION_AMOUNT,$this->amount).
					$this->getValue(self::ID_COUNTRY_CODE,'BR').
					$this->getValue(self::ID_MERCHANT_NAME,$this->merchantName).
					$this->getValue(self::ID_MERCHANT_CITY,$this->merchantCity).
					$this->getAdditionalDataFieldTemplate();

		//RETORNA O PAYLOAD + CRC16
		return $payload.$this->getCRC16($payload);
	}

	private function getCRC16($payload) {
		//ADICIONA DADOS GERAIS NO PAYLOAD
		$payload .= self::ID_CRC16.'04';

		//DADOS DEFINIDOS PELO BACEN
		$polinomio = 0x1021;
		$resultado = 0xFFFF;

		//CHECKSUM
		if (($length = strlen($payload)) > 0) {
			for ($offset = 0; $offset < $length; $offset++) {
				$resultado ^= (ord($payload[$offset]) << 8);
				for ($bitwise = 0; $bitwise < 8; $bitwise++) {
					if (($resultado <<= 1) & 0x10000) $resultado ^= $polinomio;
						$resultado &= 0xFFFF;
				}
			}
		}

		//RETORNA CÓDIGO CRC16 DE 4 CARACTERES
		return self::ID_CRC16.'04'.strtoupper(dechex($resultado));
	}

	}