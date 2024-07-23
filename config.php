<?php 
    $env = $_SERVER['SERVER_NAME'];
    $dbHost = 'localhost';
    $dbUser = 'root';
    $dbPass = '';
    $dbName = 'teknospace';

    if ($env != 'localhost') {
        $dbHost = 'localhost';
        $dbUser = 'pljrluqz_angie';
        $dbPass = 'angiedb234;;';
        $dbName = 'pljrluqz_teknospace';
    }
 
    try {
        $conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }

    // $conn = mysqli_connect('localhost', 'root', '', 'teknospace')or die("Couldn't connect");
   // $conn = mysqli_connect('sql312.infinityfree.com', 'if0_36811532', 'EOAadDVdofVT', 'if0_36811532_teknoDB')or die("Couldn't connect");
    return $conn;
?>
