<?php  

	namespace Hcode;

	class Model {

		private $values = [];

		// Quando chama um método que não existe, aparece esse no lugar
		public function __call($name, $args) {

			// traga os indices 0, 1 e 2
			$method = substr($name, 0, 3);

			$fieldName = substr($name, 3, strlen($name));

			switch($method) {

				case "get":
					return $this->values[$fieldName];
				break;

				case "set":
					return $this->values[$fieldName] = $args[0];
				break;
			}
		}

		public function setData($data = array()) {

			foreach ($data as $key => $value) {
				
				// Tudo que eu crio dinâmicamento eu coloco entre {}
				$this->{"set".$key}($value);
			}
		}

		public function getValues() {
			return $this->values;
		}
	}

?>