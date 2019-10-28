<?php  

	namespace Hcode\Model;

	use \Hcode\DB\Sql;
	use \Hcode\Model;

	class Address extends Model {

		const SESSION_ERROR = "AddressError";
		
		public static function getCEP($nrcep) {

			$nrcep = str_replace(".", "", $nrcep);

			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, "http://viacep.com.br/ws/$nrcep/json/");

			// Se eu espero algum retorno
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			// Se vai precisar de alguma autenticação
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

			// true para vir como json e não como objeto
			$data = json_decode(curl_exec($ch), true);

			curl_close($ch);

			return $data;
		}

		public function loadFromCEP($nrcep) {

			$data = Address::getCEP($nrcep);

			if(isset($data['logradouro']) && $data['logradouro']) {

				$this->setdesaddress($data['logradouro']);
				$this->setdescomplement($data['complemento']);
				$this->setdesdistrict($data['bairro']);
				$this->setdescity($data['localidade']);
				$this->setdesstate($data['uf']);
				$this->setdescountry('Brasil');
				$this->setnrzipcode($nrcep);
			}
		}

		public function save() {

			$sql = new Sql();

			$results = $sql->select("CALL sp_addresses_save(:idaddress, :idperson, :desaddress, :descomplement, :descity, :desstate, :descountry, :deszipcode, :desdistrict)", [
				':idaddress'=>$this->getidaddress(),
				':idperson'=>$this->getidperson(),
				':desaddress'=>utf8_decode($this->getdesaddress()),
				':descomplement'=>utf8_decode($this->getdescomplement()), // decode para o acento
				':descity'=>utf8_decode($this->getdescity()),
				':desstate'=>utf8_decode($this->getdesstate()),
				':descountry'=>utf8_decode($this->getdescountry()),
				':deszipcode'=>$this->getdeszipcode(),
				':desdistrict'=>$this->getdesdistrict()
			]);

			if(count($results) > 0) {
				$this->setData($results[0]);
			}
		}

				public static function setMsgError($msg) {

			$_SESSION[Address::SESSION_ERROR] = $msg;
		}

		public static function getMsgError() {
			$msg = (isset($_SESSION[Address::SESSION_ERROR])) ? $_SESSION[Address::SESSION_ERROR] : "";

			Address::clearMsgError();

			return $msg;
		}

		public static function clearMsgError() {
			$_SESSION[Address::SESSION_ERROR] = NULL;
		}
	}

?>