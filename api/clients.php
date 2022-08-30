<?php

class DBClients {

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

    public function checkDuplicate ($fname, $lname) { 
        $checker = "SELECT * FROM `client_info` WHERE `fname` = '$fname' AND `lname` = '$lname'";

        $result = mysqli_query($this->connect, $checker);

        if(mysqli_num_rows($result) > 0) 
        {
            return true;
        } 
        else 
        {
            return false;
        }
    }

    public function getClientInfos($type ="active")
    {
        $data = array();
        $sql = "SELECT DISTINCT c.client_user_id, c.fname, c.lname, c.account_type, c.address, c.dob,
                        CONCAT(COALESCE(m.comaker_fname,'N/A'), ' ',COALESCE(m.comaker_lname, '')) AS `comakers`
                        FROM `client_info` AS c
                        LEFT JOIN `co_makers1` AS m
                        ON c.client_user_id = m.client_user_id
                        WHERE c.account_type = '$type'
                        GROUP BY c.client_user_id";

        $result = mysqli_query($this->connect, $sql);

        $data = mysqli_fetch_all($result, MYSQLI_ASSOC);

        return $data;
    }

    public function getClient($client_uid)
    {
        $data = array();
        $sql = "SELECT * FROM `client_info` WHERE `client_user_id` = '$client_uid'";
        $result = mysqli_query($this->connect, $sql);

        if(mysqli_num_rows($result) > 0)
        {
            $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
            return $data[0];
        }
        else
        {
            return $data;
        }
    }

    public function insertNewClient($client_info)
    {
        $fname = $client_info['fname'];
        $lname = $client_info['lname'];
        $dob = $client_info['dob'];
        $address = $client_info['address'];
        $contact_no = $client_info['contact_no'];
        $account_type = $client_info['account_type'];
        $client_uid = $client_info['client_user_id'];
        $date_joined = date('Y-m-d');
        $gender = $client_info['gender'];
        $profile_pic = $client_info['profile_pic'];

        // print_r($client_info);

        if(!($this->checkDuplicate($fname, $lname)))
        {
            $insert = "INSERT INTO 
            `client_info` (`client_id`, `client_user_id`, `fname`, `lname`, `dob`, `gender`, `address`, `account_type`,`contact_no`,`date_joined`,`profile_pic`)
            VALUES (NULL, '$client_uid', '$fname', '$lname','$dob','$gender','$address','$account_type','$contact_no','$date_joined', '$profile_pic')";
            
            if(mysqli_query($this->connect, $insert))
            {
                return assignResult("success", "Successfully added a new client.");
            } 
            else 
            {
                return assignResult("error", "Failed to add new client! Please contact admin.");
            }
        } 
        else
        {
            return assignResult("error", formatName($fname, $lname)." is already in the database!");
        }
    }
    
    public function getClientsCount($type) 
    {
        $sql = !empty($type) ? "SELECT * FROM `client_info` WHERE `account_type` = '$type'" : "SELECT * FROM `client_info`";
        return mysqli_num_rows(mysqli_query($this->connect, $sql));
    }

    public function makePayment($type, $payer_data)
    {
        $fname = ucwords($payer_data['fname']);
        $lname = ucwords($payer_data['lname']);
        $payment_amount = $payer_data['payment_amount'];
        $payment_date = $payer_data['payment_date'];
        $mode_of_payment = $payer_data['mode_of_payment'];
        $payment_type = $payer_data['payment_type'];
        $payment_month = date('M');
        $payment_year = date('Y');
        
        $client_payment_type = $type == "Current" ? "client_payment_current" : "client_payment_past";

        
        $sql1 = "SELECT `client_user_id` FROM `client_info` WHERE `fname` = '$fname' AND `lname` = '$lname'";

        $result1 = mysqli_query($this->connect, $sql1);

        if(mysqli_num_rows($result1) > 0) 
        {
            $user = mysqli_fetch_assoc($result1);
            $payment_user_id = $user['client_user_id'];
            $sql2 = "SELECT * FROM `client_payment_current` WHERE `payment_date` = '$payment_date' AND `payment_user_id` = '$payment_user_id'";

            $result2 = mysqli_query($this->connect, $sql2);

            if(mysqli_num_rows($result2) > 0){
                return assignResult("error", "You have already paid on ".$payment_date.". You can edit it to update.");
            }
            else
            {
                $sql3 = "INSERT INTO 
                `$client_payment_type` (`payment_id`,`payment_user_id`,`payment_amount`,`mode_of_payment`,`payment_date`,`payment_type`,`payment_month`,`payment_year`)
                VALUES (NULL,'$payment_user_id','$payment_amount','$mode_of_payment','$payment_date','$payment_type','$payment_month','$payment_year')";
    
                if(mysqli_query($this->connect, $sql3))
                {
                    return assignResult("success", "Client '".formatName($fname, $lname)."' have successfully set the payment for ".$payment_date);
                }
                else
                {
                    return assignResult("error", "Payment of ".formatName($fname, $lname)." has failed! Please try contacting admin.");
                }
            }
        }
        else
        {
            return assignResult("error", formatName($fname, $lname)." doesn't exist or not a full name.");
        }
    }

