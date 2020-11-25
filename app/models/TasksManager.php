<?php

namespace app\models;

class TasksManager
{
    protected $database;      // Database where tasks
    protected $user;          // Current user

    // Overall checklist to validation
    const VALIDATION_DATA           = 1;
    const VALIDATION_SPECIFIED_ID   = 2;
    const VALIDATION_IS_ADMIN       = 3;

    const VALIDATION_CHECKLIST = [
        'addTask'       => [self::VALIDATION_DATA],
        'updateTask'    => [self::VALIDATION_DATA, self::VALIDATION_SPECIFIED_ID, self::VALIDATION_IS_ADMIN]
    ];

    /**
     * Init
     * 
     * @param DB $database
     * @param User $user
     * @return void
     */
    public function __construct(DB $database, User $user)
    {
        $this->database = $database;
        $this->user = $user;
    }

    /**
     * Get tasks list
     * 
     * @return array List or empty array
     */
    public function getList(): array
    {
        $tableName = $this->database->getTableName();
        return $this->database->fetchRowMany("SELECT * FROM $tableName") ?? [];
    }

    /**
     * Validate form
     * 
     * @param array Fields values
     * @return bool Correct or not
     */
    public function validateNewTask($task): bool
    {
        $rulesCheck = [
            'taskName'  => ['minlength' => 5],
            'taskText'  => ['minlength' => 5, 'maxlength' => 250],
            'taskEmail' => ['type' => 'email'] 
        ];

        foreach ($rulesCheck as $field => $rule) {
            $specifiedField = $task[$field];

            // Check minlength
            if (($minlength = $rule['minlength'] ?? false) && strlen($specifiedField) < $minlength) {
                return false;
            }

            // Check maxlength
            if (($maxlength = $rule['maxlength'] ?? false) && strlen($specifiedField) > $maxlength) {
                return false;
            }

            // Check type
            $fieldType = $rule['type'] ?? 'text';
            if (($fieldType === 'email') && !filter_var($specifiedField, FILTER_VALIDATE_EMAIL)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param $operation
     * @param $task
     * @return array
     */
    public function validateRequest($operation, $task): array
    {
        // Get checklist for operation
        $checks = self::VALIDATION_CHECKLIST[$operation] ?? [];

        // #1 Validate data first
        if (in_array(self::VALIDATION_DATA, $checks, true) && !$this->validateNewTask($task)) {
            return [
                'success' => false,
                'message' => 'Validation failed'
            ];
        }

        // #2 ID might be specified in some cases
        if (empty($task['id']) && in_array(self::VALIDATION_SPECIFIED_ID, $checks, true)) {
            return [
                'success' => false,
                'message' => 'ID is not specified'
            ];
        }

        // #3 Edit only for admins
        if (in_array(self::VALIDATION_IS_ADMIN, $checks, true) && !$this->user->isAdmin()) {
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
     * @return array Request result
     */
    public function addTask($task): array
    {
        $validationResult = $this->validateRequest('addTask', $task);
        if ($validationResult['success'] === false) {
            return $validationResult;
        }

        // Task properties
        $newProperties = [
            'task'  => $task['taskName'],
            'text'  => $task['taskText'],
            'email' => $task['taskEmail']
        ];

        // Additional field(s) for admin
        if ($task['taskCompleted'] && $this->user->isAdmin()) {
            $newProperties['completed'] = $this->isChecked($task, 'taskCompleted');
        }

        $successfulRequest = $this->database->insert(
            $this->database->getTableName(),
            $newProperties
        );

        return [
            'success' => $successfulRequest,
            'message' => $successfulRequest ? 'Task added successfully' : 'Task add failed'
        ];
    }

    public function isChecked($array, $flag): bool
    {
        $arrayFlag = $array[$flag] ?? false;
        return $arrayFlag === 'on' || $arrayFlag === 1;
    }

    /**
     * Update task
     * 
     * @param array Task properties
     * @return array
     */
    public function updateTask($task): array
    {
        $validation = $this->validateRequest('updateTask', $task);
        if ($validation['success'] === false) {
            return $validation;
        }

        // Task properties
        $newProperties = [
            'task'          => $task['taskName'],
            'text'          => $task['taskText'],
            'email'         => $task['taskEmail'],
            'completed'     => $this->isChecked($task, 'taskCompleted'),
            'admin_edit'    => 1
        ];

        $successfully = $this->database->update(
            $this->database->getTableName(), 
            [
                'id' => $task['id']
            ],
            $newProperties
        );

        return [
            'success' => $successfully,
            'message' => $successfully ? 'Task updated successfully' : 'Task update failed'
        ];
    }
}
