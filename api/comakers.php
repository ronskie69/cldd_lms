<?php

class DBComakers {

    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $dbName = "cldd";
    private $connect  = NULL;

    public function __construct()
    {
        $this->connect = mysqli_connect($this->host, $this->username, $this->password, $this->dbName)
        or die("Failed to connect!");
    }

    public function getAllComakers()
    {
        $sql1 = "SELECT * FROM `co_makers1`";
        $i = 0;
        $comaker_data = array();

        $result = mysqli_query($this->connect, $sql1);

        while($data = mysqli_fetch_assoc($result))
        {
            $comaker_by = $data['client_user_id'];

            $sql2 = "SELECT * FROM `client_info` WHERE `client_user_id` = '$comaker_by'";
            $result2 = mysqli_query($this->connect, $sql2);

            while($data2 = mysqli_fetch_assoc($result2))
            {
                $comaker_data[$i] = array(
                    "comaker_user_id" => $data['client_user_id'],
                    "comaker_fname" => $data['comaker_fname'],
                    "comaker_lname" => $data['comaker_lname'],
                    "comaker_gender" => $data['comaker_gender'],
                    "comaker_address" => $data['comaker_address'],
                    "comaker_dob" => $data['comaker_dob'],
                    "comaker_by" => $data2['fname']." ".$data2['lname'],
                    "comaker_dob" => $data['comaker_dob'],
                    "comaker_status" => $data['comaker_status'],
                );
            }
        }

        return $comaker_data;
    }

}