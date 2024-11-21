<?php

declare(strict_types=1);

namespace App\Controller;

use Kenzer\Http\Request;
use Kenzer\Utility\AttributeBag;
use Kenzer\View\View;

class HomeController
{
    public function __invoke()
    {
        $bag = new AttributeBag(['user' => ['name' => 'enoch']]);

        return View::make('pages/home', ['name' => 'enoch']);
    }

    public function somepoint(Request $request) {}
}
