<?php 

function filter($data){
    $data = htmlspecialchars($data);
    $data =trim($data);
    $data= stripslashes($data);
    return $data ;
}
$host ="localhost";
$user="SAMIR";
$pass = "samir123";
$dbname="login";
$tbname="myadmin";
$sql = "mysql:host=$host;dbname=$dbname";
