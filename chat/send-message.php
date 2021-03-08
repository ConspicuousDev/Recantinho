<?php
    require_once "../php/database.php";
    if(isset($_POST["submit"])){
        $count = trim($_POST["count"]);
        $ip = trim($_POST["ip"]);
        $author = trim($_POST["user"]);
        $content = str_replace("\r", "", str_replace("\n", "<br>&emsp;", trim($_POST["text"])));
        if(empty($content)){
            header("Location: ../chat?error=Sua mensagem não pode estar vazia!");
            exit();
        }
        $sql = "SELECT * FROM users WHERE ip = '".$ip."';";
        $statement = mysqli_stmt_init($connection);
        if(!mysqli_stmt_prepare($statement, $sql)){
            header("Location: ../chat?error=Ocorreu um erro na base de dados.");
            exit();
        }
        mysqli_stmt_execute($statement);
        $result = mysqli_stmt_get_result($statement);
        if($row = mysqli_fetch_array($result)){
            if($row["username"] !== null) {
                $username = $row["username"];
            }else{
                $username = "USUÁRIO ".$row["id"];
            }
        }
        if(empty($author)){
            $author = $username;
        }else{
            if($author !== $username){
                $sql = "UPDATE users SET username = '".$author."' WHERE ip = '".$ip."';";
                $statement = mysqli_stmt_init($connection);
                if(!mysqli_stmt_prepare($statement, $sql)){
                    header("Location: ../chat?error=Ocorreu um erro na base de dados.");
                    exit();
                }
                mysqli_stmt_execute($statement);
            }
        }
        $sql = "INSERT INTO messages (ip, message) VALUES ('".$ip."', '".$content."')";
        $statement = mysqli_stmt_init($connection);
        if(!mysqli_stmt_prepare($statement, $sql)){
            header("Location: ../chat?erro=Ocorreu um erro na base de dados.");
            exit();
        }
        mysqli_stmt_execute($statement);
        mysqli_stmt_close($statement);
        $id = mysqli_insert_id($connection);
        $count += 1;
        header("Location: ../chat?success=".$id."&count=".$count);
        exit();
    }else{
        header("Location: ../chat?");
        exit();
    }