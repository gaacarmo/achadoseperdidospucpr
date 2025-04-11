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
                <a class="nav-link" href="include.php?dir=paginas&file=login">
                    <i class="fas fa-sign-in-alt"></i> Login
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="include.php?dir=paginas&file=cadastro">
                    <i class="fas fa-user-plus"></i> Cadastro
                </a>
            </li>
            <li class="nav-item">
                <a class="btn btn-danger w-100 publicar-btn" href="include.php?dir=paginas&file=publicar">
                <i class="fas fa-feather-alt"></i> Publicar
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

    <div class="profile">
        <img src="https://via.placeholder.com/80" alt="Foto de perfil">
        <h3>Nome do Usuário</h3>
        <p>@usuario</p>
        <button class="btn btn-primary">Editar Perfil</button>
    </div>
</body>
</html>
