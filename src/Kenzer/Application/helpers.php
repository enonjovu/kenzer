<?php
use Kenzer\View\View;

function view(string $path, array $options = []) : View
{
    return View::make($path, $options);
}
