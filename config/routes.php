<?php

declare(strict_types=1);
use App\Controller\Auth\AuthSessionController;
use App\Controller\Auth\RegistrationController;
use App\Controller\HomeController;
use App\Controller\ProfileController;
use App\Controller\ProjectsController;
use App\Controller\TeamsController;
use Kenzer\Routing\Router;

return function (Router $router) {
    $router->get('/', HomeController::class);

    $router->get('/profile', [ProfileController::class, 'index']);

    $router->get('/teams', [TeamsController::class, 'index']);
    $router->get('/teams/{team}/members', [TeamsController::class, 'members']);

    $router->get('/projects', [ProjectsController::class, 'index']);

    $router->get('/auth/register', [RegistrationController::class, 'create']);
    $router->post('/auth/register', [RegistrationController::class, 'store']);

    $router->get('/auth/login', [AuthSessionController::class, 'create']);
    $router->post('/auth/login', [AuthSessionController::class, 'store']);
};
