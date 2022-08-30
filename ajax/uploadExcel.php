<?php

include_once('../database/connect.php');
include('../api/functions.php');

require('../libraries/PHPExcel/Classes/PHPExcel.php');
require_once('../libraries/PHPExcel/Classes/PHPExcel/IOFactory.php');


$excel_file = $_FILES['file']['tmp_name'];
$date_joined = date('Y-m-d');
$ex = PHPExcel_IOFactory::load($excel_file);

foreach($ex->getWorksheetIterator() as $worksheet) 
{
    $highestRow = $worksheet->getHighestRow();
    for($row = 3; $row <=$highestRow; $row++){
        $fname = trimCaps(trimLow($worksheet->getCellByColumnAndRow(0, $row)->getValue()));
        $lname = trimCaps(trimLow($worksheet->getCellByColumnAndRow(1, $row)->getValue()));
        $dob = trimCaps(trimLow($worksheet->getCellByColumnAndRow(2, $row)->getValue()));
        $gender = trimCaps(trimLow($worksheet->getCellByColumnAndRow(3, $row)->getValue()));
        $address = trimCaps(trimLow($worksheet->getCellByColumnAndRow(4, $row)->getValue()));
        $contact = trimLow($worksheet->getCellByColumnAndRow(5, $row)->getValue());
        $status = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
    
        // $newdob = $date[2].'-'.$date[0].'-'.$date[1];

        $randomID = randomUSERID(0, 3000);

        $sql = "INSERT INTO 
            `client_info` (`client_id`, `client_user_id`, `fname`, `lname`, `dob`, `gender`, `address`, `account_type`,`contact_no`,`date_joined`,`profile_pic`)
            VALUES (NULL, '$randomID', '$fname', '$lname','','$gender','$address','$status','$contact','$date_joined', '')";
        if($fname != "" && $lname !== "")
        {
            if(mysqli_query($connect, $sql))
            {
                echo $dob;
            }
            else
            {
                echo "no".$lname;
            }    
        }

    }
}
