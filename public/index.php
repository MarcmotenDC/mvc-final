<?php

declare(strict_types=1);

use Framework\Exceptions\PageNotFoundException;

set_error_handler(function(
    int $errno,
    string $errstr,
    string $errfile,
    int $errline
): bool
{
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

set_exception_handler(function (Throwable $exception) {

    if ($exception instanceof Framework\Exceptions\PageNotFoundException) {

        http_response_code(404);

        $template = "404.php";

    } else {
    
        http_response_code(500);

        $template = "500.php";

    }

    $show_errors = true;

    if ($show_errors) {

        ini_set("display_errors", "1");

        require ROOT_PATH . "/views/$template";

    } else {

        ini_set("display_errors", "0");

        ini_set("log_errors", "1");

        require ROOT_PATH . "/views/$template";

    }

    throw $exception;

});
// Server
// !Change URL for every site!


define("ROOT_PATH", dirname(__DIR__));

define("WEB_ROOT", "/webdev/SteveHarvey/mvc-final/public/");
$path = str_replace(WEB_ROOT, "", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

spl_autoload_register(function (string $class_name) {

    require ROOT_PATH . "/src/" . str_replace("\\", "/", $class_name) . ".php";
});

$dotenv = new Framework\Dotenv;

$dotenv->load(ROOT_PATH . "/.env");

$router = new Framework\Router;
// Routes
$router->add("/product/{slug:[\w-]+}", ["controller" => "products", "action" => "show"]);
$router->add("/{controller}/{id:\d+}/{action}");
$router->add("/home/index", ["controller" => "home", "action" => "index"]);
$router->add("/products", ["controller" => "products", "action" => "index"]);
$router->add("/products/show", ["controller" => "products", "action" => "show"]);
$router->add("/", ["controller" => "home", "action" => "index"]);
$router->add("/{controller}/{action}");
$router->add("/home", ["controller" => "home", "action" => "index"]);

$params = $router->matchRoute($path);
if ($params === false) {

    throw new PageNotFoundException("No matching route for '$path'.");
    
}
if ( !empty($params["id"]) ) {

    $id = $params["id"];

} else {

    $id = NULL;

}

$action = $params["action"];
$controller = "App\Controllers\\" . ucwords($params["controller"]);
$controller_object = new $controller;

if (!empty($id)) {
    $controller_object->$action($id);
} else {
    $controller_object->$action();
}