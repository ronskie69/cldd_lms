<?php

require('../database/connect.php');

$event_id = $_POST['event_id'];
$event_author_uid = $_POST['event_author_uid'];

$sql = "SELECT `event_title`, `event_details` FROM `events` WHERE `event_id` = '$event_id' AND `event_author_uid` = '$event_author_uid'";

$result = mysqli_query($connect, $sql);
$data = mysqli_fetch_assoc($result);

echo json_encode($data);

