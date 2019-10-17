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

	$app->get("/admin/users", function() {

		User::verifyLogin();

		$users = User::listAll();

		$page = new PageAdmin();

		$page->setTpl("users", array(
			"users"=>$users
		));
	});

	$app->get("/admin/users/create", function() {

		User::verifyLogin();

		$page = new PageAdmin();

		$page->setTpl("users-create");

	});

	// Esse tem que ser primeiro que o debaixo, pois se estivesse o contrário, quando eu chama-se
	// a rota ia confundir
	$app->get("/admin/users/:iduser/delete", function($iduser) {

		User::verifyLogin();

		$user = new User();

		$user->get((int)$iduser);

		$user->delete();

		header("Location: /admin/users");
		exit;

	});

	$app->get("/admin/users/:iduser", function($iduser) {

		User::verifyLogin();

		$user = new User();

		$user->get((int)$iduser);

		$page = new PageAdmin();

		$page->setTpl("users-update", array(
			"user" => $user->getValues()
		));
	});

	// rota para salvar
	$app->post("/admin/users/create", function() {

		User::verifyLogin();

		$user = new User();

		$_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;

		$user->setData($_POST);

		$user->save();

		header("Location: /admin/users");
		exit;
	});

	// Update
	$app->post("/admin/users/:iduser", function($iduser) {

		User::verifyLogin();

		$user = new User();

		$_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;

		$user->get((int)$iduser);

		$user->setData($_POST);

		$user->update();

		header("Location: /admin/users");
		exit;
	});

	$app->get("/admin/forgot", function() {

		$page = new PageAdmin([
			"header" => false,
			"footer" => false
		]);

		$page->setTpl("forgot");
	});

	$app->post("/admin/forgot", function() {

		$user = User::getForgot($_POST["email"]);

		header("Location: /admin/forgot/sent");
		exit;
	});

	$app->get("/admin/forgot/sent", function() {

		$page = new PageAdmin([
			"header" => false,
			"footer" => false
		]);

		$page->setTpl("forgot-sent");
	});

	$app->run();

?>