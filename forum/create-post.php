<?php
    if(isset($_POST["submit-post"])){
        $nick = $_POST["user"];
        $title = trim($_POST["title"]);
        $content = str_replace("\n", "<br>&emsp;", trim($_POST["post-content"]));
        if(empty($nick)){
           $nick = "Anônimo(a)";
        }
        if(empty($title)){
            header("Location: ../forum?error=Sua história precisa de um título!");
            exit();
        }
        if(empty($content)){
            header("Location: ../forum?error=Sua história não pode estar vazia!");
            exit();
        }
        require_once '../php/database.php';
        $sql = "INSERT INTO forum (postAuthor, postDate, postTitle, postContent, postComments) VALUES ('".$nick."', '".date('d')." de ".date('M')." de ".date('Y')." - ".date('H:i')."', '".$title."', '".$content."', '[]');";
        $statement = mysqli_stmt_init($connection);
        if(!mysqli_stmt_prepare($statement, $sql)){
            header("Location: ../forum?erro=Ocorreu um erro na base de dados.");
            exit();
        }
        mysqli_stmt_execute($statement);
        mysqli_stmt_close($statement);
        $id = mysqli_insert_id($connection);
        echo $id;
        header("Location: ../forum?success=".$id);
        exit();
    }else{
        header("Location: ../forum");
        exit();
    }