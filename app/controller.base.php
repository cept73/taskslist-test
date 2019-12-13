<?php

namespace Todo;

abstract class BaseController
{
    protected $config;      // Properties
    protected $user;        // Current user information
    protected $database;    // Current database information
    protected $globalViewParams;


    /**
     * Initialize
     * 
     * @param array $config Needed data
     * @return void
     */
    function __construct($config)
    {
        $this->config = $config;

        $this->database = new Model\DB([
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
     */
    function view($viewName, $params = [])
    {
        // Add global params
        if (!empty($this->globalViewParams))
            $params = array_merge($this->globalViewParams, $params);
        if (!empty($this->config))
            $params = array_merge($this->config, $params);

        return View($viewName, $params);
    }


    /**
     * Show JSON
     * 
     * @param array $array data
     */
    function jsonOutput($array)
    {
        echo json_encode($array);
    }



    /**
     * Redirect to other page
     * 
     * @param string $pageUrl URL to go
     */
    function redirect($pageUrl, $prefix = '?action=')
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
    function getPath($request)
    {
        // LIMITATION FOR FREE VERSION: ?path=... passed throw GET
        // IN COMMERCIAL VERSION: Correct path with rewrite
        return $request['path'] ?? '';
    }


    /**
     * Is request match mask?
     * 
     * @param string $requestPath
     * @param string $mask
     * @return bool
     */
    function isPathMatch($requestUrl, $mask)
    {
        return ($requestUrl ?? '') == $mask;
    }


    /**
     * Is user match permissions?
     * 
     * @param array $permissions list
     */
    function isUserMatch($permissions)
    {
        // No requirements - always allowed
        if (empty($permissions)) return true;

        // If one requirement - make it array
        if (!is_array($permissions)) $permissions = [$permissions];

        // Check list, search fail
        foreach ($permissions as $required_state) {
            switch ($required_state) {
                case 'not auth' : if ($this->user->isLogged()) return false; break;
                case 'auth'     : if (!$this->user->isLogged()) return false; break;
                case 'admin'    : if (!$this->user->isAdmin()) return false; break;
            }
        }

        // True by default
        return true;
    }
    

    /**
     * Not found page
     * 
     * @return void
     */
    function viewNotFound()
    {
        return $this->view('error');
    }


    /**
     * Not found script
     * 
     * @return void
     */
    function scriptNotFound()
    {
        return json_encode([
            'status' => 'error', 'message' => 'Wrong request'
            ]);
    }


    /**
     * Not found
     * 
     * @return void
     */
    function viewDenied()
    {
        return $this->view('500');
    }


    /**
     * Get config param
     * 
     * @param string $param Parameter name
     * @return string Parameter value or null
     */
    function getConfig($param)
    {
        return $this->config[ $param ] ?? null;
    }


    public function getCurrentUser()
    {
        return new Model\User();
    }


    /**
     * Might be specified in child class
     */
    abstract public function resolve($url, $request, $routes);
}
