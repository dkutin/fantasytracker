<?php


class Database
{
    private $server = "127.0.0.1";
    private $username = "root";
    private $password = "admin";
    private $dbname = "fantasytracker_db";

    function __construct($server = '', $username = '', $password = '')
    {
        return $this;
    }

    function put($string) {
        $conn = new mysqli($this->server, $this->username, $this->password, $this->dbname);
        if ($conn->connect_error) {
            print_r($conn->connect_error);
            return FALSE;
        }

        if ($conn->query($string) === TRUE) {
            $conn->close();
            return TRUE;
        }
        print_r($conn->error);
        $conn->close();
        return FALSE;

    }

    function get($string) {
        $return = [];
        $conn = new mysqli($this->server, $this->username, $this->password, $this->dbname);
        if ($conn->connect_error) {
            print_r($conn->connect_error);
            return FALSE;
        }
        $result = $conn->query($string);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                foreach ($row as $key => $value) {
                    $return[$key] = $value;
                }
            }
        }
        $conn->close();
        return $return;
    }

    function getMultiple($string) {
        $return = [];
        $row_num = 0;
        $conn = new mysqli($this->server, $this->username, $this->password, $this->dbname);
        if ($conn->connect_error) {
            print_r($conn->connect_error);
            return FALSE;
        }
        $result = $conn->query($string);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                foreach ($row as $key => $value) {
                    $return[$row_num][$key] = $value;
                }
                $row_num++;
            }
        }
        $conn->close();
        return $return;
    }
}
