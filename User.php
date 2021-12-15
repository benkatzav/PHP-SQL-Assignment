<?php
    class User
    {
        //DB stuff
        private $conn;
        private $table = 'users';
  
        //User Properties
        public $id;
        public $user_name;
        public $email;

  
        // Constructor with DB
        public function __construct($db, $uid)
        {
            $this->conn = $db->getDb();
            $this->id = $uid;
        }
  
        // Get user
        public function read()
        {
            //Create query
            $query = 'SELECT 
            user_name,
            email 
            FROM ' . $this->table;
  
            //Prepre statement
            $stmt = $this->conn->prepare($query);
              
            //Execute query
            $stmt->execute();
  
            return $stmt;
        }

        // Get user information by ID
        public function getInfo()
        {
             //Create query
             $query = 'SELECT user_id as "User ID", user_name as "User Name", email as "Email" FROM ' . $this->table .
             ' WHERE user_id = ' . $this->id;
             //Prepre statement
             $stmt = $this->conn->prepare($query);
            
             //Execute query
             $stmt->execute();

             $row = $stmt->fetch(PDO::FETCH_ASSOC);

             $this->id = $row['User ID'];
             $this->user_name = $row['User Name'];
             $this->email = $row['Email'];

             return ' USER ID: ' . $this->id . ', NAME: ' . $this->user_name . ', EMAIL: ' . $this->email . '<br />';
        }
        
    }
?>