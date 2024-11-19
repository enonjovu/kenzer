<?php

declare(strict_types=1);

namespace Kenzer\View\Compiler;

use Kenzer\Interface\View\ViewContentCompiler;

class DirectiveCompiler implements ViewContentCompiler
{
    private static ?ViewContentCompiler $instance = null;

    public function __construct(
        private array $replacements = []
    ) {}

    public function addDirective(string $name, callable $callback)
    {
        $this->replacements[$name] = $callback;
    }

    public function process(string $subject): string
    {
        $directives = implode('|', array_keys($this->replacements));

        // Regex to match directives with or without parentheses
        $pattern = '/@('.$directives.')(\(\s*(.*?)\s*\))?/';

        // Callback to replace the directive match based on the corresponding logic
        $callback = function ($matches) {
            $directive = $matches[1];  // Directive name (e.g., 'if', 'endif')
            $hasExpression = isset($matches[3]);  // Check if an expression exists
            $expression = $hasExpression ? $matches[3] : null;  // Get the expression if it exists

            // Check if replacement logic exists for the matched directive
            if (isset($this->replacements[$directive])) {
                // Call the replacement logic, passing the expression if needed
                return $this->replacements[$directive]($expression);
            }

            return $matches[0]; // Return the original match if no replacement found
        };

        // Perform the replacement
        return preg_replace_callback($pattern, $callback, $subject);
    }
}
