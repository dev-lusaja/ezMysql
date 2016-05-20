<?php

class ezMysqlConnectionError extends Exception{};
class ezMysqlDisconnectError extends Exception{};
class ezMysqlChangeDbError extends Exception{};
class ezMysqlQueryError extends Exception{};
class ezMysqlConstructError extends Exception{};
class ezMysqlInsertedIdError extends Exception{};

class ezMysql {

    private static $instance;
    private $connection;

    /**
    * Singleton pattern
    **/
    public static function getconnection($server, $user = '', $pass = '', $db = '')
    {
        if( is_null(self::$instance) ) {
            self::$instance = new self($server, $user, $pass, $db);
        }
        return self::$instance;
    }


    protected function __construct($server, $user, $pass, $db)
    {
        if (empty($server) or empty($user)) {
            throw new ezMysqlConstructError("Data connection is required");
        }

        $this->connection = new mysqli($server, $user, $pass, $db);

        if($this->connection->connect_errno){
            throw new ezMysqlConnectionError("Connection error (". $this->connection->connect_errno .") : " . $this->connection->connect_error);
        }
    }



    public function Disconnect()
    {
        if(!$this->connection->close()){
            throw new ezMysqlDisconnectError(self::$resource->error); 
        }
    }
    

    public function ChangeDb($db)
    {
        if (!$this->connection->select_db($db)) {
            throw new ezMysqlChangeDbError($this->connection->error); 
        }
    }
     

    //For queries like "SELECT, SHOW, DESC" will return an associative array
    //For queries like "INSERT, DELETE, UPDATE" will return an exception if the execution of the command is not completed
    public function Query($query)
    {
        $result = $this->connection->query($query);
        if (is_object($result)) 
        {
            if ($result->num_rows > 0) {
                return $this->FetchAssoc($result);
            }
        }
        else if (!$result) {
            throw new ezMysqlQueryError($this->connection->error); 
        }
    }


    public function InsertedId()
    {
        $inserted_id = $this->connection->insert_id;
        if (empty($inserted_id)) {
            throw new ezMysqlInsertedIdError("Not found any ID generated automatically.");    
        }
        return $inserted_id;
    }


    protected function FetchAssoc($result)
    {
        $array = array();
        $num = 0;

        while ($row = $result->fetch_assoc()) {
            $array[$num] = $row;
            $num++;
        }
        return $array;
    }
}