<?php  

	namespace Hcode;

	use Rain\TpL;

	class Page {

		private $tpl;

		private $options = [];

		private $defaults = [
			"header" => true,
			"footer" => true,
			"data" => []
		];

		public function __construct($opts = array(), $tpl_dir = "/views/") { // primeiro método a ser executado

			// array_merge mescla. O último sempre vai sobreescrever os anteriores
			$this->options = array_merge($this->defaults, $opts);

			$config = array(
				"tpl_dir" => $_SERVER["DOCUMENT_ROOT"].$tpl_dir,
				"cache_dir" => $_SERVER["DOCUMENT_ROOT"]."/views-cache/",
				"debug" => false
			);

			TpL::configure($config);

			$this->tpl = new TpL;

			$this->setData($this->options["data"]);

			if($this->options["header"] === true) $this->tpl->draw("header");
		}

		public function setData($data = array()) {

			foreach($data as $key => $value) {
				$this->tpl->assign($key, $value);
			}
		}

		// O corpo da página, esse html que vai ser trabalhado mais vezes
		public function setTpl($name, $data = array(), $returnHTML = false) {

			$this->setData($data);

			return $this->tpl->draw($name, $returnHTML);
		}

		public function __destruct() { // último método a ser executado

			if($this->options["footer"] === true) $this->tpl->draw("footer");
		}
	}

?>