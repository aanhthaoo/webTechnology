<?php
class Router
{
    private $routes = [];

    public function addRoute($method, $path, $controller, $action)
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'controller' => $controller,
            'action' => $action
        ];
    }

    public function get($path, $controller, $action)
    {
        $this->addRoute('GET', $path, $controller, $action);
    }

    public function post($path, $controller, $action)
    {
        $this->addRoute('POST', $path, $controller, $action);
    }

    public function dispatch($requestUri, $requestMethod)
    {
        $path = parse_url($requestUri, PHP_URL_PATH);

        foreach ($this->routes as $route) {
            if ($this->matchRoute($route['path'], $path) && $route['method'] === $requestMethod) {
                $params = $this->extractParams($route['path'], $path);
                return $this->callController($route['controller'], $route['action'], $params);
            }
        }

        // 404 Not Found
        $this->render404();
    }

    private function matchRoute($routePath, $requestPath)
    {
        $routePattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $routePath);
        $routePattern = '#^' . $routePattern . '$#';
        return preg_match($routePattern, $requestPath);
    }

    private function extractParams($routePath, $requestPath)
    {
        preg_match_all('/\{([^}]+)\}/', $routePath, $paramNames);
        $routePattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $routePath);
        preg_match('#^' . $routePattern . '$#', $requestPath, $paramValues);

        array_shift($paramValues); // Remove full match

        if (empty($paramNames[1])) {
            return [];
        }

        return array_combine($paramNames[1], $paramValues);
    }

    private function callController($controllerName, $action, $params = [])
    {
        $controllerFile = __DIR__ . "/../controllers/{$controllerName}.php";

        if (!file_exists($controllerFile)) {
            throw new Exception("Controller {$controllerName} not found");
        }

        require_once $controllerFile;
        $controller = new $controllerName();

        if (!method_exists($controller, $action)) {
            throw new Exception("Action {$action} not found in {$controllerName}");
        }

        return call_user_func_array([$controller, $action], array_values($params));
    }

    private function render404()
    {
        http_response_code(404);
        $viewFile = __DIR__ . '/../views/errors/404.php';
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            echo "<h1>404 - Page Not Found</h1>";
        }
    }
}
