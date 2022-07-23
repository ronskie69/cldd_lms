<?php

class AccountsDB {

    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $dbName = "cldd";
    private $conn = NULL;

    public function __construct()
    {
        $this->conn = mysqli_connect($this->host, $this->username, $this->password, $this->dbName)
        or die("Failed to connect!");
    }

    public function checkDuplicates($username)
    {
        $query = "SELECT * FROM `admins` WHERE `username` = '$username'";
        $result = mysqli_query($this->conn, $query);

        if(mysqli_num_rows($result) > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function getAccountData($admin_id)
    {
        $sql = "SELECT * FROM `admins` WHERE `user_id` = '$admin_id'";
        $result = mysqli_query($this->conn, $sql);
        if(mysqli_num_rows($result) > 0)
        {
            $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
            return $data[0];
        }
    }

    public function loginUser($username, $password)
    {
        $password = md5($password);
        $sql = "SELECT * FROM `admins` WHERE `username` = '$username' AND `password` = '$password'";

        $result = mysqli_query($this->conn, $sql);

        if(mysqli_num_rows($result) > 0)
        {
            $user = mysqli_fetch_all($result, MYSQLI_ASSOC);
            return assignResult("success", "Login Successfully!", $user[0]);
        }
        else 
        {
            return assignResult("error", "Username or Password is incorrect!");
        }
    }

    public function registerUser($username, $password, $nickname)
    {
        $password = md5($password);

        if(!($this->checkDuplicates($username)))
        {
            $sql = "INSERT INTO 
            `admins` (`user_id`,`username`,`password`,`nickname`, `profile_pic`) 
            VALUES (null, '$username','$password','$nickname', NULL)";

            if(mysqli_query($this->conn, $sql))
            {
                return assignResult("success", "You have created a new admin!");
            }
            else
            {
                return assignResult("error", "Failed to add new admin!");
            }
        }
        else 
        {
            return assignResult("error", "Username is already taken!");
        }
    }

    public function updatePassword($user_id, $new_password)
    {
        $new_password = md5(trimLow($new_password));

        $sql1 = "SELECT * FROM `admins` WHERE `user_id` = '$user_id' AND `password` = '$new_password'";
        
        $old = mysqli_query($this->conn, $sql1);
        
        if(!(mysqli_num_rows($old) > 0))
        {
            $sql2 = "UPDATE `admins` SET `password` = '$new_password' WHERE `user_id` = '$user_id'";
            
            if(mysqli_query($this->conn, $sql2))
            {
                return assignResult("success", "Password is successfully updated!");
            }
            else
            {
                return assignResult("error", "Failed to update password! Please contact admin.");
            }
        }
        else
        {
            return assignResult("error", "New password should not be your old password!");
        }
    }

    public function updateNickname($user_id, $new_nickname)
    {
        $sql = "UPDATE `admins` SET `nickname` = '$new_nickname' WHERE `user_id` = '$user_id'";
        if(mysqli_query($this->conn, $sql))
        {
            return assignResult("success", "Nickname successfully updated to ".$new_nickname.".");
        }
        else
        {
            return assignResult("error", "Failed to update nickname! Please contact admin.");
        }
    }

    public function updateProfilePic($user_id, $profile_pic)
    {
        $sql = "UPDATE `admins` SET `profile_pic` = '$profile_pic' WHERE `user_id` = '$user_id'";
        if(mysqli_query($this->conn, $sql))
        {
            return assignResult("success", "Profile picture successfully updated.");
        }
        else
        {
            return assignResult("error", "Failed to update profile picture! Please contact admin.");
        }
    }

    public function postEvent($event)
    {
        $event_title = $event['event_title'];
        $event_details = $event['event_details'];
        $event_date = $event['event_date'];
        $event_author = $event['event_author'];
        $event_author_uid = $event['event_author_uid'];
        $event_location = $event['event_location'];
        $event_receivers= $event['event_receivers'];
        $posted_date = date('Y-m-d');
        
        $sql = "INSERT INTO 
        `events` (`event_id`,`event_title`,`event_details`,`event_location`,`event_date`,`date_posted`,`event_author`,`event_author_uid`,`event_receivers`) 
        VALUES (null, '$event_title','$event_details','$event_location','$event_date','$posted_date','$event_author','$event_author_uid', '$event_receivers')";

        if(mysqli_query($this->conn, $sql))
        {
            return assignResult("success", "New event / announcement is created!");
        }
        else
        {
            return assignResult("error", "Failed to create event!");
        }
    }

    public function loadEvents($query="")
    {
        $sql = empty($query) ? "SELECT * FROM `events`" : "SELECT * FROM `events` WHERE `event_author_uid` = '$query'";
        $result = mysqli_query($this->conn, $sql);

        if(mysqli_num_rows($result) > 0)
        {
            $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
            return $data;
        }
        else
        {
            return array();
        }
    }

    public function getCurrentEventsCount()
    {
        $current_datae = date("Y-m-d");
        $counter = "SELECT * FROM `events` WHERE `date_posted` = '$current_datae'";
        return mysqli_num_rows(mysqli_query($this->conn, $counter));
    }
}