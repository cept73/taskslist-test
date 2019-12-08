<?php

namespace App\Model;

// Base class
require_once('app/models/model.base.php');

// Custom model
class TasksManager extends BaseModel {

    protected $database;      // Database where tasks
    protected $user;          // Current user
    private $table;           // Table


    /**
     * Init
     * 
     * @param $database \App\Model\DB
     * @param $user \App\Model\User
     * @return void
     */
    function __construct(\App\Model\DB $database, User $user)
    {
        $this->database = $database;
        $this->user = $user;
    }


    /**
     * Get tasks list
     * 
     * @return array List or empty array
     */
    function getList()
    {
        return $this->database->fetchRowMany(
            'SELECT * FROM ' . $this->database->getTableName()
        ) ?? [];
    }


    /**
     * Validate form
     * 
     * @param array Fields values
     * @return bool Correct or not
     */
    function validateNewTask($task)
    {
        $rulesCheck = [
            'taskName' => ['minlength' => 5],
            'taskText' => ['minlength' => 5, 'maxlength' => 250],
            'taskEmail' => ['type' => 'email'] 
        ];

        foreach ($rulesCheck as $field => $rule) {
            $specifiedField = $task[$field];

            // Check minlength
            if ($minlength = $rule['minlength'] ?? false) {
                if (strlen($specifiedField) < $minlength) return false;
            }

            // Check maxlength
            if ($maxlength = $rule['maxlength'] ?? false) {
                if (strlen($specifiedField) > $maxlength) return false;
            }

            // Check type
            $fieldType = $rule['type'] ?? 'text';
            if ($fieldType == 'email') {
                if (!filter_var($specifiedField, FILTER_VALIDATE_EMAIL))
                    return false;
            }
        }

        return true;
    }

    function validateRequest($operation, $task)
    {
        // Overall checklist to validation
        $checksOverallList = [
            'addTask' => [1],
            'updateTask' => [1,2,3]
        ];

        // Get checklist for operation
        $checks = $checksOverallList[$operation] ?? [];

        // #1 Validate data first
        if (in_array(1, $checks)) {
            if (!$this->validateNewTask($task))
                return [
                    'success' => false,
                    'message' => 'Validation failed' 
                ];
        }

        // #2 ID might be specified in some cases
        if (in_array(2, $checks)) {
            if (empty($task['id']))
                return [
                    'success' => false,
                    'message' => 'ID is not specified'
                ];
        }

        // #3 Edit only for admins
        if (in_array(3, $checks)) {
            if (!$this->user->isAdmin())
                return [
                    'success' => false,
                    'message' => 'Admin rights required'
                ];
        }

        // Else - validation complete successfully
        return [
            'success' => true
        ];
    }

    /**
     * Add task
     * 
     * @param array Task properties
     * @return bool All right or not
     */
    function addTask($task)
    {
        $validation = $this->validateRequest('addTask', $task);
        if ($validation['success'] == false)
            return $validation;

        // Task properties
        $newProperties = [
            'task'  => $task['taskName'],
            'text'  => $task['taskText'],
            'email' => $task['taskEmail']
        ];
        // Additional field(s) for admininstrator
        if ($this->user->isAdmin()) {
            if ($task['taskCompleted'])
                $newProperties['completed'] = $this->isChecked($task, 'taskCompleted');
        }

        $successfully = $this->database->insert(
            $this->database->getTableName(),
            $newProperties
        );

        return [
            'success' => $successfully,
            'message' => $successfully ? 'Task added successfully' : 'Task add failed'
        ];
    }

    function isChecked($array, $flag)
    {
        if ($array[$flag] ?? false == 'on') return true;
        if ($array[$flag] ?? false == 1) return true;
        return false;
    }

    /**
     * Update task
     * 
     * @param array Task properties
     * @return bool All right or not
     */
    function updateTask($task)
    {
        $validation = $this->validateRequest('updateTask', $task);
        if ($validation['success'] == false)
            return $validation;

        // Task properties
        $newProperties = [
            'task'  => $task['taskName'],
            'text'  => $task['taskText'],
            'email' => $task['taskEmail'],
            'completed'  => $this->isChecked($task, 'taskCompleted'),
            'admin_edit' => 1
        ];

        $successfully = $this->database->update(
            $this->database->getTableName(), 
            [ 'id' => $task['id'] ],
            $newProperties
        );

        return [
            'success' => $successfully,
            'message' => $successfully ? 'Task updated successfully' : 'Task update failed'
        ];
    }

}
