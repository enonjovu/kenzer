<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in(__DIR__)
    ->exclude(['vendor', 'node_modules', 'storage']) // Exclude these folders
    ->name('*.php') // Only consider PHP files
    ->notName('*.view.php'); // Exclude Blade templates if you're using Laravel

return (new Config())
    ->setFinder($finder)
    ->setRules([
        '@PSR12' => true, // Enforce PSR-12 coding standards
        'array_syntax' => ['syntax' => 'short'],
        'single_quote' => true,
        'no_unused_imports' => true,
        'binary_operator_spaces' => [
            'default' => 'single_space',
        ],
        'blank_line_after_namespace' => true,
        'blank_line_after_opening_tag' => true,
        'braces' => [
            'position_after_functions_and_oop_constructs' => 'next',
        ],
        'concat_space' => [
            'spacing' => 'one',
        ],
        'declare_strict_types' => true, // Adds strict types to PHP files
        'function_declaration' => [
            'closure_function_spacing' => 'none',
        ],
        'line_ending' => true,
        'lowercase_keywords' => true,
        'method_argument_space' => [
            'on_multiline' => 'ensure_fully_multiline',
        ],
        'no_empty_statement' => true,
        'no_extra_blank_lines' => [
            'tokens' => [
                'extra',
                'throw',
                'use',
                'return',
            ],
        ],
        'no_whitespace_in_blank_line' => true,
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'phpdoc_align' => ['align' => 'left'],
        'phpdoc_indent' => true,
        'phpdoc_no_empty_return' => true,
        'phpdoc_scalar' => true,
        'phpdoc_separation' => true,
        'phpdoc_trim' => true,
        'trailing_comma_in_multiline' => ['elements' => ['arrays']],
    ])
    ->setRiskyAllowed(true)
    ->setUsingCache(true);