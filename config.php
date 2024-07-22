<?php 
    $env = $_SERVER['SERVER_NAME'];
    $dbHost = 'localhost';
    $dbUser = 'root';
    $dbPass = '';
    $dbName = 'teknospace';

    if ($env != 'localhost') {
        $dbHost = 'localhost';
        $dbUser = 'angie';
        $dbPass = 'angiedb234;;';
        $dbName = 'pljrluqz_teknospace';
    }
 
    $conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName) or die("Couldn't connect");
   // $conn = mysqli_connect('sql312.infinityfree.com', 'if0_36811532', 'EOAadDVdofVT', 'if0_36811532_teknoDB')or die("Couldn't connect");
    return $conn;
?>