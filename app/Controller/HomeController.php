<?php

declare(strict_types=1);

namespace App\Controller;

use Kenzer\Exception\Http\HttpException;
use Kenzer\Http\Request;
use Kenzer\View\View;

class HomeController
{
    public function __invoke()
    {
        throw new HttpException(401, 'hello world');

        return View::make('pages/home', ['name' => 'enoch']);
    }

    public function somepoint(Request $request) {}
}
