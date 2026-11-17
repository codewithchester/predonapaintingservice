<?php
namespace App\Core;

final class Router
{
    private array $routes = [];

    public function get(string $path, callable|array $handler): void { $this->add('GET', $path, $handler); }
    public function post(string $path, callable|array $handler): void { $this->add('POST', $path, $handler); }

    private function add(string $method, string $path, callable|array $handler): void
    {
        $this->routes[] = compact('method', 'path', 'handler');
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        $base = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
        if ($base !== '' && $base !== '/' && str_starts_with($path, $base)) $path = substr($path, strlen($base));
        $path = '/' . trim($path, '/');
        if ($path === '//') $path = '/';
        foreach ($this->routes as $route) {
            if ($route['method'] !== strtoupper($method)) continue;
            $names = [];
            $pattern = preg_replace_callback('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', function ($m) use (&$names) { $names[] = $m[1]; return '([^/]+)'; }, $route['path']);
            if (!preg_match('#^' . $pattern . '/?$#', $path, $matches)) continue;
            array_shift($matches);
            $params = array_map('urldecode', $matches);
            $handler = $route['handler'];
            if (is_array($handler)) {
                [$class, $action] = $handler;
                (new $class())->$action(...$params);
            } else {
                $handler(...$params);
            }
            return;
        }
        http_response_code(404);
        view('pages/404', ['pageTitle' => 'Page not found']);
    }
}
