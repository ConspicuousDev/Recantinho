<?php
    require_once '../php/database.php';
    $sql = "SELECT * FROM messages ORDER BY id DESC LIMIT " . $_POST["count"] . ";";
    $statement = mysqli_stmt_init($connection);
    if (!mysqli_stmt_prepare($statement, $sql)) {
        exit();
    }
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);
    $firstMessage = 0;
    $return = "";
    while ($row = mysqli_fetch_row($result)) {
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
            $userSQL = "SELECT * FROM users WHERE ip = '".$row[1]."';";
            $userStatement = mysqli_stmt_init($connection);
            if (!mysqli_stmt_prepare($userStatement, $userSQL)) {
                exit();
            }
            mysqli_stmt_execute($userStatement);
            $userResult = mysqli_stmt_get_result($userStatement);
            if($userRow = mysqli_fetch_row($userResult)){
                $author = $userRow[2];
            }
            $result = mysqli_stmt_get_result($statement);
            $return .= '<div class="message-container">';
            $return .= '<div class="received-message" id="' . $row[0] . '">';
            $return .= '<div class="author">' . strtoupper($author) . '</div>';
            $return .= '<div class="message">' . $row[2] . '</div>';
            $return .= '</div>';
            $return .= '</div>';
        }
    }
//    if ($firstMessage > 0) {
//        $return .= "<script>document.getElementById('" . $firstMessage . "').scrollIntoView(true)</script>";
//    }
    echo $return;