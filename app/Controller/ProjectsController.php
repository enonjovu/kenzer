<?php

declare(strict_types=1);

namespace App\Controller;

class ProjectsController
{
    public function index()
    {
        return view('pages/projects/index');
    }

    public function create() {}
}
