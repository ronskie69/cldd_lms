<?php

require('../database/connect.php');
include_once('../api/functions.php');

$i = 0;
$clients = array();
$clientsCopy = array();

$sql = "SELECT `address`, `fname`, `lname` FROM `client_info`";

$result = mysqli_query($connect, $sql);

if(mysqli_num_rows($result) > 0) {
   while($data = mysqli_fetch_assoc($result)){
    $clients[$i] = array(
        "address" => $data['address'],
        "clients" => array(formatName($data['fname'], $data['lname'])),
    );
    $i++;
   }

   echo json_encode($clients);
}


