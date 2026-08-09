<?php

namespace App\Core;

class Validator
{
    protected array $errors = [];

    /**
     * Validate data against rules.
     *
     * Supported rules (pipe-separated):
     *   required, email, url, numeric, int, alpha, alnum, confirmed,
     *   min:N, max:N, length:N, regex:/pattern/, in:a,b,c
     *
     * Example: ['email' => 'required|email|max:254', 'password' => 'required|min:12|max:1024']
     */
    public function validate(array $data, array $rules): bool
    {
        $this->errors = [];

        foreach ($rules as $field => $ruleString) {
            $rulesArray = explode('|', $ruleString);
            $value = $data[$field] ?? null;

            foreach ($rulesArray as $rule) {
                if ($rule === 'required') {
                    if (is_string($value)) {
                        $value = trim($value);
                    }
                    if ($value === null || $value === '') {
                        $this->addError($field, ucfirst(str_replace('_', ' ', $field)) . " is required.");
                    }
                } elseif ($rule === 'email') {
                    if (!empty($value) && (strlen($value) > 254 || !filter_var($value, FILTER_VALIDATE_EMAIL))) {
                        $this->addError($field, "Invalid email format.");
                    }
                } elseif ($rule === 'url') {
                    if (!empty($value) && !filter_var($value, FILTER_VALIDATE_URL)) {
                        $this->addError($field, "Invalid URL format.");
                    }
                } elseif ($rule === 'numeric') {
                    if (!empty($value) && !is_numeric($value)) {
                        $this->addError($field, ucfirst(str_replace('_', ' ', $field)) . " must be a number.");
                    }
                } elseif ($rule === 'int') {
                    if (!empty($value) && filter_var($value, FILTER_VALIDATE_INT) === false) {
                        $this->addError($field, ucfirst(str_replace('_', ' ', $field)) . " must be a whole number.");
                    }
                } elseif ($rule === 'alpha') {
                    if (!empty($value) && !ctype_alpha(str_replace([' ', '-', "'"], '', $value))) {
                        $this->addError($field, ucfirst(str_replace('_', ' ', $field)) . " may only contain letters.");
                    }
                } elseif ($rule === 'alnum') {
                    if (!empty($value) && !ctype_alnum(str_replace([' ', '-', '_'], '', $value))) {
                        $this->addError($field, ucfirst(str_replace('_', ' ', $field)) . " may only contain letters and numbers.");
                    }
                } elseif ($rule === 'confirmed') {
                    if ($value !== ($data[$field . '_confirmation'] ?? null)) {
                        $this->addError($field, ucfirst(str_replace('_', ' ', $field)) . " confirmation does not match.");
                    }
                } elseif (strpos($rule, 'min:') === 0) {
                    if (!empty($value)) {
                        $min = (int) substr($rule, 4);
                        if (strlen((string)$value) < $min) {
                            $this->addError($field, ucfirst(str_replace('_', ' ', $field)) . " must be at least $min characters.");
                        }
                    }
                } elseif (strpos($rule, 'max:') === 0) {
                    if (!empty($value)) {
                        $max = (int) substr($rule, 4);
                        if (strlen((string)$value) > $max) {
                            $this->addError($field, ucfirst(str_replace('_', ' ', $field)) . " must be at most $max characters.");
                        }
                    }
                } elseif (strpos($rule, 'length:') === 0) {
                    if (!empty($value)) {
                        $len = (int) substr($rule, 7);
                        if (strlen((string)$value) !== $len) {
                            $this->addError($field, ucfirst(str_replace('_', ' ', $field)) . " must be exactly $len characters.");
                        }
                    }
                } elseif (strpos($rule, 'regex:') === 0) {
                    if (!empty($value)) {
                        $pattern = substr($rule, 6);
                        if (@preg_match($pattern, (string)$value) !== 1) {
                            $this->addError($field, ucfirst(str_replace('_', ' ', $field)) . " has an invalid format.");
                        }
                    }
                } elseif (strpos($rule, 'in:') === 0) {
                    if (!empty($value)) {
                        $allowed = explode(',', substr($rule, 3));
                        if (!in_array($value, $allowed, true)) {
                            $this->addError($field, ucfirst(str_replace('_', ' ', $field)) . " is not a valid choice.");
                        }
                    }
                }
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
