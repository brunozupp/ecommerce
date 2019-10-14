<?php  

	session_start();

	require_once("vendor/autoload.php");

	use \Slim\Slim;
	use \Hcode\Page;
	use \Hcode\PageAdmin;
	use \Hcode\Model\User;

	$app = new Slim();

	$app->config('debug', true);

	$app->get('/', function() {
		
		$page = new Page(); // Chama o método construct que cria o meu header

		$page->setTpl("index"); // Chama o método setTpl para criar o body

		// Depois de executado tudo, vai entrar no destruct que é onde vai criar meu footer

	});

	$app->get('/admin', function() {

		User::verifyLogin();

		$page = new PageAdmin();

		$page->setTpl("index");

	});

	$app->get('/admin/login', function() {

		// Desabilitando o header e o footer
		$page = new PageAdmin([
			"header" => false,
			"footer" => false
		]);

		$page->setTpl("login");
	});

	$app->post('/admin/login', function() {

		User::login($_POST["login"], $_POST["password"]);

		header("Location: /admin");
		exit;
	});

	$app->get('/admin/logout', function() {

		User::logout();

		header("Location: /admin/login");
		exit;
		
	});

	$app->run();

?>