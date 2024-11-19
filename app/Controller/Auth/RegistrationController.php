<?php

declare(strict_types=1);

namespace App\Controller\Auth;

class RegistrationController
{
    public function create()
    {
        return view('pages/auth/register');
    }

    public function store() {}
}