    public function updatePaymentDetails($new_details)
    {
        $payment_amount = $new_details['payment_amount'];
        $payment_user_id = $new_details['payment_user_id'];
        $payment_date = $new_details['payment_date'];
        $mode_of_payment = $new_details['mode_of_payment'];
        $payment_type = $new_details['payment_type'];
        $payment_id = $new_details['payment_id'];

        $arr = explode('-', $payment_date);
        $payment_month = date("M", mktime(0,0,0, $arr[1],1));

        $payment_year = $arr[0];

        $sql= "UPDATE `client_payment_current` 
                SET `payment_amount` = '$payment_amount', 
                `mode_of_payment` = '$mode_of_payment', 
                `payment_date` = '$payment_date', 
                `payment_type` = '$payment_type',
                `payment_month` = '$payment_month',
                `payment_year` = '$payment_year'
                WHERE `payment_user_id` = '$payment_user_id'
                AND `payment_id` = '$payment_id'";

        if(mysqli_query($this->connect, $sql))
        {
            return assignResult("success", "Payment successfully edited.");
        }
        else
        {
            // var_dump(mysqli_query($this->connect, $sql));
            return assignResult("error", "Payment failed to edit.");
        }
    }

    public function deletePayment($client_uid, $payment_date)
    {
        $sql = "DELETE FROM `client_payment_current` WHERE `payment_user_id` ='$client_uid' AND `payment_date` = '$payment_date'";
        if(mysqli_query($this->connect, $sql))
        {
            return assignResult("success", "Payment for $payment_date is successfully deleted.");
        }
        else
        {
            return assignResult("error", "Payment failed to delete. Try refreshing your browser or try again later.");
        }
    }

    public function getPaymentInfoByDate($client_uid, $payment_date)
    {
        $sql = "SELECT * FROM `client_payment_current` WHERE `payment_user_id` = '$client_uid' AND `payment_date` = '$payment_date'";
        $result = mysqli_query($this->connect, $sql);
        if(mysqli_num_rows($result) > 0)
        {
            $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
        }

        return $data[0];
    }

    public function deleteClient($client_id)
    {
        $check_payments = "SELECT * FROM `client_payment_current` WHERE `payment_user_id` = '$client_id'";
        $result = mysqli_query($this->connect, $check_payments);
        
        if(mysqli_num_rows($result) > 0)
        {
            $sql = "DELETE FROM `client_info` WHERE `client_user_id` = '$client_id'; ";
            $sql .= "DELETE FROM `client_payment_current` WHERE `payment_user_id` = '$client_id'";

            if(mysqli_multi_query($this->connect, $sql))
            {
                return assignResult("success", "Client successfully deleted.");
            }
            else
            {
                return assignResult("error", "Client is failed to delete.");
            }
        }
        else
        {
            $sql = "DELETE FROM `client_info` WHERE `client_user_id` = '$client_id'";

            if(mysqli_query($this->connect, $sql))
            {
                return assignResult("success", "Client successfully deleted.");
            }
            else
            {
                return assignResult("error", "Client is failed to delete.");
            }
        }
    }

