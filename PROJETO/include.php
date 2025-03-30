<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Include Base</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="parent">
        <div class="div1">
            <div class="container mt-3">
                <h2>Achei PUCPR</h2>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="include.php?dir=paginas&file=login">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="include.php?dir=paginas&file=cadastro">Cadastro</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Em produção</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="conteudo">
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

        <div class="div4">Perfil - Em produção</div>
    </div>
</body>

<footer>Rodapé - Em produção</footer>

</html>

<style>
    .parent {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        grid-template-rows: repeat(5, 1fr);
        gap: 8px;
        height: 100vh;
    }
        
    .div1 {
        position: fixed;
        left: 0;    
        top: 50%;
        transform: translateY(-50%);
        color: black;
        display: flex;
        align-items: center;
    }

    .div4 {
        grid-row: span 5 / span 5;
        grid-column-start: 5;
        grid-row-start: 1;
        color: black;
        background-color: red;
    }

    .conteudo {
        grid-column: span 3 / span 3;
        grid-row: span 5 / span 5;
        grid-column-start: 2;
        grid-row-start: 1;
        color: black;
        background-color: orange;
    }
</style>
