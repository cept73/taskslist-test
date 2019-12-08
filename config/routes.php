<?php
    /*
     * Returns routes
     * 
     * Format: 'url'    => [
     *      'required_perm' => 'perm' | ['perm1','perm2',...],
     * 
     *      'view' => filename           -  trying to run view
     *      or 'action' => someAct       -  trying to run actionSomeAct in controller
     * ]
     * 
     * Supported permissions:
     *      empty - any user
     *      notauth - only not authorized
     *      auth - only authorized
     *      admin - only authorized admin
     */
    return [
        'GET' => [
            // Mainpage
            ''               => ['view' => 'homepage'],
            // User login/logout
            '/login'         => ['required_perm' => 'notauth', 'view' => 'login'],
            '/logout'        => ['required_perm' => 'auth', 'action' => 'logout'],
            // Admin's edit
            '/edit'          => ['required_perm' => 'admin', 'view' => 'edit'],
            // Json requests
            '/tasks'         => ['action' => 'getTasks'],
        ],

        'POST' => [
            // User login, json
            '/login'         => ['required_perm' => 'notauth',  'action' => 'login'],
            // Add task
            '/task'          => ['action' => 'addTask'],
        ]
    ];
