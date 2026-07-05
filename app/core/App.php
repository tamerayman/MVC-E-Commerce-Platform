<?php

/**
 * Main application router.
 * Parses the URL and dispatches to the appropriate controller/action.
 */
class App
{
    protected $controller = 'HomeController';
    protected $action = null;
    protected $params = [];

    public static $isApi = false;

    public function __construct()
    {
        $this->prepareURL();
        $this->render();
    }

    /**
     * Parse the query string into controller, action, and params.
     */
    private function prepareURL()
    {
        $url = $_SERVER['QUERY_STRING'] ?? '';

        if (!empty($url)) {
            $parts = explode('&', $url);
            $routePath = $parts[0];
        } else {
            $routePath = '';
        }

        if (empty($routePath)) {
            self::$isApi = false;
            $this->controller = 'HomeController';
            $this->action = 'index';
            return;
        }

        $routePath = trim($routePath, '/');
        $urlSegments = explode('/', $routePath);

        if ($urlSegments[0] === 'api') {
            self::$isApi = true;

            $this->controller = (isset($urlSegments[1]) ? ucfirst($urlSegments[1]) : 'Home') . 'Controller';

            if (isset($urlSegments[2])) {
                if (is_numeric($urlSegments[2])) {
                    $this->params[] = $urlSegments[2];
                } else {
                    $this->action = $urlSegments[2];
                }
            }

            if (isset($urlSegments[3])) {
                $this->params[] = $urlSegments[3];
            }
        } else {
            self::$isApi = false;

            $this->controller = ucfirst($urlSegments[0]) . 'Controller';
            $this->action = $urlSegments[1] ?? 'index';

            if (isset($urlSegments[2])) {
                $this->params = array_slice($urlSegments, 2);
            }
        }
    }

    /**
     * Instantiate the controller and call the appropriate action.
     */
    private function render()
    {
        if (!class_exists($this->controller)) {
            http_response_code(404);
            if (self::$isApi) {
                echo json_encode([
                    'status'  => false,
                    'message' => 'Controller not found'
                ]);
            } else {
                View::load('errors/404');
            }
            return;
        }

        $controller = new $this->controller;

        if (self::$isApi) {
            $this->handleApiRequest($controller);
        } else {
            $this->handleWebRequest($controller);
        }
    }

    /**
     * Route API requests based on HTTP method.
     */
    private function handleApiRequest($controller)
    {
        $method = $_SERVER['REQUEST_METHOD'];

        switch ($method) {
            case 'GET':
                if (!empty($this->params)) {
                    call_user_func_array([$controller, 'show'], $this->params);
                } else {
                    call_user_func([$controller, 'index']);
                }
                break;

            case 'POST':
                call_user_func([$controller, $this->action ?? 'store']);
                break;

            case 'PUT':
                call_user_func_array([$controller, 'update'], $this->params);
                break;

            case 'DELETE':
                call_user_func_array([$controller, 'destroy'], $this->params);
                break;

            default:
                http_response_code(405);
                echo json_encode([
                    'status'  => false,
                    'message' => 'Method not allowed'
                ]);
        }
    }

    /**
     * Route web requests to controller actions.
     */
    private function handleWebRequest($controller)
    {
        $action = $this->action ?? 'index';

        if (method_exists($controller, $action)) {
            call_user_func_array([$controller, $action], $this->params);
        } else {
            http_response_code(404);
            View::load('errors/404');
        }
    }
}
