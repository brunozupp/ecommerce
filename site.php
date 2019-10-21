<?php 

	use \Hcode\Page;

	$app->get('/', function() {
	
		$page = new Page(); // Chama o método construct que cria o meu header

		$page->setTpl("index"); // Chama o método setTpl para criar o body

		// Depois de executado tudo, vai entrar no destruct que é onde vai criar meu footer

	});

?>