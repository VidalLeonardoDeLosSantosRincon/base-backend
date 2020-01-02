<?php
    header("Access-Control-Allow-Origin: *");
    require_once("connection.php");
    class User{

        private $email;
        private $password;
        private $connection;
        private $errores = [];

        public function __construct($email,$password,$connection){
            $this->email = $email;
            $this->password = $password;
            $this->connection = $connection;
        }

        /*//////////////////method to add a user///////////////////////*/
        public function addUser(){
            $con = $this->connection;
            $email = $this->email;
            $password = $this->password;

            if(empty(trim($email)) && !empty(trim($password))){
                
                array_push($this->errores,"empty email");
            
            }else if(!empty(trim($email)) && empty(trim($password))){
                
                array_push($this->errores,"empty password");

            }else if(empty(trim($email)) && empty(trim($password))){
                
                array_push($this->errores,"empty email","empty password");

            }else if(!empty(trim($email)) && !empty(trim($password))){
                $email = trim($email);
                $email = stripslashes($email);
                $email = htmlspecialchars($email);
                if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
                    array_push($this->errores,"invalid emial");
                }
                $password = trim($password);
                $password = stripslashes($password);
                $password = htmlspecialchars($password);
                $password = filter_var($password,FILTER_SANITIZE_STRING);
                if(strlen($password)>8 || strlen($password)<6 ){
                    array_push($this->errores,"invalid password");
                }
                
                //comprobando que el usuario exista
                $this->userExist($email);

                if(count($this->errores)==0){
                    $statement = $con->prepare("insert into user(email,pass) values(:email,:password)");
                    $userAdded = $statement->execute([":email"=>$email,":password"=>$password]);
                    if($userAdded){
                        echo json_encode("user added");
                    }else{
                        array_push($this->errores,"user not added");
                        if(count($this->errores)>0){
                            echo json_encode($this->errores);
                        }
                    }
                }else{
                     echo json_encode($this->errores);
                }
            }
        }
        /*/////////////////////////////////////////////////////////////////////////*/

        /*/////////////////////////method to get a user/////////////////////*/
        public function getUser(){
            $con = $this->connection;
            $email = $this->email;
            if(empty(trim($email))){
                
                array_push($this->errores,"empty email");            

            }else{
                $email = trim($email);
                $email = stripslashes($email);
                $email = htmlspecialchars($email);
                if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
                    array_push($this->errores,"invalid emial");
                }
              
                if(count($this->errores)==0){
                    $statement = $con->prepare("select * from user where email = :email");
                    $statement->execute([":email"=>$email]);
                    $result = $statement->fetch(PDO::FETCH_ASSOC);
                    if($result){
                        echo json_encode($result);
                    }else{
                        array_push($this->errores,"user not found");
                        if(count($this->errores)>0){
                            echo json_encode($this->errores);
                        }
                    }
                }else{
                    echo json_encode($this->errores);
                }
                                
            }
            
        }
        /*/////////////////////////////////////////////////////////////////////// */

        /*//////////method to validate if user exist/////////////////*/
        private function userExist($email){
            $con = $this->connection;
            $statement = $con->prepare("select * from user where email = :email");
            $statement->execute([":email"=>$email]);
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            if($result){
                array_push($this->errores,"user exist");  
            }
        }
        /*///////////////////////////////////////////////////////// */

        /*///////////////method to log user///////////////////////// */
        public function logUser(){
            $con = $this->connection;
            $email = $this->email;
            $password = $this->password;
                 
            if(empty(trim($email)) && !empty(trim($password))){
                    
                array_push($this->errores,"empty email");
            
            }else if(!empty(trim($email)) && empty(trim($password))){
                
                array_push($this->errores,"empty password");
    
            }else if(empty(trim($email)) && empty(trim($password))){
                
                array_push($this->errores,"empty email","empty password");
    
            }else if(!empty(trim($email)) && !empty(trim($password))){
                $email = trim($email);
                $email = stripslashes($email);
                $email = htmlspecialchars($email);
                if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
                    array_push($this->errores,"invalid emial");
                }
                $password = trim($password);
                $password = stripslashes($password);
                $password = htmlspecialchars($password);
                $password = filter_var($password,FILTER_SANITIZE_STRING);
                if(strlen($password)>8 || strlen($password)<6 ){
                    array_push($this->errores,"invalid password");
                }
                
                if(count($this->errores)==0){
                    $statement = $con->prepare("select * from user where email = :email");
                    $statement->execute([":email"=>$email]);
                    $anUser = $statement->fetch(PDO::FETCH_ASSOC);
                    if($anUser){ 
                        $statement = $con->prepare("select * from user where email = :email and pass=:password");
                        $statement->execute([":email"=>$email,":password"=>$password]);
                        $userLogged = $statement->fetch(PDO::FETCH_ASSOC);
    
                        if($userLogged){
                            echo json_encode("user logged");
                        }else{
                            array_push($this->errores,"incorrect information");
                            if(count($this->errores)>0){
                                echo json_encode($this->errores);
                            }
                        }
                    }else{
                        array_push($this->errores,"incorrect information");
                        if(count($this->errores)>0){
                            echo json_encode($this->errores);
                        }
                    }
                    
                }else{
                     echo json_encode($this->errores);
                }
            }
        }
        /**/////////////////////////////////////////////////////////////////// */
     
    }

    
    if($_SERVER["REQUEST_METHOD"]=="POST"){
        if($_POST){
            $conex = $conexion->toConnect();
            $data = json_decode($_POST["data"]);
            
            $action = $_POST["action"];
           
            if($action=="addUser"){
                $email = $data->email;
                $password = $data->password;
                $u = new User($email,$password,$conex);
                $u->addUser();
            }
            else if($action=="getUser"){
                $email = $data->email;
                $u = new User($email,"",$conex);
                $u->getUser();
            }
            else if($action=="logUser"){
                $email = $data->email;
                $password = $data->password;
                $u = new User($email,$password,$conex);
                $u->logUser();
            }    
        }
    }

?>