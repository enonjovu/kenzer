<?php

namespace App\Controller;
use Kenzer\Http\Request;
use Kenzer\Validation\Validator;
use Kenzer\View\View;

class HomeController
{
    public function __invoke()
    {
        return View::make('pages/home', ['name' => 'enoch']);
    }

    public function somepoint(Request $request)
    {

    }
}
