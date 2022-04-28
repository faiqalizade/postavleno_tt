<?php
namespace Core;

class Router
{
    public static array $routes = [];

    public static function __callStatic(string $name, array $arguments)
    {
        if (in_array($name, ['get', 'delete', 'post', 'put'])) {
            return self::addRoute(strtoupper($name), ...$arguments);
        }

        return 0;
    }

    protected static function addRoute(string $method, string $path, callable|array $callback): static
    {
        self::$routes[$method][$path] = [
            'path' => $path,
            'callback' => $callback,
            'arguments' => []
        ];

        return new static();
    }

    public static function match($requestPath): array|bool
    {
        $currentMethodRoutes = self::$routes[$_SERVER['REQUEST_METHOD']];

        if (array_key_exists($requestPath, $currentMethodRoutes)) {
            return $currentMethodRoutes[$requestPath];
        } else {
            foreach (array_keys($currentMethodRoutes) as $availableRoute)
            {
                if (preg_match("/{[A-Z0-9a-z]+}/", $availableRoute)) {
                    $pattern = preg_replace('/{[A-Z0-9a-z]+}/', "[A-Z0-9a-z]+", $availableRoute);
                    $pattern = str_replace("/", "\/", $pattern);
                    $pattern = "/^$pattern$/";
                    if (preg_match($pattern, $requestPath)) {

                        $routeArguments = [];
                        $availableRouteElements = explode('/', $availableRoute);
                        $currentRouteElements = explode('/', $requestPath);

                        if (count($currentRouteElements) !== count($availableRouteElements)) {
                            return false;
                        }

                        foreach ($availableRouteElements as $index => $element) {
                            if (preg_match("/{[A-Z0-9a-z]+}/", $element)) {
                                $argument = str_replace(["{", "}"], '', $element);
                                $routeArguments[$argument] = $currentRouteElements[$index];
                            }
                        }
                        return [
                            'route' => $currentMethodRoutes[$availableRoute],
                            'path' => $availableRoute,
                            'arguments' => $routeArguments
                        ];
                    }
                }
            }
        }
        return false;
    }
}
