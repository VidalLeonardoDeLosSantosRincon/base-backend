<?php
    header("Access-Control-Allow-Origin: *");
    require_once("connection.php");
    class Post{
        private $text;
        private $image;
        private $date;
        public $user;
        private $connection;
        private $errores = [];

        public function __construct($text,$image,$date,$user,$connection){
            $this->text = $text;
            $this->image = $image;
            $this->date = $date;
            $this->user = $user;
            $this->connection = $connection;
        }

        
        /*//////////////////////method to add a post///////////////////////////// */
        /*
        public function addPost(){
            $con = $this->connection;
            $text = $this->text;
            $image = $this->image;
            $date = $this->date;
            $user = $this->user;

            if(!empty(trim($text)) && empty(trim($image)) && !empty(trim($date)) && empty(trim($user))){
                
                array_push($this->errores,"empty image","empty user");

            }else if(empty(trim($text)) && !empty(trim($image)) && empty(trim($date)) && !empty(trim($user))){
                
                array_push($this->errores,"empty text","empty date");

            }else if(!empty(trim($text)) && empty(trim($image)) && empty(trim($date)) && !empty(trim($user))){
                
                array_push($this->errores,"empty image","empty date");

            }else if(empty(trim($text)) && !empty(trim($image)) && !empty(trim($date)) && empty(trim($user))){
                
                array_push($this->errores,"empty text","empty user");

            }else if(empty(trim($text)) && empty(trim($image)) && !empty(trim($date)) && !empty(trim($user))){
                
                array_push($this->errores,"empty text","empty image");

            }else if(!empty(trim($text)) && !empty(trim($image)) && empty(trim($date)) && empty(trim($user))){
                
                array_push($this->errores,"empty date","empty user");

            }
            else if(empty(trim($text)) && empty(trim($image)) && empty(trim($date)) && !empty(trim($user))){
                
                array_push($this->errores,"empty text","empty image", "empty date");

            }  else if(empty(trim($text)) && empty(trim($image)) && !empty(trim($date)) && empty(trim($user))){
                
                array_push($this->errores,"empty text","empty image", "empty user");

            }else if(empty(trim($text)) && empty(trim($image)) && empty(trim($date)) && empty(trim($user))){
                
                array_push($this->errores,"empty text","empty image", "empty date","empty user");

            }else if((!empty(trim($text)) || !empty(trim($image))) && !empty(trim($date)) && !empty(trim($user))){
                $text = trim($text);
                $text = stripslashes($text);
                $text = htmlspecialchars($text);
                $text = filter_var($text,FILTER_SANITIZE_STRING);

                $image = trim($image);
                $image = stripslashes($image);
                $image = htmlspecialchars($image);
                $image = filter_var($image,FILTER_SANITIZE_STRING);

                $date = trim($date);
                $date = stripslashes($date);
                $date = htmlspecialchars($date);
                $date = filter_var($date,FILTER_SANITIZE_STRING);

                $user = trim($user);
                $user = stripslashes($user);
                $user = htmlspecialchars($user);
                if(filter_var($user,FILTER_VALIDATE_EMAIL)){
                    array_push($this->errores,"invalid user");
                }

                if(count($this->errores)==0){
                    $statement = $con->prepare("insert into post(text,image,date,userN) values(:text,:image,:date,:user)");
                    $addPost = $statement->execute([":text"=>$text,":image"=>$image,":date"=>$date,":user"=>$user]);
                    if($addPost){
                        echo json_encode("post added");
                    }else{
                        array_push($this->errores,"post not added");
                    }

                }else{
                    echo json_encode($this->errores);
                }

            }
        }
        */
        /*/////////////////////////////////////////////////////////////////////////////// */
        
        public function addPostText(){
            $con = $this->connection;
            $text = $this->text;
            $date = $this->date;
            $user = $this->user;

            if(!empty(trim($text))  && !empty(trim($date)) && empty(trim($user))){
                
                array_push($this->errores,"empty user");

            }else if(empty(trim($text)) && empty(trim($date)) && !empty(trim($user))){
                
                array_push($this->errores,"empty text","empty date");

            }else if(!empty(trim($text)) && empty(trim($date)) && !empty(trim($user))){
                
                array_push($this->errores,"empty date");

            }else if(empty(trim($text))  && !empty(trim($date)) && empty(trim($user))){
                
                array_push($this->errores,"empty text","empty user");

            }else if(empty(trim($text))  && !empty(trim($date)) && !empty(trim($user))){
                
                array_push($this->errores,"empty text");

            }else if(!empty(trim($text)) && empty(trim($date)) && empty(trim($user))){
                
                array_push($this->errores,"empty date","empty user");

            }
            else if(empty(trim($text))  && empty(trim($date)) && !empty(trim($user))){
                
                array_push($this->errores,"empty text", "empty date");

            }  else if(empty(trim($text)) && !empty(trim($date)) && empty(trim($user))){
                
                array_push($this->errores,"empty text", "empty user");

            }else if(empty(trim($text))&& empty(trim($date)) && empty(trim($user))){
                
                array_push($this->errores,"empty text", "empty date","empty user");

            }else if(!empty(trim($text))  && !empty(trim($date)) && !empty(trim($user))){
                $text = trim($text);
                $text = stripslashes($text);
                $text = htmlspecialchars($text);
                $text = filter_var($text,FILTER_SANITIZE_STRING);

                $date = trim($date);
                $date = stripslashes($date);
                $date = htmlspecialchars($date);
                $date = filter_var($date,FILTER_SANITIZE_STRING);

                $user = trim($user);
                $user = stripslashes($user);
                $user = htmlspecialchars($user);
                if(!filter_var($user,FILTER_VALIDATE_EMAIL)){
                    array_push($this->errores,"invalid user");
                }else{
                    $statement = $con->prepare("select id from user where email = :user");
                    $statement->execute([":user"=>$user]);
                    $result = $statement->fetch(PDO::FETCH_ASSOC);
                    $user = $result["id"];

                }

                if(count($this->errores)==0){
                    $statement = $con->prepare("insert into post(text,image,date,userN) values(:text,:image,:date,:user)");
                    $addPost = $statement->execute([":text"=>$text,":image"=>"",":date"=>$date,":user"=>$user]);
                    if($addPost){
                        echo json_encode("post added");
                    }else{
                        array_push($this->errores,"post not added");
                    }

                }else{
                    echo json_encode($this->errores);
                }

            }
        }
        /*/////////////////////////////////////////////////////////////////////////////// */


        public function getPost($id){
            $con = $this->connection;
            $statement = $con->prepare("select * from post where id = :id");
            $statement->execute([":id"=>$id]);
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            if($result){
                $statement = $con->prepare("select email from user where id = :id");
                $statement->execute([":id"=>$result["userN"]]);
                $result2 = $statement->fetch(PDO::FETCH_ASSOC);
                if($result2){
                    echo json_encode(["text"=>$result["text"],"date"=>$result["date"],"user"=>$result2["email"]]);
                }
            }else{
                array_push($this->errores,"post not found");
               
            }
            if(count($this->errores)>0){
                echo json_encode($this->errores);
            }
           
        }

        /**//////////////////method to get all post///////////////////////// */
        public function getPosts(){
           $con = $this->connection;
           $statement = $con->prepare("select * from post");
           $statement->execute();
           $results = $statement->fetchAll(PDO::FETCH_ASSOC);
           $posts=[];

           if($results){
               foreach($results as $result){
                    $statement = $con->prepare("select email from user where id = :id");
                    $statement->execute([":id"=>$result["userN"]]);
                    $result2 = $statement->fetch(PDO::FETCH_ASSOC);
                    if($result2){
                        array_push($posts,["text"=>$result["text"],"date"=>$result["date"],"user"=>$result2["email"]]);
                    }
               }
            echo json_encode($posts);
            }else{
                array_push($this->errores,"posts not found");
            }
            if(count($this->errores)>0){
                echo json_encode($this->errores);
            }

        }
        /**////////////////////////////////////////////////////////////////// */

        public function  deletePost($id){

        }


    }
  

    if($_SERVER["REQUEST_METHOD"]=="POST"){
        if($_POST){
            $conex = $conexion->toConnect();
            $data = json_decode($_POST["data"]);
            
            $action = $_POST["action"];
           
            if($action=="addPost"){
                $text = $data->text;
                $image = $data->image;
                $date = $data->date;
                $user = $data->user;
                $p = new Post($text,$image,$date,$user,$conex);
                $p->addPost();
            }if($action=="addPostText"){
                $text = $data->text;
                $date = $data->date;
                $user = $data->user;
                $p = new Post($text,"",$date,$user,$conex);
                $p->addPostText();
            }
            else if($action=="getPost"){
                $user = $data->user;
                $p = new Post("t","i","d",$user,$conex);
                $p->getPost($user);
            }
            
        }
    }else if($_SERVER["REQUEST_METHOD"]=="GET"){
        if($_GET){
            $conex = $conexion->toConnect();
            $p = new Post("t","i","d","u",$conex);
            $p->getPosts(); 
        }
    }
 
?>