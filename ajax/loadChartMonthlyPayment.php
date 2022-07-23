<?php

require('../database/connect.php');
include_once('../api/functions.php');

$left_join = isset($_GET['client_payment_type']) && $_GET['client_payment_type'] === 'current' ?  "client_payment_current" : 'client_payment_past';

$sql = "SELECT `payment_month`, SUM(`payment_amount`) AS `total_amount` FROM `client_payment_current`";


$i = 0;
$payments_info = array();
$month_f = array("Jan", "Feb", "Mar", "Apr", "May","Jun","Jul","Aug","Sep","Oct","Nov", "Dec");


$result = mysqli_query($connect, $sql);

if(mysqli_num_rows($result) > 0) {
   while($data = mysqli_fetch_assoc($result)){
    $payments_info[$i] = array(
        "total" => $data['total_amount'],
        "payment_month" => $data['payment_month'],
        "dates" => array(
            $data['payment_month'],
            date(in_array($data['payment_month'], $month_f) ? "M" :"M", strtotime('+2 month')),
            date(in_array($data['payment_month'], $month_f) ?"M" :"M", strtotime('+3 month')),
            date(in_array($data['payment_month'], $month_f) ?"M" :"M", strtotime('+4 month')),
            date(in_array($data['payment_month'], $month_f) ?"M" :"M", strtotime('+5 month')),
            date(in_array($data['payment_month'], $month_f) ?"M" :"M", strtotime('+6 month')),
        )
       
    );
    $i++;
   }


   echo json_encode($payments_info);
}