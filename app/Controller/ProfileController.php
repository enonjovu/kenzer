<?php

declare(strict_types=1);

namespace App\Controller;

class ProfileController
{
    public function index()
    {
        return view('pages/profile');
    }
}
