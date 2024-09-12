<?php

namespace App\Services;

use App\Services\ServiceInterface;

class ProductsService implements ServiceInterface
{
    public function __construct()
    {
        dump('ProductsService ...');
    }
}
