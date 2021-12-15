<?php
    Class Process
    {
        private $connection;
        
        // Constructor with DB
        public function __construct($db)
        {
            $this->connection = $db;
        }

        // Associating comments with users
        public function associateComments()
        {
            $this->connection->update('comments', ['user_id = FLOOR(RAND() * 10)+1']);
        }

        // Associating albums to posts
        public function associateAlbums()
        {
            $this->connection->update('albums', ['post_id = FLOOR(RAND() * 100)+1']);
        }
    }
?>