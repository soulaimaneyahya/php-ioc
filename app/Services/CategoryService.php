<?php

namespace App\Services;

use App\Services\ServiceInterface;

class CategoryService implements ServiceInterface
{
    public function __construct()
    {
        dump('CategoryService ...');
    }
}
