<?php

use Exception;
use PDOException;
use App\Providers\MultiChatServiceProvider;

/*
* Time Zone Setting
*/

date_default_timezone_set('Europe/London');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';

$app = MultiChatServiceProvider::getInstance();

try {
    $app->registerSharedServices();

    // Testing methods
    dump("Container after registering shared connection service:");
    dump($app);

    // Resolve connection service twice
    $connection = $app->resolve('connection');

    dump("Resolved connection service:");
    dump($connection);

    // Check if connection is registered
    $hasConnection = $app->has('connection');
    dump("Connection registered?");
    dump($hasConnection);

    // Scan directory for services
    $app->scanDirectory(__DIR__ . '/app/Services/');

    dump("Container after scanning services directory:");
    dump($app);

    // Resolve scanned services
    $categoryService = $app->resolve('CategoryService');
    dump("Resolved CategoryService:");
    dump($categoryService);

    $productsService = $app->resolve('ProductsService');
    dump("Resolved ProductsService:");
    dump($productsService);

} catch (PDOException $e) {
    dump($e->getMessage());
} catch (Exception $e) {
    dump("Error: " . $e->getMessage());
}
