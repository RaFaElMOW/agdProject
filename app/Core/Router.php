<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    public function add(string $method, string $pattern, callable $handler, array $middleware = []): void
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'pattern' => $this->compile($pattern),
            'handler' => $handler,
            'middleware' => $middleware,
        ];
    }

    public function get(string $pattern, callable $handler, array $middleware = []): void
    {
        $this->add('GET', $pattern, $handler, $middleware);
    }

    public function post(string $pattern, callable $handler, array $middleware = []): void
    {
        $this->add('POST', $pattern, $handler, $middleware);
    }

    private function compile(string $pattern): string
    {
        $escaped = preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', '(?P<$1>[^/]+)', $pattern);
        return '#^' . $escaped . '$#';
    }

    public function dispatch(Request $request): void
    {
        $method = $request->method();
        $path = $request->path();

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            if (preg_match($route['pattern'], $path, $matches) === 1) {
                $params = array_filter($matches, fn ($key) => !is_int($key), ARRAY_FILTER_USE_KEY);
                $this->runPipeline($route['middleware'], $request, function () use ($route, $request, $params) {
                    ($route['handler'])($request, $params);
                });
                return;
            }
        }

        Response::notFound('Página não encontrada.');
    }

    private function runPipeline(array $middleware, Request $request, callable $final): void
    {
        $pipeline = array_reduce(
            array_reverse($middleware),
            function (callable $next, MiddlewareInterface $mw) {
                return function (Request $request) use ($mw, $next) {
                    return $mw->handle($request, $next);
                };
            },
            $final
        );

        $pipeline($request);
    }
}
