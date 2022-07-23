<?php

require('../database/connect.php');

$account_type = $_POST['account_type'];
$client_user_id = $_POST['client_uid'];

$sql = "UPDATE `client_info` SET `account_type` = '$account_type' 
        WHERE `client_user_id` = '$client_user_id'";

if(mysqli_query($connect, $sql))
{
    echo "success";
}
else
{
    echo "failed";
}