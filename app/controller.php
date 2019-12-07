<?php

namespace App;
use App\Model;

// Base class
require_once('app/controller.base.php');

// Custom controller
class Controller extends BaseController {

    /**
     * Resolve request
     */
    public function resolve($request, $routes)
    {
        // Add global info to all templates
        $this->globalViewParams = [
            'user' => $this->user->getInfo(),
        ];

        $method = $_SERVER['REQUEST_METHOD'];
        if (!isset($routes[$method])) {
            // Unknown method
            return $this->viewDenied();
        }


        // Seek route
        foreach ($routes[$method] as $route => $attributes) {
            // Path not match
            if (! $this->isPathMatch($request, $route) ) continue;

            // User is not permitted
            if (! $this->isUserMatch($attributes['required_perm'] ?? null) )
                return $this->viewDenied();

            // Without view?
            if (!isset($attributes['view'])) {

                // Seek action
                $actionMethodName = 'action' . (
                    ucfirst($attributes['action']) ?? 'Default'
                );

                if (method_exists( $this, $actionMethodName )) {
                    return $this->$actionMethodName($request);
                }
                return $this->actionNotFound($request);

            }

            // Pass route attributes to template
            $this->globalViewParams['route'] = $attributes;

            // Well done, we found it
            return $this->view($attributes['view'], $request);
        }

        // Else: not found
        return $this->viewNotFound();
    }


    /**
     * Get model for working with
     */
    function getTasksModel()
    {
        return new \App\Model\TasksManager(
            $this->database, // Get data from database
            $this->user      // Current user, who want to work with it
        );
    }


    /**
     * Actions list - to serve AJAX and other requests
     */

    function actionNotFound($request)
    {
        return $this->jsonOutput([
            'error' => 'Wrong action', 
            'action' => $request
            ]);
    }

    function actionLogin($request)
    {
        if (! $this->user->login($request['login'], $request['password']) ) {
            return $this->view('login', [
                'error' => 'Wrong credentials', 
                'login' => $request['login']
                ]);
        }
        return $this->redirect('');
    }

    function actionLogout($request)
    {
        $this->user->logout();
        return $this->redirect('');
    }

    function actionGetTasks($request)
    {
        return $this->jsonOutput([
            'table' => $this->getTasksModel()->getList(),
            'success' => true,
        ]);
    }

    function actionAddTask($request)
    {
        if (!empty($request['id'])) {
            if (!$this->user->isAdmin())
                return $this->redirect('login', '?path=');

            $success = $this->getTasksModel()->updateTask($request);
        }
        else
            $success = $this->getTasksModel()->addTask($request);

        if (!$success)
            $passedData = array_merge($request, [
                'addFormError' => 'Validation failed'
                ]);
        else
            // Do not pass old request 
            $passedData = [
                'addFormSuccess' => 'Task added successfully'
                ];

        return $this->view('homepage', $passedData);
    }

}
