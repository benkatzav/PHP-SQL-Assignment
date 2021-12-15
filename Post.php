<?php
    class Post
    {
        //DB stuff
        private $connection;
        private $table = 'posts';

        //Post Properties
        public $id;
        public $user_id;
        public $title;
        public $content;
        public $comments;
        public $photos;

        // Constructor with DB
        public function __construct($db,$pid,$uid,$title,$content)
        {
            $this->connection = $db->getDb();
            $this->id = $pid;
            $this->user_id = $uid;
            $this->title = '<br />' . $title . '<br />';
            $this->content = '<br />' . $content . '<br />';
            $this->comments = $this->getComments();
            $this->photos = $this->getPhotos();
        }

        // Get comments of specific post
        public function getComments()
        {
            $commentsArr = array();
            //Create query
            $query = 'SELECT 
            comment_id,
            user_id,
            content FROM comments WHERE post_id = ' . $this->id;

            //Prepre statement
            $stmt = $this->connection->prepare($query);
            
            //Execute query
            $stmt->execute();

            while($row = $stmt->fetch(PDO::FETCH_ASSOC))
            {
                array_push($commentsArr, '<br />COMMENT ID: ' . $row['comment_id'] . ', Written by USER ID: ' . $row['user_id'] . '<br />CONTENT: ' . $row['content'] . '<br />');
            }

            return $commentsArr;
        }

        public function getPhotos()
        {
            $photos = array();
            //Create query
            $query = 'SELECT photo_id, link_photo, link_small_photo FROM photos p
            INNER JOIN albums a
            ON a.post_id = ' .$this->id . ' AND p.album_id = a.album_id';

            //Prepre statement
            $stmt = $this->connection->prepare($query);
            
            //Execute query
            $stmt->execute();

            while($row = $stmt->fetch(PDO::FETCH_ASSOC))
            {
                array_push($photos, '<br />PHOTO ID: ' . $row['photo_id'] . '<br /> PIC:<br /><img class=image-responsive src= ' . $row['link_photo'] . 'alt=""><br />SMALL PIC: <br /><img class=image-responsive src= ' . $row['link_small_photo'] . 'alt=""><br />');
            }

            return $photos;
        }

    }
?>