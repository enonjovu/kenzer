<?php

declare(strict_types=1);

$data = [
    'a' => 5,
    'b' => 2,
    'c' => 6,
    'd' => 1,
];

$data = array_filter($data, fn ($k) => $k);

var_dump($data);