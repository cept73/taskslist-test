<?php

namespace app\controller;

use app\models\TasksManager;

class Controller extends BaseController
{
    const PAGE_AFTER_AUTH = '';

    /**
     * Resolve request
     * @param $url
     * @param $request
     * @param $routes
     * @return bool|void
     */
    public function resolve($url, $request, $routes)
    {
        $user = $this->user;

        // Add global info to all templates
        $this->globalViewParams = [
            'user' => $user->getInfo(),
        ];

        $method = $_SERVER['REQUEST_METHOD'];
        if (!isset($routes[$method])) {
            // Unknown method
            return $this->viewDenied();
        }

        // Seek route
        foreach ($routes[$method] as $route => $attributes) {
            // Path not match
            if (!$this->isPathMatch($url, $route)) {
                continue;
            }

            // User is not permitted
            if (!empty($attributes['required_perm']) && !$this->isUserMatch($attributes['required_perm'])) {
                return $this->viewDenied();
            }

            // Without view?
            if (!isset($attributes['view'])) {
                // Seek action
                $actionSubName      = ucfirst($attributes['action']) ?? 'Default';
                $actionMethodName   = "action$actionSubName";

                if (method_exists($this, $actionMethodName)) {
                    return $this->$actionMethodName($request);
                }

                return $this->actionNotFound($url);
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
    public function getTasksModel(): TasksManager
    {
        return new TasksManager(
            $this->database, // Get data from database
            $this->user      // Current user, who want to work with it
        );
    }

    /**
     * Actions list - to serve AJAX and other requests
     * @param $request
     */
    public function actionNotFound($request)
    {
        $this->jsonOutput([
            'error'     => 'Wrong action',
            'action'    => $request
        ]);
    }

    public function actionLogin($request): bool
    {
        if (! $this->user->login($request['login'], $request['password']) ) {
            return $this->view('login', [
                'error' => 'Wrong credentials', 
                'login' => $request['login']
            ]);
        }

        $this->redirect(self::PAGE_AFTER_AUTH);
        return true;
    }

    /** @noinspection PhpUnused */
    public function actionLogout(): bool
    {
        $this->user->logout();
        $this->redirect(self::PAGE_AFTER_AUTH);
        return true;
    }

    public function actionGetTasks()
    {
        $this->jsonOutput([
            'table'     => $this->getTasksModel()->getList(),
            'success'   => true,
        ]);
    }

    /** @noinspection PhpUnused */
    public function actionAddTask($request)
    {
        if (!empty($request['id'])) {
            if (!$this->user->isAdmin()) {
                $result = [
                    'success' => 'error',
                    'message' => 'Unauthorized'
                ];
            } else {
                $result = $this->getTasksModel()->updateTask($request);
            }
        } else {
            $result = $this->getTasksModel()->addTask($request);
        }

        $this->jsonOutput($result);
    }
}
