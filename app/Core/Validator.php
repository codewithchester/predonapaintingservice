<?php
namespace App\Core;

final class Validator
{
    public static function required(array $data, array $fields): array
    {
        $errors = [];
        foreach ($fields as $field => $label) {
            if (trim((string) ($data[$field] ?? '')) === '') $errors[$field] = $label . ' is required.';
        }
        return $errors;
    }

    public static function email(string $email): bool { return (bool) filter_var($email, FILTER_VALIDATE_EMAIL); }
    public static function phone(string $phone): bool { return (bool) preg_match('/^[+0-9()\s.-]{7,25}$/', $phone); }
}
