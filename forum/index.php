<?php
    require_once '../php/database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-type" content="text/html;charset=utf-8">
    <title>Recantinho</title>
    <link rel="icon" href="../img/logo.png">
    <link rel="stylesheet" href="../css/content.css">
    <link rel="stylesheet" href="../css/scrollbar.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="forum.css">
    <?php
        $url = strtok((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]", "?");
        echo("<script>history.replaceState({},'','$url');</script>");
        $postCount = 50;
        if(isset($_GET["postCount"])){
            $postCount = intval($_GET["postCount"]);
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
        <div class="section">
            <div class="title">Fórum</div>
            <div class="text">&emsp;Seja bem vindo ao nosso fórum! O propósito desta página é inpirar, ser inspirado, apoiar ou ser apoiado através das postagens abaixo, onde outros usuários (e talvez você) publicarão suas histórias possibilitando algum engajamento por meio dos comentários em cada postagem. Você pode fazer uma publicação <a class="link" href="#create-post">aqui</a>, mas antes é necessário ter em mente algumas regras:</div>
            <div class="text">&emsp;&emsp;&emsp;• Todas as postagens passam por revisão constante, visando eliminar qualquer mensagem que possa vir a causar distúrbio a outros usuários.</div>
            <div class="text">&emsp;&emsp;&emsp;• Busque manter suas histórias amigáveis para o ambiente familiar uma vez que o fórum é aberto para a participação todos.</div>
            <div class="text">&emsp;&emsp;&emsp;• Não solicite nem publique informações pessoais de ninguém, sempre respeitando o anonimato de alguns.</div>
        </div>
        <?php
            $sql = "SELECT * FROM forum ORDER BY postID DESC LIMIT ".$postCount.";";
            $statement = mysqli_stmt_init($connection);
            if(!mysqli_stmt_prepare($statement, $sql)){
                header("Location: ?error=Ocorreu um erro na base de dados.");
                exit();
            }
            mysqli_stmt_execute($statement);
            $result = mysqli_stmt_get_result($statement);
            $count = 0;
            while($row = mysqli_fetch_array($result)) {
                $comments = json_decode($row["postComments"], true);
                echo '<div class="post" onmouseover="enteredPost(this)" onmouseleave="leftPost(this)" id="'.$row["postID"].'">';
                echo '<div class="header">';
                echo '<img src="../img/profiles/'.rand(1, 16).'.png">';
                echo '<div class="info">';
                echo '<div class="author">'.$row["postAuthor"].'</div>';
                echo '<div class="date">'.$row["postDate"].'</div>';
                echo '</div>';
                echo '</div>';
                echo '<div class="body">';
                echo '<div class="title" style="font-size: 28px">'.$row["postTitle"].'</div>';
                echo '<div>&emsp;'.$row["postContent"].'</div>';
                echo '</div>';
                echo '<div class="title" style="text-align: left; font-size: 24px; font-weight: 500; padding-left: 20px">'.sizeof($comments).' Comentário(s)</div>';
                echo '<div class="comments">';
                foreach($comments as $comment){
                    echo '<div class="comment">';
                    echo '<div class="header">';
                    echo '<img src="../img/profiles/'.rand(1, 16).'.png">';
                    echo '<div class="info">';
                    echo '<div class="author">'.$comment["author"].'</div>';
                    echo '<div class="date">'.$comment["date"].'</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '<div class="body">';
                    echo '<div>'.$comment["content"].'</div>';
                    echo '</div>';
                    echo '</div>';
                }
                echo '<div class="form">';
                echo '<div class="title" style="font-size: 28px; font-weight: 600">Adicione um comentário</div>';
                echo '<form method="post" action="add-comment.php" id="comment-form-'.$row["postID"].'" autocomplete="off">';
                echo '<input type="hidden" name="id" value="'.$row["postID"].'">';
                echo '<label for="user">COMO DEVEMOS TE CHAMAR?</label><br>';
                echo '<input class="text-field" name="user" type="text" placeholder="Apelido" maxlength="18"><br>';
                echo '<span class="small-text">Deixe o campo acima em branco para postar como Anônimo.</span><br>';
                echo '<div style="margin: 20px"></div>';
                echo '<textarea style="height: 100px; margin-top: 0" form="comment-form-'.$row["postID"].'" name="content" class="text-area" placeholder="Escreva aqui..." maxlength="200"></textarea><br>';
                echo '<input class="button" name="submit-comment" type="submit" value="Enviar">';
                echo '</form>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                $count++;
            }
            if($count == 0){
                echo "<div class='title' style='font-weight: 400; padding: 50px 0'>Não há postagens ainda! Seja o primeiro :)</div>";
            }
            mysqli_stmt_close($statement);
            if(isset($_GET["postID"])){
                echo '<script>document.getElementById("'.$_GET["postID"].'").getElementsByClassName("comments")[0].scrollIntoView(true)</script>';
            }
        ?>
        <div class="section" style="text-align: center">
            <button class="button" id="see-more" onclick="location.href = '<?php echo $url.'?postCount='.($postCount+10).'&reload=true' ?>'">Ver Mais</button>
        </div>
        <?php
            if(isset($_GET["reload"])){
                echo("<script>document.getElementById('see-more').scrollIntoView(true)</script>");
            }
        ?>
        <div class="section" id="create-post">
            <div class="title">Crie uma postagem!</div>
            <div class="text">&emsp;Preecha o formulário abaixo para compartilhar uma história, experiência ou fato com todos aqui. Desse modo, poderemos conversar e trocar ideias por meio dos comentários.</div>
        </div>
        <div class="form">
            <form method="post" action="create-post.php" id="post-form" autocomplete="off">
                <label for="user">COMO DEVEMOS TE CHAMAR?</label><br>
                <input class="text-field" name="user" type="text" placeholder="Apelido" maxlength="18"><br>
                <span class="small-text">Deixe o campo acima em branco para postar como Anônimo.</span><br>
                <div style="margin: 20px"></div>
                <label for="user">QUAL O TÍTULO DA SUA HISTÓRIA?</label><br>
                <input class="text-field" name="title" type="text" placeholder="Título" maxlength="18"><br>
                <div style="margin: 20px"></div>
                <label for="post-content">CONTE-NOS SUA HISTÓRIA</label><br>
                <textarea form="post-form" name="post-content" class="text-area" placeholder="Escreva aqui..."></textarea><br>
                <input class="button" name="submit-post" type="submit" value="Enviar">
                <?php
                    if(isset($_GET["error"])){
                        echo("<div class='response error'><span>".$_GET['error']."</span></div>");
                        echo("<script>document.getElementsByClassName('response')[0].scrollIntoView(true)</script>");
                    }else if(isset($_GET["success"])){
                        echo("<div class='response success'><span>Sua postagem foi enviada com sucesso! Clique <a class='link' style='cursor: pointer' onclick='document.getElementById(\"".$_GET['success']."\").scrollIntoView(true)'>aqui</a> para vê la!</span></div>");
                        echo("<script>document.getElementsByClassName('response')[0].scrollIntoView(true)</script>");
                    }
                ?>
            </form>
        </div>
        <div class="footer">
            <div class="text">Todos os direitos reservados © Recantinho 2021</div>
            <div class="text">Esta plataforma é conduzida para um projeto da <a class="link" href="https://www.nhs.us/" target="_blank">National Honor Society US</a>.</div>
        </div>
    </div>
</body>
<script>
    const elements = document.querySelectorAll(".post")
    for(var i = 0; i < elements.length; i++){
        elements[i].getElementsByClassName("form")[0].hidden = true
    }

    function enteredPost(post){
        const id = post.id
        post.getElementsByClassName("form")[0].hidden = false
    }

    function leftPost(post){
        const id = post.id
        post.getElementsByClassName("form")[0].hidden = true
    }
</script>
</html>