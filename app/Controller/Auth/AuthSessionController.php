<?php

declare(strict_types=1);

namespace App\Controller\Auth;

use Kenzer\Http\Request;
use Kenzer\Validation\Validator;

class AuthSessionController
{
    public function create(Request $request)
    {
        return view('pages/auth/login');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        dd($data);

    }

    public function destroy()
    {
    }
}
