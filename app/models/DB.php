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

    public function execute($sql)
    {
        try {
            return @$this->connection->execute($sql);
        }
        catch (\Exception $ex) {
            return $this->onError($ex);
        }
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function fetchColumn($sql, $data=[])
    {
        try {
            return @$this->connection->fetchColumn($sql, $data);
        }
        catch (\Exception $ex) {
            return $this->onError($ex);
        }
    }

    public function fetchRow($sql, $data=[])
    {
        try {
            return @$this->connection->fetchRow($sql, $data);
        }
        catch (\Exception $ex) {
            return $this->onError($ex);
        }
    }

    public function fetchRowMany($sql, $data=[])
    {
        try {
            return @$this->connection->fetchRowMany($sql, $data);
        }
        catch (\Exception $ex) {
            return $this->onError($ex);
        }
    }

    public function insert($tableName, $data)
    {
        try {    
            return @$this->connection->insert($tableName, $data);
        }
        catch (\Exception $ex) {
            return $this->onError($ex);
        }
    }

    public function update($tableName, $conds, $data)
    {
        try {
            return @$this->connection->update($tableName, $conds, $data);
        }
        catch (\Exception $ex) {
            return $this->onError($ex);
        }
    }

    public function delete($tableName, $conds)
    {
        try {
            return @$this->connection->delete($tableName, $conds);
        }
        catch (\Exception $ex) {
            return $this->onError($ex);
        }
    }

}
