<?php 
    $env = $_SERVER['SERVER_NAME'];
    $dbHost = 'localhost';
    $dbUser = 'root';
    $dbPass = '';
    $dbName = 'teknospace';

    if ($env != 'localhost') {
        $dbHost = '163.44.242.11';
        $dbUser = 'pljrluqz_angie';
        $dbPass = 'angiedb234;;';
        $dbName = 'pljrluqz_teknospace';
    }
 
    try {
        $conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
        if (!$conn) {
            throw new Exception("Connection failed: " . mysqli_connect_error());
        }
        echo "Connected successfully";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
    
   // $conn = mysqli_connect('sql312.infinityfree.com', 'if0_36811532', 'EOAadDVdofVT', 'if0_36811532_teknoDB')or die("Couldn't connect");
    return $conn;
?>
