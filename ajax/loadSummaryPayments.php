<?php
require('../database/connect.php');
include_once('../api/functions.php');

$sql = "SELECT SUM(`payment_amount`) as `total` FROM `client_payment_current`";

$result = mysqli_query($connect, $sql);
$sum = mysqli_fetch_assoc($result);


echo $sum['total'] ? $sum['total'] : 0.00;
