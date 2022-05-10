
<?php
    require('.\config\Database.php');
    require('.\config\bootstrap.html');

    include_once '.\WebServiceInteraction.php';
    include_once '.\PostManager.php';
    include_once '.\Process.php';
    include_once '.\Post.php';
    include_once '.\User.php';

    $host = 'localhost';
    $db_name = 'sql_db';
    $username = 'root';
    $password = 'h@123456';

    // Instantiate DB connect
    $database = new Database($host,$db_name,$username,$password);
    $database->connect();

    $dbCheck = $database->isEmpty();
    
    if($dbCheck > 0)
    {
        if($dbCheck == 2)
        {
           $database->createTables();
        }
        $wbs = new WebServiceInteraction($database);
        $wbs->retrieveData();
    
        $process = new Process($database);
        $process->associateAlbums();
        $process->associateComments();
    }

    $pManager = new PostManager($database);

    print_r($pManager->retrieveFromTable());

?>
