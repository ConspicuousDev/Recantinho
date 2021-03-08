<?php
    if(isset($_POST["submit-comment"])){
        $nick = $_POST["user"];
        $content = str_replace("\n", "<br>&emsp;", trim($_POST["content"]));
        $postID = $_POST["id"];
        if(empty($nick)){
            $nick = "Anônimo(a)";
        }
        if(empty($content)){
            header("Location: ../forum?error=Seu comentário não pode estar vazio!");
            exit();
        }
        require_once '../php/database.php';
        $sql = "SELECT * FROM forum WHERE postID = ".$postID.";";
        $statement = mysqli_stmt_init($connection);
        if(!mysqli_stmt_prepare($statement, $sql)){
            header("Location: ../forum?error=Ocorreu um erro na base de dados.");
            exit();
        }
        mysqli_stmt_execute($statement);
        $result = mysqli_stmt_get_result($statement);
        if($row = mysqli_fetch_array($result)){
            $comments = json_decode($row["postComments"], true);
            $comment = '{"author": "'.$nick.'", "date": "'.date("d").' de '.date("M").' de '.date("Y").' - '.date("H:i").'", "content": "'.$content.'"}';
            array_push($comments, json_decode($comment));
            if(in_array(null, $comments)){
                header("Location: ../forum?error=Você utilizou caracteres inválidos.");
                exit();
            }
            $sql = "UPDATE forum SET postComments = '".json_encode($comments, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)."' WHERE postID = ".$postID.";";
            $statement = mysqli_stmt_init($connection);
            if(!mysqli_stmt_prepare($statement, $sql)){
                header("Location: ../forum?error=Ocorreu um erro na base de dados.");
                exit();
            }
            mysqli_stmt_execute($statement);
            mysqli_stmt_close($statement);
            header("Location: ../forum?postID=".$postID);
            exit();
        }else{
            header("Location: ../forum?error=A postagem não foi encontrada.");
            exit();
        }
    }else{
        header("Location: ../forum");
        exit();
    }