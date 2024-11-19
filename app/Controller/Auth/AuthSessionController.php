<?php

declare(strict_types=1);

namespace App\Controller\Auth;

use Kenzer\Http\Request;

class AuthSessionController
{
    public function create(Request $request)
    {
        return view('pages/auth/login');
    }

    public function store(Request $request)
    {
        dd($request);
    }

    public function destroy() {}
}
