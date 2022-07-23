<?php

function formatName ($str1, $str2) 
{
    return $str1." ".$str2;
}

function trimLow($string)
{
    return trim(strtolower($string));
}

function trimCaps($string)
{
    return trim(ucwords($string));
}

function assignResult($status, $message, $data = array())
{
    return array(
        "status" => $status,
        "message" => $message,
        "data" => $data
    );
}

function randomUSERID($min, $max)
{
    return "USER".random_int($min, $max);
}

function splitString($string)
{
    return explode(" ",$string);
}

function imageUploader($file_name, $file_temp_name,$image_owner)
{
    $folder = '../images/uploads/'.$image_owner.'-'.$file_name;

   try {

        if(move_uploaded_file($file_temp_name, $folder))
        {
            return array(
                "isUploaded" => true,
                "result" => $file_name,
                "owner_id" => $image_owner
            );
        }
        else
        {
            return array(
                "isUploaded" => true,
                "result" => $file_name
            );
        }
        
   }
   catch(Exception $e)
   {
        return array(
            "isUploaded" => true,
            "result" => $file_name
        );
   }
}

function isNotValidFile($file_name)
{
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    if($file_ext !=='png' && $file_ext !== 'jpeg' && $file_ext !== 'jpg')
    {
        return true;
    }
    else
    {
        return false;
    }
}