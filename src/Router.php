<?php

namespace App;

use JetBrains\PhpStorm\NoReturn;

class Router
{
    private array $routes = [];

    public function get(string $path, array $handlerFunc): void
    {
        $this->addRoute('GET', $path, $handlerFunc);
    }

    public function post(string $path, array $handlerFunc): void
    {
        $this->addRoute('POST', $path, $handlerFunc);
    }

    public function put(string $path, array $handlerFunc): void
    {
        $this->addRoute('PUT', $path, $handlerFunc);
    }

    public function delete(string $path, array $handlerFunc): void
    {
        $this->addRoute('DELETE', $path, $handlerFunc);
    }

    private function addRoute(string $method, string $path, array $handlerFunc): void
    {
        [$regex, $paramNames] = $this->compileRoute($path);
        $this->routes[$method][$regex] = [
            'urlParams' => $paramNames,
            'handlerFunc' => $handlerFunc
        ];
    }

    #[NoReturn]
    public function executeRequest(Request $request): string|null
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $params = [];
        $handler = null;

        $methodRoutes = $this->routes[$method] ?? [];
        if ($methodRoutes) {
            foreach ($methodRoutes as $regex => ['urlParams' => $urlParams, 'handlerFunc' => $handlerFunc]) {

                if ($regex[0] == "#") {
                    if (preg_match($regex, $url, $matches)) {
                        array_shift($matches); // убираем полный матч
                        $params = array_combine($urlParams, $matches); // создаем массив вида [ [id] => 16, ... ]
                        $handler = $handlerFunc;
                        break;
                    }
                } elseif (isset($methodRoutes[$url])) {
                    $handler = $methodRoutes[$url]['handlerFunc'];
                }
            }
        }

        if (!$handler) {
            http_response_code(404);
            return json_encode(['error' => 'Page not Found']);
        }

        [$controllerClass, $method] = $handler;

        $controller = new $controllerClass();

        return $this->callController(
            $controller,
            $method,
            $params,
            $request
        );
    }

    private function callController(object $controller, string $method, array $params, Request $request)
    {
        $arguments = [];

        foreach ($params as $value) {
            $arguments[] = $value;
        }

        // $request — всегда последний передается в экшен
        $arguments[] = $request;

        return $controller->$method(...$arguments);
    }

    private function compileRoute(string $route): array
    {
        if (!(str_contains($route, '{') && str_contains($route, '}'))) {
            return [$route, []];
        }
        preg_match_all('#\{([^}]+)\}#', $route, $matches);

        $paramNames = $matches[1]; // например ['id', 'test', 'test2']

        $regex = preg_replace(
            '#\{[^}]+\}#',
            '([^/]+)',
            $route
        );

        $regex = '#^' . $regex . '$#';

        return [$regex, $paramNames];
    }
}
