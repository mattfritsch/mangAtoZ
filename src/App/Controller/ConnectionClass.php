<?php

namespace App\Controller;

class ConnectionClass extends mysqli
{
    private $host='localhost',$username='root',$password='',$dbname='manga';
    public $con;

    function __construct(){
        $this->con=$this->connect($this->host, $this->username, $this->password, $this->dbname);
    }

}