<?php

require('../database/connect.php');


$search = $_GET['term'];

$sql = "SELECT * FROM `client_info` WHERE `fname` LIKE '%$search%' OR `lname` LIKE '%$search%'"; 
$result = mysqli_query($connect, $sql);
$user = array();

if (mysqli_num_rows($result) > 0) 
{
    $i = 0;
    while($data = mysqli_fetch_assoc($result))
    {
        $user[$i] = $data['fname']." ".$data['lname'];
        $i++;
    }
}

echo json_encode($user);