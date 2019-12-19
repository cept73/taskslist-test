<?php

namespace Todo\Model;

class DB
{
    var $connection = null;
    var $tableName = null;


    function __construct($settings = null)
    {
        // Connect with specified params or from env
        $this->connect($settings);
    }

    public function onError($error)
    {
        return false;

        //die (json_encode($error));
    }

    public function connect($settings = null)
    {
        // Already connected
        if ($this->connection != null)
            return $this->connection;

        $pdo = ( new \Simplon\Mysql\PDOConnector(
            $settings['db_host'],   // server
            $settings['db_user'],   // user
            $settings['db_pass'],   // password
            $settings['db_name']    // database
        ) )->connect('utf8', []);

        $this->tableName = $settings['db_table'];
        $this->connection = new \Simplon\Mysql\Mysql( $pdo );
        return $this->connection; 
    }

    private function operation($method, ...$params)
    {
        try {
            return @$this->connection->$method(...$params);
        }
        catch (\Exception $ex) {
            return $this->onError($ex);
        }
    }

    public function execute($sql)
    {
        return $this->operation('execute', $sql);
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function fetchColumn($sql, $reqParams=[])
    {
        return $this->operation('fetchColumn', $sql, $reqParams);
    }

    public function fetchRow($sql, $reqParams=[])
    {
        return $this->operation('fetchRow', $sql, $reqParams);
    }

    public function fetchRowMany($sql, $reqParams=[])
    {
        return $this->operation('fetchRowMany', $sql, $reqParams);
    }

    public function insert($tableName, $reqParams)
    {
        return $this->operation('insert', $tableName, $reqParams);
    }

    public function update($tableName, $conds, $reqParams)
    {
        return $this->operation('update', $tableName, $conds, $reqParams);
    }

    public function delete($tableName, $conditions)
    {
        return $this->operation('delete', $tableName, $conditions);
    }

}
