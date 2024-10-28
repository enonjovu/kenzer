<?php

namespace Kenzer\View;

class ViewContentCompiler
{
    private static ?ViewContentCompiler $instance = null;

    private function __construct(
        private array $replacements = []
    ) {
    }

    public static function create(array $directives = [])
    {
        return self::$instance ??= new static($directives);
    }

    public function addDirective(string $name, callable $callback)
    {
        $this->replacements[$name] = $callback;
    }

    public function process(string $subject) : string
    {
        // Regex to match directives with or without parentheses
        $pattern = '/@(' . implode('|', array_keys($this->replacements)) . ')(\(\s*(.*?)\s*\))?/';

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
