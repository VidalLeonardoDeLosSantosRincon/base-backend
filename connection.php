<?php
    class Connection{

        private $host;
        private $dbname;
        private $user;
        private $password;

        public function __construct($host,$dbname,$user,$password){
            $this ->host = $host;
            $this ->dbname = $dbname;
            $this ->user = $user;
            $this->password = $password;
        }

        public function toConnect(){
            try{

            $con = new PDO("mysql:host=".$this->host.";dbname=".$this->dbname, $this->user, $this->password);
            return $con;

            }catch(PDOException $e){
                echo "Error: ".$e->getMessage();
            }
        }   
    }
    $conexion = new Connection("localhost","base","root","");
?>