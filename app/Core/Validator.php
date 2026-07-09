<?php

namespace App\Core;

class Validator
{
    protected array $errors = [];

    /**
     * Validate data against rules
     * Example rules: ['email' => 'required|email', 'password' => 'required|min:8']
     */
    public function validate(array $data, array $rules): bool
    {
        $this->errors = [];

        foreach ($rules as $field => $ruleString) {
            $rulesArray = explode('|', $ruleString);
            $value = $data[$field] ?? null;

            foreach ($rulesArray as $rule) {
                if ($rule === 'required' && empty($value)) {
                    $this->addError($field, ucfirst(str_replace('_', ' ', $field)) . " is required.");
                } elseif ($rule === 'email' && !empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($field, "Invalid email format.");
                } elseif (strpos($rule, 'min:') === 0 && !empty($value)) {
                    $min = (int) substr($rule, 4);
                    if (strlen($value) < $min) {
                        $this->addError($field, ucfirst(str_replace('_', ' ', $field)) . " must be at least $min characters.");
                    }
                }
                // Add more rules as needed...
            }
        }

        return empty($this->errors);
    }

    protected function addError(string $field, string $message): void
    {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = [];
        }
        $this->errors[$field][] = $message;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
