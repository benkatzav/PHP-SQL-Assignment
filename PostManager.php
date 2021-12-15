<?php
    Class PostManager
    {
        //DB stuff
        private $db;
        public $users;
        public $posts;

        public function __construct($db)
        {
            $this->db = $db;
            $this->posts = array();
            $this->users = array();
        }

        public function retrieveFromTable()
        {
            $postsRes = $this->getPosts();
            while($row = $postsRes->fetch(PDO::FETCH_ASSOC))
            {
                if($this->isExist($row['User ID']) == false)
                {
                    array_push($this->users, new User($this->db,$row['User ID']));
                }
                $newPost = new Post($this->db, $row['Post ID'], '<br />Posted by' . $this->getUser($row['User ID']),$row['Title'],$row['Content']);
                array_push($this->posts, $newPost);
            }

            return $this->posts;
        }

        public function getPosts()
        {
            //Create query
            $query = 'SELECT 
            post_id as "Post ID",
            user_id as "User ID",
            title as "Title",
            content as "Content"
            FROM posts';

            //Prepre statement
            $stmt = $this->db->getDb()->prepare($query);
            
            //Execute query
            $stmt->execute();

            return $stmt;
        }
        

        public function isExist($uid)
        {
            foreach($this->users as $u)
            {
                if($u->id == $uid)
                    return true;
            }
            return false;
        }

        public function getUser($uid)
        {
            foreach($this->users as $u)
            {
                if($u->id == $uid)
                    return $u->getInfo();
            }
            return null;
        }
    }

?>