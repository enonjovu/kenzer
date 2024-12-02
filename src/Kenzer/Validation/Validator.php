<?php

declare(strict_types=1);

namespace Kenzer\Validation;

use Exception;
use Kenzer\Application\Application;
use Kenzer\Exception\Http\ValidationException;
use Kenzer\Interface\Validation\RuleInterface;
use Kenzer\Utility\ArrayHelper;

class Validator
{
    private array $errors;

    public function __construct(
        protected array $data,
        protected array $rules,
    ) {
        $this->errors = [];
    }

    protected function ruleAlias()
    {
        return [
            'required' => \Kenzer\Validation\Rules\RequiredRule::class,
            'email' => \Kenzer\Validation\Rules\EmailRule::class,
        ];
    }

    public static function create(array $data = [], array $rules = []) : static
    {
        return new static($data, $rules);
    }

    public function validate()
    {
        foreach ($this->rules as $field => $rules) {
            $value = $this->data[$field] ?? null;

            foreach ($rules as $validationRule) {
                $validationRule = $this->parseRule($validationRule);

                if ($validationRule->passes($value)) {
                    continue;
                }

                $this->addError($field, $validationRule->fail($field));
            }

        }
    }

    public function safe()
    {
        $errorFields = array_keys($this->getErrors());

        return ArrayHelper::where($this->data, fn ($v, $k) => ! in_array($k, $errorFields));
    }

    private function parseRule(mixed $rule) : RuleInterface
    {
        if ($rule instanceof RuleInterface) {
            return $rule;
        }


        if (is_string($rule) && class_exists($rule)) {
            $rule = container($rule);

            if ($rule instanceof RuleInterface) {
                return $rule;
            }
            throw new Exception('failed to parse rule ' . $rule);
        }

        if (is_string($rule) && array_key_exists($rule, $this->ruleAlias())) {
            return container($this->ruleAlias()[$rule]);
        }

        throw new Exception('failed to parse rule ' . $rule);
    }

    public function addError(string $field, string $message)
    {
        $this->errors[$field][] = $message;
    }

    public function failed()
    {
        return ! empty($this->errors);
    }

    public function getErrors()
    {
        return ArrayHelper::where($this->errors, fn ($value, $key) => ! empty ($value));
    }
}
