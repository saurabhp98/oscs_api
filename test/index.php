<?php

// making types strict 
declare(strict_types=1);
// autoloading class by passing class inside the spl autoloader
spl_autoload_register(function ($class){
require __DIR__ . "/src/$class.php";
});
// Exception handler 
set_exception_handler("ErrorHandler::handleException");
// header
header("Content-Type:application/json; charset=UTF-8");
// splitting the requested uri using explode function 
$uridetails = explode('/', $_SERVER['REQUEST_URI']);
// checking the respected uri param to be exact to "product" then if function will be processed
if ($uridetails[3] != "products") {
   http_response_code(404);
   exit;
}

// checking for the passed id after product param if not found assining null
$id = $uridetails[4] ?? null;

// database
$database = new Database("217.21.84.154", "u506543126_test","u506543126_test", "Test@123");
$productGateway = new ProductGateway($database);


// INITIALIZING PRODUCT CONTROLLER CLASS
$productController = new ProductController($productGateway);
// RUNNING THE PROCESS REQUEST METHOD FOR THIS 
$productController->processRequest($_SERVER['REQUEST_METHOD'], $id);