<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

define('APP_PATH', dirname(__DIR__));
define('PUBLIC_PATH', __DIR__);

require APP_PATH . '/core/Database.php';
require APP_PATH . '/core/Model.php';
require APP_PATH . '/core/Auth.php';

spl_autoload_register(function ($class) {
    $path = APP_PATH . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($path)) {
        require $path;
    }
});

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = str_replace('/ges stock', '', $uri);
$method = $_SERVER['REQUEST_METHOD'];

$routes = [
    'GET' => [
        '/' => 'DashboardController@index',
        '/login' => 'AuthController@loginForm',
        '/dashboard' => 'DashboardController@index',
        '/logout' => 'AuthController@logout',
        '/articles' => 'ArticleController@index',
        '/articles/([0-9]+)' => 'ArticleController@show',
        '/articles/create' => 'ArticleController@create',
        '/articles/([0-9]+)/edit' => 'ArticleController@edit',
        '/stock' => 'StockController@index',
        '/stock/mouvements' => 'StockController@mouvements',
        '/demandes' => 'DemandeController@index',
        '/demandes/([0-9]+)' => 'DemandeController@show',
        '/demandes/create' => 'DemandeController@create',
        '/approvisionnements' => 'ApprovisionnementController@index',
        '/inventaires' => 'InventaireController@index',
        '/rapports' => 'RapportController@index',
        '/change-password' => 'AuthController@changePasswordForm',
        '/admin/users' => 'AdminController@users',
        '/admin/logs' => 'AdminController@logs',
    ],
    'POST' => [
        '/login' => 'AuthController@login',
        '/articles' => 'ArticleController@store',
        '/articles/([0-9]+)' => 'ArticleController@update',
        '/articles/([0-9]+)/archive' => 'ArticleController@archive',
        '/stock/mouvement' => 'StockController@createMouvement',
        '/demandes' => 'DemandeController@store',
        '/demandes/([0-9]+)/validate' => 'DemandeController@validate',
        '/change-password' => 'AuthController@changePassword',
    ]
];

$found = false;

foreach ($routes[$method] ?? [] as $pattern => $handler) {
    $pattern = str_replace('/', '\/', $pattern);
    if (preg_match("^" . $pattern . "$", $uri, $matches)) {
        [$controller, $method] = explode('@', $handler);
        
        $controllerClass = "App\\Controllers\\$controller";
        $ctrl = new $controllerClass();
        
        array_shift($matches);
        call_user_func_array([$ctrl, $method], $matches);
        
        $found = true;
        break;
    }
}

if (!$found) {
    http_response_code(404);
    echo "Page non trouvée: $uri";
}
