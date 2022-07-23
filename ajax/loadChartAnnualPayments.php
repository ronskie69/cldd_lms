<?php

require('../database/connect.php');
include_once('../api/functions.php');

$left_join = isset($_GET['client_payment_type']) && $_GET['client_payment_type'] === 'current' ?  "client_payment_current" : 'client_payment_past';

$sql = "SELECT `payment_year`, SUM(`payment_amount`) AS `total_amount` FROM `client_payment_current`";


$i = 0;
$payments_info = array();

$result = mysqli_query($connect, $sql);

if(mysqli_num_rows($result) > 0) {
   while($data = mysqli_fetch_assoc($result)){

    
    $payments_info[$i] = array(
        "total" => $data['total_amount'],
        "payment_year" => $data['payment_year'],
        "years" => array(
            $data['payment_year'],
            $data['payment_year'],
            date('Y', strtotime("+1 year")),
            date('Y', strtotime("+2 year")),
            date('Y', strtotime("+3 year")),
            date('Y', strtotime("+4 year")),
            date('Y', strtotime("+5 year")),
        )
       
    );
    $i++;
   }


   echo json_encode($payments_info);
}