    public function replaceClientInfo($data_to_replace)
    {
        $fname = $data_to_replace['fname'];
        $lname = $data_to_replace['lname'];
        $dob = $data_to_replace['dob'];
        $contact_no = $data_to_replace['contact_no'];
        $address = $data_to_replace['address'];
        $account_type = $data_to_replace['account_type'];
        $CLIENT_USER_ID = $data_to_replace['client_user_id'];
        $profile_picture = $data_to_replace['new_profile'];

        $sql = "UPDATE `client_info` 
            SET `fname` = '$fname', 
            `lname` = '$lname', 
            `dob` = '$dob', 
            `contact_no` = '$contact_no', 
            `address` = '$address',
            `account_type` = '$account_type',
            `profile_pic` = '$profile_picture'
            WHERE `client_user_id` = '$CLIENT_USER_ID'";

        if(mysqli_query($this->connect, $sql))
        {
            return assignResult("success", "Client successfully edited.");
        }
        else
        {
            return assignResult("error", "Failed to edit ".formatName($fname, $lname)."! Try to contact admin.");
        }
    }
    public function setClientAccountType($client_uid, $account_type)
    {
        $data = $this->getClient($client_uid);
        $client = formatName($data['fname'], $data['lname']);
        
        $sql = "UPDATE `client_info` SET `account_type` = '$account_type' WHERE `client_user_id` = '$client_uid'";

        if(mysqli_query($this->connect, $sql))
        {
            return assignResult("success", "You have successfully set ".$client."'s account to ".ucwords($account_type).".");
        }
        else
        {
            return assignResult("error", "Failed to edit ".$client."! Try to contact admin.");
        }
    }

    public function addCoMaker($comaker_details)
    {
        $client_userid = $comaker_details['client_user_id'];

        $checkSQL = "SELECT COUNT(*) AS `total` FROM `co_makers_1` WHERE `comaker_user_id` = '$client_userid";
        $checker = mysqli_query($this->connect, $checkSQL);
        
        $comakerx = mysqli_fetch_assoc($checker);  
        if($comakerx['total'] >= 2)
        {
            return assignResult("error", "Limit reached for co-makers."); 
        }
        else
        {
            $comaker_uid = $comaker_details['comaker_uid'];
            $client_user_id = $comaker_details['client_user_id'];
            $comaker_fname = $comaker_details['comaker_fname'];
            $comaker_lname = $comaker_details['comaker_lname'];
            $comaker_profile_pic = $comaker_details['comaker_profile_pic'];
            $comaker_status = $comaker_details['comaker_status'];
            $comaker_address = $comaker_details['comaker_address'];
            $comaker_contact_no = $comaker_details['comaker_contact_no'];
            $comaker_dob = $comaker_details['comaker_dob'];
            $comaker_gender = $comaker_details['comaker_gender'];
    
            $sql = "INSERT INTO `co_makers1` (`comaker_id`,`comaker_user_id`,`client_user_id`,`comaker_fname`,`comaker_lname`,`comaker_profile_pic`,`comaker_status`,`comaker_address`,`comaker_contact_no`,`comaker_dob`,`comaker_gender`) 
            VALUES(null, '$comaker_uid','$client_user_id','$comaker_fname','$comaker_lname','$comaker_profile_pic','$comaker_status','$comaker_address','$comaker_contact_no','$comaker_dob','$comaker_gender')";
    
            if(mysqli_query($this->connect, $sql))
            {
                return assignResult("success", "You have successfully added a co-maker.");
            }
            else
            {
                return assignResult("error", "Failed to add a co-maker!");
            }
        }
    }

    public function deleteCoMaker($comaker_id)
    {
        $sql= "DELETE FROM `co_makers1` WHERE `comaker_user_id` = '$comaker_id'";
        if(mysqli_query($this->connect, $sql))
        {
            return assignResult("success", "You have successfully deleted a co-maker.");
        } 
        else
        {
            return assignResult("error", "You have failed to delete a co-maker.");
        }
    }

    public function getCoMakers($client_user_id)
    {
        $data = array();

        $sql = "SELECT * FROM `co_makers1` WHERE `client_user_id` = '$client_user_id'";
        $result = mysqli_query($this->connect, $sql);

        if(mysqli_num_rows($result) > 0)
        {
            $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
        }

        return $data;
    }
}