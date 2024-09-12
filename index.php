<?php

use App\Providers\MultiChatServiceProvider;

/*
* Time Zone Setting
*/

date_default_timezone_set('Europe/London');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';

$multiChatServiceProvider = MultiChatServiceProvider::getInstance();

try {
    $multiChatServiceProvider->registerServices();

    /**
     * Resolve connection service
     */
    $multiChatServiceProvider->resolve('connection');
    dump("success connected to db");

    $multiChatServiceProvider->scanDirectory(__DIR__ . '/app/Services/');

    $categoryService = $multiChatServiceProvider->resolve('CategoryService');
    $productsService = $multiChatServiceProvider->resolve('ProductsService');

} catch (PDOException $e) {
    dump($e->getMessage());
}
