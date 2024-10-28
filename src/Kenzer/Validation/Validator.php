<?php

namespace Kenzer\Validation;
use Kenzer\Application\Application;
use Kenzer\Exception\Http\ValidationException;
use Kenzer\Interface\Validation\RuleInterface;

class Validator
{
    private array $alias = [
        'required' => \Kenzer\Validation\Rules\RequiredRule::class,
    ];

    private array $errors = [];

    /**
     * @var array<string,RuleInterface>
     */
    private array $compiledRules = [];

    public function __construct(
        private $data = [],
        private $rules = [],
    ) {
        $this->errors = [];
        $this->compiledRules = $this->processRules();
    }

    public function validate(string $field)
    {
        $this->errors[$field] = [];

        $value = $this->data[$field];
        $rules = $this->compiledRules[$field];

        foreach ($rules as $rule) {

            if (! $rule->passes($value)) {
                $this->errors[$field][] = $rule->fail($field);
            }

        }

        if (empty($this->errors[$field])) {
            unset($this->errors[$field]);
        }

        return $this;
    }

    public function hasErrors()
    {
        return ! empty($this->errors);
    }

    public function hasFieldError(string $name)
    {
        return ! empty($this->getFieldErrors($name));
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getFieldErrors(string $name)
    {
        return $this->errors[$name] ?? [];
    }

    public function validated()
    {
        $data = [];

        foreach ($this->data as $key => $value) {
            $this->validate($key);
        }

        if ($this->hasErrors()) {
            throw ValidationException::create(
                $this->data,
                $this->errors
            );
        }

        return array_intersect_key($this->data, $this->errors);
    }

    public static function create($data = [], $rules = []) : static
    {
        return new static($data, $rules);
    }

    private function processRules()
    {
        $compiledRules = [];

        foreach ($this->rules as $key => $rules) {
            foreach ($rules as $rule) {
                if (is_object($rule) && $rule instanceof RuleInterface) {
                    $compiledRules[$key][] = $rule;
                    continue;
                }

                if (is_string($rule)) {


                    if (class_exists($rule) && Application::getInstance()->get($rule) instanceof RuleInterface) {
                        $compiledRules[$key][] = Application::getInstance()->get($rule);
                        continue;
                    }

                    if (! array_key_exists($rule, $this->alias)) {
                        continue;
                    }


                    [$rule, $params] = explode(":", $rule, 2);

                    $params ??= [];

                    if (is_string($params)) {
                        $params = explode(",", $params);
                    }

                    $compiledRules[$key][] = $params ? new $this->alias[$rule]($params) : new $this->alias[$rule];
                }
            }
        }

        return $compiledRules;
    }
}
