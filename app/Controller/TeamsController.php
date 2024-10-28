<?php

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
