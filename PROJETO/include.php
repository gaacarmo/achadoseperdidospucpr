<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AcheiNaPuc</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            height: 100vh;
        }

        .sidebar {
            width: 25%;
            background-color: #ffffff;
            border-right: 1px solid #ddd;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .sidebar h2 {
            font-size: 40px;
            margin-bottom: 30px;
            
        }

        .sidebar .nav-item {
            width: 100%;
        }

        .sidebar .nav-link {
            display: flex;
            align-items: center;
            font-size: 18px;
            color: black;
            text-decoration: none;
            padding: 10px;
            width: 100%;
            border-radius: 25px;
            transition: background 0.2s;
        }

        .sidebar .nav-link:hover {
            background-color: #e8f5fe;
        }

        .sidebar .nav-link i {
            margin-right: 15px;
            font-size: 22px;
        }

        .content {
            width: 50%;
            padding: 20px;
            text-align: center;
            overflow-y: auto;
        }

        .profile {
            width: 25%;
            background-color: #ffffff;
            border-left: 1px solid #ddd;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .profile img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 10px;
        }

        .profile h3 {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .profile p {
            font-size: 14px;
            color: gray;
            margin-bottom: 20px;
        }
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
                <a class="nav-link" href="#">
                    <i class="fas fa-tools"></i> Em produção
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
