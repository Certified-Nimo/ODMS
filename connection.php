<?php

class Database
{
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $db = "odms_db";

    function connect()
    {
        $conn = mysqli_connect($this->host, $this->username, $this->password, $this->db);
        return $conn;
    }

    function read($conn, $query)
    {
        $result = mysqli_query($conn, $query);

        if (!$result) {
            return false;
        } else {
            $data = false;
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
            return $data;
        }
    }

    function save($conn, $query)
    {
        $result = mysqli_query($conn, $query);

        if (!$result) {
            return false;
        } else {
            return true;
        }
    }
}

$DB = new Database();
$conn = $DB->connect(); // Establishing a connection

// Now you can use $conn in your further operations.
