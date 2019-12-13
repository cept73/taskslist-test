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

    private function operation($method, ...$data)
    {
        try {
            return @$this->connection->$method(...$data);
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

    public function fetchColumn($sql, $data=[])
    {
        return $this->operation('fetchColumn', $sql, $data);
    }

    public function fetchRow($sql, $data=[])
    {
        return $this->operation('fetchRow', $sql, $data);
    }

    public function fetchRowMany($sql, $data=[])
    {
        return $this->operation('fetchRowMany', $sql, $data);
    }

    public function insert($tableName, $data)
    {
        return $this->operation('insert', $tableName, $data);
    }

    public function update($tableName, $conds, $data)
    {
        return $this->operation('update', $tableName, $conds, $data);
    }

    public function delete($tableName, $conds)
    {
        return $this->operation('delete', $tableName, $conds);
    }

}
