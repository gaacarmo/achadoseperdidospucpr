<?php

session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AcheiNaPuc</title>
    <link rel="stylesheet" href="include.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
    
</style>

</head>
<body>
    <div class="sidebar">
        <h2>Achei PUCPR</h2>
        <ul class="nav flex-column">
        <li class="nav-item">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-sign-in-alt"></i> Home
                </a>
            </li>
            <li class="nav-item">
                <a class="btn btn-danger w-100 publicar-btn" href="include.php?dir=paginas&file=publicar">
                    <img src="assets/add.png" alt="Publicar">
                </a>
            </li>

        </ul>
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
        echo "<button class='btn btn-primary'><a href='#'>Editar Perfil</a></button>";
        echo "<button class='btn btn-primary'><a href='include.php?dir=paginas&file=del_usu'>Sair</a></button>";
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
