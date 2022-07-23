<?php

require('../database/connect.php');
include_once('../api/functions.php');

$session_user_id = $_POST['session_user_id'];
$old_password = trimLow($_POST['old_password']);

$old_password = md5($old_password);


$sql = "SELECT * FROM `admins` WHERE `password` = '$old_password' AND `user_id` = '$session_user_id'";
$checked = mysqli_query($connect, $sql);

if(mysqli_num_rows($checked) > 0)
{
    echo "success";
}
else
{
    echo "error";
}
