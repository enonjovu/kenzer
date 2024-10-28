<?php

declare(strict_types=1);

namespace App\Controller;

class TeamsController
{
    public function index()
    {
        return view('pages/teams/index');
    }

    public function members(string $team)
    {
        return view('pages/teams/members', ['team' => $team]);
    }
}
