<?php 
    require('inc/esentials.php');

    session_start();
    session_destroy();
    redirect('index.php');

?>