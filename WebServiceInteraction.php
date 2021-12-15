<?php
    Class WebServiceInteraction
    {
        private $connection;

        // Constructor with DB
        public function __construct($db)
        {
            $this->connection = $db;
        }

        public function get_content($url)
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, $url);
            $data = curl_exec($ch);
            curl_close($ch);
            return $data;
        }
        
        public function retrieveData()
        {
            $counter = 1;
            $data = $this->get_content('https://jsonplaceholder.typicode.com/users');
            $array = json_decode($data, true);

            foreach($array as $r)
            {
                $this->connection->insert('users', [$r["id"], $r["username"], $r["email"]]);
            }

                        
            $data = $this->get_content('https://jsonplaceholder.typicode.com/posts');
            $array = json_decode($data, true);
        
            foreach($array as $r)
            {
                $this->connection->insert('posts', [$r["id"], $r["userId"], $r["title"], $r['body']]);
            }

                        
            $data = $this->get_content('https://jsonplaceholder.typicode.com/comments');
            $array = json_decode($data, true);
            shuffle($array);
            foreach($array as $r)
            {
                $this->connection->insert('comments', [$r["id"], $r["postId"], $r["body"]],'comment_id, post_id, content');
                if($counter >= 100)
                {
                    break;
                }
                $counter += 1;
            }

                        
            $data = $this->get_content('https://jsonplaceholder.typicode.com/albums');
            $array = json_decode($data, true);
        
            foreach($array as $r)
            {
                $this->connection->insert('albums', [$r["id"]],'album_id');
            }
            
            $counter = 1;

            $data = $this->get_content('https://jsonplaceholder.typicode.com/photos');
            $array = json_decode($data, true);
            shuffle($array);
            foreach($array as $r)
            {
                $this->connection->insert('photos', [$r["id"], $r["albumId"], $r["url"], $r["thumbnailUrl"]]);
                if($counter >= 100)
                {
                    break;
                }
                $counter += 1;
            }

        }

    }
?>