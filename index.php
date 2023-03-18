<?php

use PDO;
use App\Providers\AppServiceProvider;

/*
* Time Zone Setting
*/
date_default_timezone_set('Europe/London');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';

$app = new AppServiceProvider();

$db = [
    "host" => "localhost",
    "dbname" => "php-ioc",
    "username" => "root",
    "password" => ""
];

try {
    // register service
    $app->register('conn', fn () => new PDO("mysql:host={$db['host']};dbname={$db['dbname']}", $db['username'], $db['password']));

    // resolve service
    $app->resolve('conn');
    dump("success connected to db");

    $app->scanDirectory(__DIR__ . '/app/Services/');
    $categoryService = $app->resolve('CategoryService');
    $productsService = $app->resolve('ProductsService');

} catch (PDOException $e) {
    dump($e->getMessage());
}
