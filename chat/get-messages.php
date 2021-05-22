<?php
    require_once '../php/database.php';
    $sql = "SELECT * FROM users";
    $statement = mysqli_stmt_init($connection);
    if(!mysqli_stmt_prepare($statement, $sql)){
        header("Location:../chat?error=Ocorreu um erro na base de dados.");
        exit();
    }
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);
    $usernames = [];
    while($row = mysqli_fetch_array($result)){
        if($row["username"] !== null){
            $usernames[$row["ip"]] = $row["username"];
        }else{
            $usernames[$row["ip"]] = "USUÁRIO ".$row["id"];
        }
    }
    $sql = "SELECT * FROM messages ORDER BY id DESC LIMIT " . $_POST["count"] . ";";
    $statement = mysqli_stmt_init($connection);
    if(!mysqli_stmt_prepare($statement, $sql)){
        header("Location:../chat?error=Ocorreu um erro na base de dados.");
        exit();
    }
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);
    $firstMessage = 0;
    $return = "";
    while ($row = mysqli_fetch_row($result)) {
        echo mysqli_error($connection);
        if ($firstMessage < $row[0]) {
            $firstMessage = $row[0];
        }
        if ($row[1] ===  $_POST["ip"]) {
            $return .= '<div class="message-container">';
            $return .= '<div class="sent-message" id="' . $row[0] . '">';
            $return .= '<div class="author">' . strtoupper($_POST["user"]) . '</div>';
            $return .= '<div class="message">' . $row[2] . '</div>';
            $return .= '</div>';
            $return .= '</div>';
        } else {
            $author = "USUÁRIO NÃO ENCONTRADO";
            if(array_key_exists($row[1], $usernames)){
                $author = $usernames[$row[1]];
            }
            $return .= '<div class="message-container">';
            $return .= '<div class="received-message" id="' . $row[0] . '">';
            $return .= '<div class="author">' . strtoupper($author) . '</div>';
            $return .= '<div class="message">' . $row[2] . '</div>';
            $return .= '</div>';
            $return .= '</div>';
        }
    }
    echo $return;