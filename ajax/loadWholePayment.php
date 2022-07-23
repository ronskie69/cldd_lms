<?php

require('../database/connect.php');
include_once('../api/functions.php');

$left_join = isset($_GET['client_payment_type']) && $_GET['client_payment_type'] === 'current' ?  "client_payment_current" : 'client_payment_past';


$i = 0;
$payments_info = array();

$sql = "SELECT c.client_id AS `client_id`, c.client_user_id AS `client_uid`, c.fname AS `fname`, c.lname AS `lname`, p.payment_id AS `payment_id`, p.payment_user_id AS `payment_user_id`, p.payment_amount AS `payment_amount`, p.payment_date AS `payment_date`, p.payment_type AS `payment_type`, p.mode_of_payment AS `mode_of_payment`, p.payment_year AS `payment_year`, p.payment_month AS `payment_month`
        FROM `client_info` AS c
        RIGHT JOIN `client_payment_current` AS p
        ON c.client_user_id = p.payment_user_id";

$result = mysqli_query($connect, $sql);

if(mysqli_num_rows($result) > 0) {
   while($data = mysqli_fetch_assoc($result)){
    $payments_info[$i] = array(
        "client_id" => $data['client_id'],
        "client_uid" => $data['client_uid'],
        "client_name" => formatName($data['fname'], $data['lname']),
        "payment_id" => $data['payment_id'],
        "payment_user_id" => $data['payment_user_id'],
        "payment_amount" => $data['payment_amount'],
        "payment_date" => $data['payment_date'],
        "payment_type" => $data['payment_type'],
        "mode_of_payment" => $data['mode_of_payment'],
        "payment_year" => $data['payment_year'],
        "payment_month" => $data['payment_month']
    );
    $i++;
   }


   echo json_encode($payments_info);
}


