<?php

use DI\ContainerBuilder;
use League\Plates\Engine;
use FastRoute\RouteCollector;
use Aura\SqlQuery\QueryFactory;
use Delight\Auth\Auth;

$containerBuilder = new ContainerBuilder;
$containerBuilder->addDefinitions([
    Engine::class => function() {
        return new Engine('../app/views');
    },
    Swift_Mailer::class => function() {
        $transport = (new Swift_SmtpTransport(
            config('mail.smtp'),
            config('mail.port'),
            config('mail.encryption')
        ))
        ->setUsername(config('mail.mail'))
        ->setPassword(config('mail.password'));
        return new Swift_Mailer($transport);
    },
    QueryFactory::class => function() {
        return new QueryFactory('mysql');
    },
    PDO::class => function() {
        $driver = config('database.driver');
        $host = config('database.host');
        $database_name = config('database.database_name');
        $username = config('database.username');
        $password = config('database.password');
        return new PDO("$driver:host=$host; dbname=$database_name", $username, $password);
    },
    Auth::class => function($container) {
        return new Auth($container->get('PDO'));
    },
]);

$container = $containerBuilder->build();

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->get('/', ['app\controllers\HomeController', 'index']);
    $r->get('/category/{id:\d+}', ['app\controllers\HomeController', 'category']);
    $r->get('/user/{id:\d+}', ['app\controllers\HomeController', 'user']);

    
    $r->get('/register', ['app\controllers\RegisterController', 'showForm']);
    $r->get('/login', ['app\controllers\LoginController', 'showForm']);
    $r->get('/password-recovery', ['app\controllers\ResetPasswordController', 'showForm']);
    $r->post('/password-recovery', ['app\controllers\ResetPasswordController', 'recovery']);
    $r->get('/password-recovery/form', ['app\controllers\ResetPasswordController', 'showSetForm']);
    $r->post('/password-recovery/change', ['App\Controllers\ResetPasswordController', 'change']);
    $r->get('/email-verification', ['app\controllers\VerificationController', 'showForm']);
    $r->post('/email-reconfirm', ['app\controllers\VerificationController', 'reconfirmEmailForm']);
    $r->get('/verify_email', ['app\controllers\VerificationController', 'verify']);
    
    $r->post('/register', ['app\controllers\RegisterController', 'register']);
    $r->post('/login', ['app\controllers\LoginController', 'login']);
    $r->get('/logout', ['app\controllers\LoginController', 'logout']);


    $r->get('/profile/info', ['app\controllers\ProfileController', 'showInfo']);
    $r->post('/profile/info', ['app\controllers\ProfileController', 'postInfo']);
    
    $r->get('/profile/security', ['app\controllers\ProfileController', 'showSecurity']);
    $r->post('/profile/security', ['app\controllers\ProfileController', 'postSecurity']);
    
    $r->get('/photos', ['app\controllers\PhotosController', 'index']);
    $r->get('/photos/{id:\d+}', ['app\controllers\PhotosController', 'show']);
    $r->get('/photos/{id:\d+}/download', ['app\controllers\PhotosController', 'download']);
    $r->get('/photos/create', ['app\controllers\PhotosController', 'create']);
    $r->post('/photos/store', ['app\controllers\PhotosController', 'store']);
    $r->get('/photos/{id:\d+}/edit', ['app\controllers\PhotosController', 'edit']);
    $r->post('/photos/{id:\d+}/update', ['app\controllers\PhotosController', 'update']);
    $r->get('/photos/{id:\d+}/delete', ['app\controllers\PhotosController', 'delete']);
    
    $r->addGroup('/admin', function (RouteCollector $r) {
        $r->get('', ['app\controllers\admin\HomeController', 'index']);

        $r->get('/categories', ['app\controllers\admin\CategoriesController', 'index']);
        $r->get('/categories/create', ['app\controllers\admin\CategoriesController', 'create']);
        $r->post('/categories/store', ['app\controllers\admin\CategoriesController', 'store']);
        $r->get('/categories/{id:\d+}/edit', ['app\controllers\admin\CategoriesController', 'edit']);
        $r->post('/categories/{id:\d+}/update', ['app\controllers\admin\CategoriesController', 'update']);
        $r->get('/categories/{id:\d+}/delete', ['app\controllers\admin\CategoriesController', 'delete']);
        
        $r->get('/users', ['app\controllers\admin\UsersController', 'index']);
        $r->get('/users/create', ['app\controllers\admin\UsersController', 'create']);
        $r->post('/users/store', ['app\controllers\admin\UsersController', 'store']);
        $r->get('/users/{id:\d+}/edit', ['app\controllers\admin\UsersController', 'edit']);
        $r->post('/users/{id:\d+}/update', ['app\controllers\admin\UsersController', 'update']);
        $r->get('/users/{id:\d+}/delete', ['app\controllers\admin\UsersController', 'delete']);
        
        $r->get('/photos', ['app\controllers\admin\PhotosController', 'index']);
        $r->get('/photos/create', ['app\controllers\admin\PhotosController', 'create']);
        $r->post('/photos/store', ['app\controllers\admin\PhotosController', 'store']);
        $r->get('/photos/{id:\d+}/edit', ['app\controllers\admin\PhotosController', 'edit']);
        $r->post('/photos/{id:\d+}/update', ['app\controllers\admin\PhotosController', 'update']);
        $r->get('/photos/{id:\d+}/delete', ['app\controllers\admin\PhotosController', 'delete']);
    });
});

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        echo '404 Not Found';
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        echo '405 Method Not Allowed';
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        $container->call($handler, $vars);
        break;
}