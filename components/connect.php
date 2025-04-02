<?php
$db_name = 'e-commerce';
$server = 'localhost';
$user_name = 'root';
$password = '';

// Create a connection
$conn = new mysqli($server, $user_name, $password, $db_name);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

function unique_id(){
    $chars='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charLength=strlen($chars);
    $randomString='I';

    for($i=0;$i<20;$i++){
        $randomString.=$chars[mt_rand(0,$charLength-1)];
    }
    return $randomString;
}
?>