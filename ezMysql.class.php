<?php
class ezMysqlConnectionError extends Exception{};
class ezMysqlDisconnectError extends Exception{};
class ezMysqlChangeDbError extends Exception{};
class ezMysqlQueryError extends Exception{};
class ezMysqlConstructError extends Exception{};
class ezMysqlInsertedIdError extends Exception{};

class ezMysql {

    protected $resource;

    function __construct($server, $user, $pass = '', $db = '')
    {
        if (empty($server) or empty($user)) {
            throw new ezMysqlConstructError("Data connection is required");
        }

        $this->resource = new mysqli($server, $user, $pass, $db);

        if($this->resource->connect_errno){
            throw new ezMysqlConnectionError("Connection error (". $this->resource->connect_errno .") : " . $this->resource->connect_error);
        }
    }

    public function Disconnect()
    {
        if(!$this->resource->close()){
            throw new ezMysqlDisconnectError($this->resource->error); 
        }
    }
    
    public function ChangeDb($db)
    {
        if (!$this->resource->select_db($db)) {
            throw new ezMysqlChangeDbError($this->resource->error); 
        }
    }
     
    //For queries like "SELECT, SHOW, DESC" will return an associative array
    //For queries like "INSERT, DELETE, UPDATE" will return an exception if the execution of the command is not completed
    public function Query($query)
    {
        $result = $this->resource->query($query);
        if (is_object($result)) 
        {
            if ($result->num_rows > 0) {
                return $this->FetchAssoc($result);
            }
        }
        else if (!$result) {
            throw new ezMysqlQueryError($this->resource->error); 
        }
    }

    public function InsertedId()
    {
        $inserted_id = $this->resource->insert_id;
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