<?php
class Database
{
    //DB Params
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $connection;
    private $result = array();

    public function __construct($host,$db_name,$username,$password)
    {
        $this->host = $host;
        $this->db_name = $db_name;
        $this->username = $username;
        $this->password = $password;
    }

    //DB Connect
    public function connect()
    {
        $this->connection = null;
        try
        {
            $this->connection = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, 
            $this->username, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e)
        {
            echo 'Connection Error: ' . $e->getMessage();
        }
        return $this->connection;
    }

    public function select($table, $rows = '*', $where = null, $order = null)
    {
        //Create query
        $query = 'SELECT ' . $rows . ' FROM ' . $table;
        if($where != null)
        {
            $query .= ' WHERE ' . $where;
        }
        if($order != null)
        {
            $query .= ' ORDER BY ' . $order;
        }

        //Prepre statement
        $stmt = $this->connection->prepare($query);

        // Execute query
        $stmt->execute();

        // Inserting every row into the result array
        while($row = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            array_push($this->result, $row);
        }
    }

    public function insert($table, $values, $cols = null)
    {
        //Creating and executing query
        $query = 'INSERT INTO ' . $table;
        if($cols != null)
        {
            $query .= ' (' . $cols . ')';
        }
        $query .= ' VALUES ("' . $values[0];
        for($i = 1; $i < sizeof($values); $i++)
        {
            $query .= '", "' . $values[$i];
        }
        $query .= '")';

        $stmt = $this->connection->prepare($query);
        $stmt->execute();
    }


    public function delete($table, $where = null)
    {
        //Creating and executing query
        if($where == null)
        {
            $query = 'DELETE ' . $table;
        } 
        else{
            $query = 'DELETE FROM ' . $table . ' WHERE ' . $where;
        }

        $stmt = $this->connection->prepare($query);
        $stmt->execute();
    }

    public function update($table, $cols, $where = null)
    {
        //Creating and executing query
        $query = 'UPDATE ' . $table;
        $query .= ' SET ' . $cols[0];
        for($i = 1; $i < sizeof($cols); $i++)
        {
            $query .= ', ' . $cols[$i];
        }
        if($where != null)
        {
            $query .= ' WHERE ' . $where;
        }
        else {
            $query .= ';';
        }
        
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
    }

    public function getResult()
    {
       return $this->result;
    }

}

?>