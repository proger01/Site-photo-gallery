<?php

use app\services\Database;
use app\services\Roles;
use Aura\SqlQuery\QueryFactory;
use Delight\Auth\Auth;
use JasonGrimes\Paginator;

function components($name)
{
    global $container;
    return $container->get($name);
}

function config($field)
{
    $config = require '../app/config.php';
    return array_get($config, $field);
}

function getCategory($id)
{
    global $container;
    $pdo = $container->get('PDO');
    $queryFactory = $container->get(QueryFactory::class);
    $database = new Database($queryFactory, $pdo);
    return $database->find('categories', $id);
}

function getAllCategories() {
    global $container;
    $pdo = $container->get('PDO');
    $queryFactory = $container->get('Aura\SqlQuery\QueryFactory');
    $database = new Database($queryFactory, $pdo);
    return $database->all('categories');
}

function uploadedDate($timestamp)
{
    return date('d.m.Y', $timestamp);
}

function getImage($image)
{
    return (new app\services\ImageManager())->getImage($image);
}

function getUserImage($image)
{
    return (new app\services\ImageManager())->getUserImage($image);
}

function getRole($key)
{
    return Roles::getRole($key);
}

function auth()
{
    global $container;
    return $container->get(Auth::class);
}

function paginate($count, $page, $perPage, $url)
{
    $totalItems = $count;
    $itemsPerPage = $perPage;
    $currentPage = $page;
    $urlPattern = $url;

    $paginator = new Paginator($totalItems, $itemsPerPage, $currentPage, $urlPattern);
    return $paginator;
}

function abort($type)
{
    switch ($type) {
        case 404:
            $view = components(\League\Plates\Engine::class);
            echo $view->render('errors/404');exit;
        break;
    }
}

function paginator($paginator)
{
    include config('views_path') . 'partials/pagination.php';
}

function redirect($path)
{
    header("Location: $path");
    exit;
}

function back()
{
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}