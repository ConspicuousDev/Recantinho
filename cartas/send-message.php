<?php
    if(isset($_POST["submit-message"])){
        $contentCopy = trim($_POST["message-content"]);
        $institution = trim($_POST["institution"]);
        $content = str_replace("\r", "", str_replace("\n", "<br>&emsp;", trim($_POST["message-content"])));
        if(empty($content)){
            header("Location: ../cartas?error=Sua carta não pode estar vazia!");
            exit();
        }
        if(empty($institution)){
            $contentCopy = str_replace("\r", "", str_replace("\n", "|", $contentCopy));
            header("Location: ../cartas?error=Você precisa selecionar uma instituição!&content=".$contentCopy);
            exit();
        }
        require_once '../php/database.php';
        $sql = "INSERT INTO cartas (institution, messageContent) VALUES ('".$institution."', '".$content."');";
        $statement = mysqli_stmt_init($connection);
        if(!mysqli_stmt_prepare($statement, $sql)){
            header("Location: ../cartas?error=Ocorreu um erro na base de dados.");
            exit();
        }
        mysqli_stmt_execute($statement);
        mysqli_stmt_close($statement);
        $id = mysqli_insert_id($connection);
        header("Location: ../cartas?success=".$institution);
        exit();
    }else{
        header("Location: ../cartas");
        exit();
    }