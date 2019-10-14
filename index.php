<?php  

	require_once("vendor/autoload.php");

	use \Slim\Slim;
	use \Hcode\Page;
	use \Hcode\PageAdmin;

	$app = new Slim();

	$app->config('debug', true);

	$app->get('/', function() {
		
		$page = new Page(); // Chama o método construct que cria o meu header

		$page->setTpl("index"); // Chama o método setTpl para criar o body

		// Depois de executado tudo, vai entrar no destruct que é onde vai criar meu footer

	});

	$app->get('/admin', function() {

		$page = new PageAdmin();

		$page->setTpl("index");

	});

	$app->run();

?>