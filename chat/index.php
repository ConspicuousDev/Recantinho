<?php
    require_once '../php/database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Recantinho</title>
    <link rel="icon" href="../img/logo.png">
    <link rel="stylesheet" href="../css/content.css">
    <link rel="stylesheet" href="../css/scrollbar.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="chat.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <?php
        $url = strtok((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]", "?");
        echo("<script>history.replaceState({},'','$url');</script>");
        $ip = $_SERVER["REMOTE_ADDR"];
        $user = "";
        $sql = "SELECT * FROM users WHERE ip = '".$ip."';";
        $statement = mysqli_stmt_init($connection);
        if(!mysqli_stmt_prepare($statement, $sql)){
            header("Location: ../?error=Ocorreu um erro na base de dados.");
            exit();
        }
        mysqli_stmt_execute($statement);
        $result = mysqli_stmt_get_result($statement);
        if($row = mysqli_fetch_array($result)){
            if($row["username"] !== null) {
                $user = $row["username"];
            }else{
                $user = "USUÁRIO ".$row["id"];
            }
        }else{
            $sql = "INSERT INTO users (ip) VALUES ('".$ip."');";
            $statement = mysqli_stmt_init($connection);
            if(!mysqli_stmt_prepare($statement, $sql)){
                header("Location: ../?error=Ocorreu um erro na base de dados.");
                exit();
            }
            mysqli_stmt_execute($statement);
            header("Location: ../chat");
            exit();
        }
        $messageCount = 50;
        if(isset($_GET["count"])){
            $messageCount = $_GET["count"];
        }
    ?>
</head>
<body>
    <link rel="stylesheet" href="../css/nav.css">
    <div class="nav">
        <div class="logo"><a href="../"><img src="../img/logo.png" width="70" height="70"></a></div>
        <div class="nav-item" style="border-color: #91E5F6"><a href="../home"><img src="../img/icons/home.png"><div>Home</div></a></div>
        <div class="nav-item" style="border-color: #84D2F6"><a href="../cartas"><img src="../img/icons/cartas.png"><div>Cartas</div></a></div>
        <div class="nav-item" style="border-color: #59A5D8"><a href="../forum"><img src="../img/icons/compartilhar.png"><div>Fórum</div></a></div>
        <div class="nav-item" style="border-color: #386FA4"><a href="../chat"><img src="../img/icons/chat.png"><div>Chat</div></a></div>
    </div>
    <div class="content">
        <div class="chat-section">
            <div class="chat-container">
                <div class="chat-display" id="output">

                </div>
                <div class="chat-interface">
                    <form style="display: inline-flex; width: 100%" action="send-message.php" method="post">
                        <input type="hidden" name="ip" value="<?php echo $ip ?>">;
                        <input class="text-field" style="width: 15%;" type="text" name="user" placeholder="Anônimo" value="<?php echo $user ?>">;
                        <input class="text-field" style="width: 100%;" type="text" name="text" placeholder="Escreva sua mensagem...">;
                        <input type="hidden" name="count" value="<?php echo $messageCount ?>">;
                        <input class="button" type="submit" name="submit" value="Enviar">;
                    </form>
                </div>
            </div>
        </div>
        <div class="response popup" style="background-color: #FFAAAA" id="popup">
                <span class="message">
                    <p><?php if(isset($_GET["error"])){ echo "".$_GET["error"]."</span>"; }?></p>
                </span>
                <span class="close" onclick="closePopup()">&times;</span>
        </div>
        <?php
            if(isset($_GET["error"])){
                echo "<script>document.getElementById('popup').style.display = 'inline-flex'</script>";
            }
        ?>
    </div>
</body>
<script>
    function closePopup(){
        document.getElementById('popup').style.display = "none";
    }
    $(document).ready(function (){
        function getMessages(){
            $.ajax({
                type: 'POST',
                url: 'get-messages.php',
                data: {count: "<?php echo $messageCount ?>", ip: "<?php echo $ip ?>", user: "<?php echo $user ?>"},
                success: function (data){
                    $('#output').html(data)
                }
            });
        }
        getMessages();
        setInterval(function (){
            getMessages();
        }, 1000);
    });
</script>
</html>