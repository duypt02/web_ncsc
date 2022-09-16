<?php

class Connect{
    private $hostname = "localhost";
    private $username = "root";
    private $password = "03102002";
    private $db_name = "webproject";

    private function cnt(){
        $connect = mysqli_connect($this->hostname, $this->username, $this->password, $this->db_name);
        mysqli_set_charset($connect, 'utf8');
        return $connect;
    }

    public function excute($sql){
        $connect = $this->cnt();
        mysqli_query($connect, $sql);
        mysqli_close($connect);
    }

    public function select($sql){
        $connect = $this->cnt();
        $result = mysqli_query($connect, $sql);
        mysqli_close($connect);
        return $result;
    }

}