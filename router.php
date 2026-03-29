<?php
/**
 * puertoctay.info — Router
 * Despacha URI a Controller@action con soporte para {slug}.
 */

class Router
{
    private array $routes = [];

    public function add(string $method, string $pattern, string $handler): void
    {
        $this->routes[] = [
            'method'  => strtoupper($method),
            'pattern' => $pattern,
            'handler' => $handler,
        ];
    }

    public function dispatch(string $uri, string $method): void
    {
        $method = strtoupper($method);

        // HEAD requests se tratan como GET
        $matchMethod = ($method === 'HEAD') ? 'GET' : $method;

        // Limpiar URI: quitar query string y trailing slash
        $uri = parse_url($uri, PHP_URL_PATH) ?? "/";

        // Quitar prefijo /puertoctay si existe (desarrollo local)
        $basePath = '/puertoctay';
        if (str_starts_with($uri, $basePath)) {
            $uri = substr($uri, strlen($basePath));
        }

        $uri = rtrim($uri, '/') ?: '/';

        foreach ($this->routes as $route) {
            if ($route['method'] !== $matchMethod) {
                continue;
            }

            $regex = $this->patternToRegex($route['pattern']);

            if (preg_match($regex, $uri, $matches)) {
                // Extraer parámetros nombrados
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                [$controllerName, $action] = explode('@', $route['handler']);
                $controller = new $controllerName(getDB());

                call_user_func_array([$controller, $action], array_values($params));
                return;
            }
        }

        // 404
        http_response_code(404);
        require ROOT_PATH . '/views/errors/404.php';
    }

    private function patternToRegex(string $pattern): string
    {
        // Convertir {param} a grupo nombrado (?P<param>[^/]+)
        $regex = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $pattern);
        return '#^' . $regex . '$#';
    }
}
