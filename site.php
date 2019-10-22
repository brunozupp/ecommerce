<?php 

	use \Hcode\Page;
	use \Hcode\Model\Category;
	use \Hcode\Model\Product;

	$app->get('/', function() {

		$products = Product::listAll();
	
		$page = new Page(); // Chama o método construct que cria o meu header

		$page->setTpl("index", [
			'products' => Product::checkList($products)
		]); // Chama o método setTpl para criar o body

		// Depois de executado tudo, vai entrar no destruct que é onde vai criar meu footer

	});

		// Tela que mostra uma categoria específica
	$app->get("/categories/:idcategory", function($idcategory) {

		$category = new Category();

		$category->get((int) $idcategory);

		$page = new Page();

		$page->setTpl("category", [
			'category' => $category->getValues(),
			'products' => Product::checkList($category->getProducts())
		]);
	});

?>