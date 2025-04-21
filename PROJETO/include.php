<?php

session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>AcheiNaPuc</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="./CSS/include.css?v=<?php echo time(); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>



</head>
<body>
<div class="sidebar">
    <div>
        <div class="title">Achei na PUCPR</div>
        <div class="nav-item"><a class="nav-link" href="index.php"><i class="fa fa-home"></i><img src="assets/home.png" alt="home"> Início</a></div>
        <div class="nav-item"><a class="nav-link" href="#"><i class="fa fa-search"></i> <img src="assets/user.png" alt="home">Perfil</a></div>
        <div class="nav-item"><a class="nav-link" href="include.php?dir=paginas&file=login"><i class="fa fa-bell"></i><img src="assets/login.png" alt="home"> Login</a></div>
        <div class="nav-item"><a class="nav-link" href="include.php?dir=paginas&file=publicar"><i class="fa fa-user"></i> <img src="assets/add.png" alt="Publicar">Publicar</a></div>
    </div>
    <button class="publicar-btn" onclick="window.location.href='include.php?dir=paginas&file=publicar'">Publicar</button>
</div>

    <div class="content">
        <?php
            // Verifica se os parâmetros estão presentes
            if (isset($_GET['dir']) && isset($_GET['file']) && !empty($_GET['dir']) && !empty($_GET['file'])) {
                $dir = basename($_GET['dir']); // Evita diretórios externos
                $file = basename($_GET['file']); // Garante que apenas o nome do arquivo seja usado

                $path = __DIR__ . "/{$dir}/{$file}.php";

                // Verifica se o arquivo existe antes de incluir
                if (file_exists($path)) {
                    include($path);
                } else {
                    echo "<p class='text-danger'>Erro: Página não encontrada!</p>";
                }
            } else {
                // Página inicial padrão
                include(__DIR__ . "/PROJETO/include.php"); 
            }
        ?>
    </div>

    <?php
    if(isset($_SESSION['is_logged_user']) && $_SESSION['is_logged_user'] === true){
        echo "<div class='profile'>";
        echo "<img src='https://via.placeholder.com/80' alt='Foto de perfil'>";
        echo "<h3>{$_SESSION['usuario']}</h3>";
        echo "<p>@{$_SESSION['usuario']}</p>";
        echo "<button class='btn btn-primary' onclick=\"window.location.href='#'\">Editar Perfil</button>";
        echo "<button class='btn btn-primary' onclick=\"window.location.href='include.php?dir=paginas&file=del_usu'\">Sair</button>";
        echo '</div>';
    } else {
        echo "<div class='profile'>
            <li class='nav-item'>
                <a class='nav-link' href='include.php?dir=paginas&file=login'>
                    <i class='fas fa-sign-in-alt'></i> Login
                </a>
            </li>";

        echo "<li class='nav-item'>
                <a class='nav-link' href='include.php?dir=paginas&file=cadastro'>
                    <i class='fas fa-user-plus'></i> Cadastro
                </a>
            </li>
        </div>";
    }
?>
</body>
</html>
