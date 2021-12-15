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
            $this->connection;
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

        public function getDb()
        {
            if ($this->connection instanceof PDO) 
            {
                return $this->connection;
            }

        }

        public function isEmpty()
        {
            $sql = "select count(*) from information_schema.tables where table_type = 'BASE TABLE' and table_schema = 'sql_inmanage'";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if($row['count(*)'] != 0)
            {
                $sql = 'SELECT CASE WHEN EXISTS(SELECT 1 FROM sql_inmanage.users) THEN 0 ELSE 1 END AS isEmpty;';
                $stmt = $this->connection->prepare($sql);
                $stmt->execute();
    
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
                $i = $row['isEmpty'];
                return $i;
            }
            return 2;
        }

        
        public function createTables()
        {
            $sql = 'USE sql_inmanage;

            CREATE TABLE users (
                user_id INT NOT NULL auto_increment,
                user_name VARCHAR(255),
                email VARCHAR(255),
                PRIMARY KEY (user_id)
            );
            
            CREATE TABLE posts (
                post_id INT NOT NULL auto_increment,
                user_id INT,
                title VARCHAR(255),
                content VARCHAR(255),
                PRIMARY KEY (post_id),
                FOREIGN KEY (user_id) REFERENCES users(user_id)
            );
            
            CREATE TABLE comments (
                comment_id INT NOT NULL auto_increment,
                post_id INT,
                user_id INT,
                content LONGTEXT,
                PRIMARY KEY (comment_id),
                FOREIGN KEY (post_id) REFERENCES posts(post_id),
                FOREIGN KEY (user_id) REFERENCES users(user_id)
            );
            
            CREATE TABLE albums (
                album_id INT NOT NULL auto_increment,
                post_id INT,
                PRIMARY KEY (album_id),
                FOREIGN KEY (post_id) REFERENCES posts(post_id)
            );
            
            CREATE TABLE photos (
                photo_id INT NOT NULL auto_increment,
                album_id INT,
                link_photo VARCHAR(255),
                link_small_photo VARCHAR(255),
                PRIMARY KEY (photo_id),
                FOREIGN KEY (album_id) REFERENCES albums(album_id)
            );';
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
        }


    }

?>