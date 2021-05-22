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
    <link rel="stylesheet" href="cartas.css">
    <?php
        $url = strtok((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]", "?");
        echo("<script>history.replaceState({},'','$url');</script>");
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
            <div class="title">Cartas</div>
            <div class="text">&emsp;Começou o dia se sentindo especial? Compartilhe esse sentimento com alguem que vá apreciá-lo! Ao preencher o formulário abaixo, você pode selecionar alguma das instituições parceiras para enviar uma mensagem que será entregue a seus beneficiários.</div>
            <div class="text">&emsp;Para saber mais sobre cada instituição, você pode acessar o link indicado próximo a cada uma delas, que o redirecionará para o site da instituição em questão. Caso você tenha dúvidas sobre como ajudar diretamente cada alguma das instituições, por favor não hesite em entrar em contato diretamente com a mesma!</div>
        </div>
        <div class="form" style="width: 80vw; max-width: 1000px">
            <form method="post" action="send-message.php" id="message-form" autocomplete="off">
                <label>SELECIONE UMA INSTITUIÇÃO</label><br>
                <div class="institution-display">
                    <?php
                        foreach(scandir("../img/institutions/") as $file){
                            if(substr($file, 0, 1) !== "."){
                                $institutionName = "";
                                $institutionParts = explode(" ", $file);
                                for ($i = 0; $i < sizeof($institutionParts) - 1; $i++) {
                                    $institutionName .= $institutionParts[$i]." ";
                                }
                                $institutionName = substr($institutionName, 0, strlen($institutionName)-1);
                                echo '<div class="institution-selector" id="'.$institutionName.'" onclick="setInstitution(\''.$institutionName.'\')">';
                                echo '<img src="../img/institutions/'.$file.'" width="160" height="160">';
                                echo '<div>'.$institutionName.'</div>';
                                echo '<a href="http://'.str_replace("=", "/", str_replace(".png", "", $institutionParts[sizeof($institutionParts)-1])).'" target="_blank"><img src="../img/icons/link.png" width="24" height="24"></a>';
                                echo '</div>';
                            }
                        }
                    ?>
                </div>
                <input type="hidden" name="institution" id="institution-input" value="">
                <label for="post-content">ESCREVA SUA MENSAGEM</label><br>
                <textarea form="message-form" name="message-content" class="text-area" placeholder="Escreva aqui..."><?php if(isset($_GET["content"])){ echo str_replace(array("|"), "\n", trim($_GET["content"])); } ?></textarea><br>
                <input class="button" name="submit-message" type="submit" value="Enviar">
            </form>
            <?php
                if(isset($_GET["error"])){
                    echo("<div class='response error'><span>".$_GET['error']."</span></div>");
                    echo("<script>document.getElementsByClassName('response')[0].scrollIntoView(true)</script>");
                }else if(isset($_GET["success"])){
                    echo("<div class='response success'><span>Sua carta foi enviada com sucesso! Em breve ela será enviada para a instituição ".$_GET["success"].".</span></div>");
                    echo("<script>document.getElementsByClassName('response')[0].scrollIntoView(true)</script>");
                }
            ?>
        </div>
        <div class="footer">
            <div class="text">Todos os direitos reservados © Recantinho 2021</div>
            <div class="text">Esta plataforma é conduzida para um projeto da <a class="link" href="https://www.nhs.us/" target="_blank">National Honor Society US</a>.</div>
        </div>
    </div>
<script>
    function setInstitution(institution){
        const input = document.getElementById("institution-input")
        for(let i = 0; i < document.getElementsByClassName("selected-institution").length; i++){
            document.getElementsByClassName("selected-institution")[i].classList.remove("selected-institution")
        }
        if(input.value === institution){
            input.value = ""
        }else{
            input.value = institution
            document.getElementById(institution).classList.add("selected-institution")
        }
    }
</script>
</body>
</html>