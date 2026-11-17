<?php
namespace App\Controllers;

use App\Core\CSRF;

abstract class BaseController
{
    protected function requireCsrf(): void
    {
        if (!CSRF::verify($_POST['_token'] ?? null)) {
            http_response_code(419);
            view('pages/404', ['pageTitle' => 'Your session expired']);
            exit;
        }
    }

    protected function validateText(string $value, int $max = 2000): string
    {
        $value = trim($value);
        return mb_substr($value, 0, $max);
    }
}
