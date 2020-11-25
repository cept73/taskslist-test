<?php

namespace app\models;

use Exception;
use Simplon\Mysql\Mysql;
use Simplon\Mysql\PDOConnector;

class DB
{
    /** @var ?Mysql $connection */
    public $connection;

    /** @var ?string $tableName */
    public $tableName;

    /**
     * DB constructor.
     * @param ?array $settings
     * @throws Exception
     */
    public function __construct(array $settings = null)
    {
        // Connect with specified params or from env
        $this->connect($settings);
    }

    /**
     * @param $error
     * @return bool
     * @noinspection ForgottenDebugOutputInspection
     */
    public function onError($error): bool
    {
        error_log(json_encode($error));
        return false;
   }

    /**
     * @param null $settings
     * @return Mysql|null
     * @throws Exception
     */
    public function connect($settings = null)
    {
        // Already connected
        if ($this->connection) {
            return $this->connection;
        }

        $this->tableName = $settings['db_table'];

        $pdo = (new PDOConnector($settings['db_host'], $settings['db_user'], $settings['db_pass'], $settings['db_name']))
            ->connect('utf8', []);
        $this->connection = new Mysql($pdo);

        return $this->connection; 
    }

    private function operation($method, ...$params)
    {
        try {
            return @$this->connection->$method(...$params);
        } catch (Exception $ex) {
            return $this->onError($ex);
        }
    }

    /**
     * @param $sql
     * @return bool
     */
    public function execute($sql): bool
    {
        return $this->operation('execute', $sql);
    }

    /**
     * @return ?string
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * @param string $sql
     * @param array $reqParams
     * @return ?string
     * @noinspection PhpUnused
     */
    public function fetchColumn(string $sql, $reqParams = [])
    {
        return $this->operation('fetchColumn', $sql, $reqParams);
    }

    /**
     * @param string $sql
     * @param array $reqParams
     * @return array|bool|null
     */
    public function fetchRow(string $sql, $reqParams = [])
    {
        return $this->operation('fetchRow', $sql, $reqParams);
    }

    /**
     * @param $sql
     * @param array $reqParams
     * @return array|bool|null
     */
    public function fetchRowMany($sql, $reqParams = [])
    {
        return $this->operation('fetchRowMany', $sql, $reqParams);
    }

    /**
     * @param $tableName
     * @param $reqParams
     * @return bool
     */
    public function insert($tableName, $reqParams): bool
    {
        return $this->operation('insert', $tableName, $reqParams);
    }

    /**
     * @param $tableName
     * @param $conditions
     * @param $reqParams
     * @return bool
     */
    public function update($tableName, $conditions, $reqParams): bool
    {
        return $this->operation('update', $tableName, $conditions, $reqParams);
    }

    /**
     * @param $tableName
     * @param $conditions
     * @return bool
     */
    public function delete($tableName, $conditions): bool
    {
        return $this->operation('delete', $tableName, $conditions);
    }
}
