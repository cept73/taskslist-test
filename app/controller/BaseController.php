<?php

namespace app\controller;

use app\models\DB;
use app\models\User;
use Exception;
use Jenssegers\Blade\Blade;

abstract class BaseController
{
    protected $config;      // Properties
    protected $user;        // Current user information
    protected $database;    // Current database information
    protected $globalViewParams;

    /**
     * Might be specified in child class
     * @param $url
     * @param $request
     * @param $routes
     */
    abstract public function resolve($url, $request, $routes);

    /**
     * Initialize
     *
     * @param array $config Needed data
     * @throws Exception
     */
    public function __construct(array $config)
    {
        $this->config = $config;

        $this->database = new DB([
            'db_host' => getenv('DB_HOST'),
            'db_user' => getenv('DB_USER'),
            'db_pass' => getenv('DB_PASS'),
            'db_name' => getenv('DB_NAME'),
            'db_table'=> getenv('DB_TABLE')
        ]);

        $this->user = $this->getCurrentUser();
    }

    /**
     * Show view finally
     *
     * @param string $viewName filename from views/ folder
     * @param array $params to pass filename
     * @return bool
     */
    public function view(string $viewName, $params = []): bool
    {
        // Add global params
        if (!empty($this->globalViewParams)) {
            $params = array_merge($this->globalViewParams, $params);
        }

        if (!empty($this->config)) {
            $params = array_merge($this->config, $params);
        }

        // Render some Blade view
        $blade = new Blade('views', 'cache');
        try {
            $renderResult = $blade->render($viewName, $params);
            $success = true;
        } catch (Exception $ex) {
            $renderResult = $blade->render('error', [
                'fileName' => $viewName,
                'params' => $params
            ]);
            $success = false;
        }

        echo $renderResult;
        return $success;
    }

    /**
     * Show JSON
     *
     * @param array $array data
     */
    public function jsonOutput(array $array)
    {
        echo json_encode($array);
    }

    /**
     * Redirect to other page
     *
     * @param string $pageUrl URL to go
     * @param string $prefix
     * @return void
     */
    public function redirect(string $pageUrl, $prefix = '?action=')
    {
        $fullUrl = empty($pageUrl) ? '/' : $prefix . $pageUrl;
        return header("location: $fullUrl");
    }

    /**
     * Main function
     *
     * @param array $request What passed
     * @return string $path
     */
    public function getPath(array $request): string
    {
        return $request['path'] ?? '';
    }

    /**
     * Is request match mask?
     *
     * @param string $requestUrl
     * @param string $mask
     * @return bool
     */
    public function isPathMatch(string $requestUrl, string $mask): bool
    {
        return ($requestUrl ?? '') === $mask;
    }


    /**
     * Is user match permissions?
     *
     * @param array|string $permissions list
     * @return bool
     */
    public function isUserMatch($permissions): bool
    {
        // No requirements - always allowed
        if (empty($permissions)) {
            return true;
        }

        // If one requirement - make it array
        if (!is_array($permissions)) {
            $permissions = [$permissions];
        }

        $user = $this->user;

        // Check list, search fail
        foreach ($permissions as $required_state) {
            switch ($required_state) {
                case 'not auth':
                    if ($user->isLogged()) {
                        return false;
                    }
                    break;
                case 'auth':
                    if (!$user->isLogged()) {
                        return false;
                    }
                    break;
                case 'admin':
                    if (!$user->isAdmin()) {
                        return false;
                    }
                    break;
            }
        }

        // True by default
        return true;
    }
    
    /**
     * Not found page
     */
    public function viewNotFound(): bool
    {
        return $this->view('error');
    }

    /**
     * Not found script
     * 
     * @return string
     */
    public function scriptNotFound(): string
    {
        return json_encode([
            'status' => 'error',
            'message' => 'Wrong request'
        ]);
    }

    /**
     * Not found
     * 
     * @return bool
     */
    public function viewDenied(): bool
    {
        return $this->view('500');
    }

    /**
     * Get config param
     *
     * @param string $param Parameter name
     * @return ?string Parameter value or null
     */
    public function getConfig(string $param)
    {
        return $this->config[$param] ?? null;
    }

    public function getCurrentUser(): User
    {
        return new User();
    }
}
