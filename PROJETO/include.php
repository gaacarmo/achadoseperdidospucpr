<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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

        .div5 {
            grid-column: span 3 / span 3;
            grid-row: span 5 / span 5;
            grid-column-start: 2;
            grid-row-start: 1;
            color: black;
            background-color: orange;
        }
        
        .cart-icon {
            position: fixed;
            top: 20px;
            right: 20px;
        }
    </style>
</head>
<body>
    
<div class="parent">
    <div class="div1">
        <div class="container mt-3">
            <h2>Vertical Nav</h2>
            <p>Use the .flex-column class to create a vertical nav:</p>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="?dir=paginas&file=login">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" href="#">Disabled</a>
                </li>
            </ul>
        </div>
    </div>
    
    <div class="cart-icon">
        <a href="home.php?dir=paginas&file=carrinho"><img src="assets/shopping-cart.png" alt="Carrinho de compras"></a>
    </div>

    <div class="div5">
        <div class="conteudo">
            <?php
            // Verifica se 'dir' e 'file' estão definidos e não estão vazios
            if (isset($_GET['dir']) && isset($_GET['file']) && !empty($_GET['dir']) && !empty($_GET['file'])) {
                $dir = preg_replace('/[^a-zA-Z0-9-_]/', '', $_GET['dir']); // Sanitiza o nome do diretório
                $file = preg_replace('/[^a-zA-Z0-9-_]/', '', $_GET['file']); // Sanitiza o nome do arquivo 

                $path = __DIR__ . "/{$dir}/{$file}.php";

                // Verifica se o arquivo realmente existe antes de incluir
                if (file_exists($path)) {
                    include($path);
                } else {
                    echo "Arquivo não encontrado!";
                }
            } else {
                // Inclui uma página padrão ou exibe uma mensagem quando 'dir' e 'file' não são fornecidos
                include(__DIR__ . "/paginas/include.php"); // Corrigido o caminho com barra inicial
            }
            ?>
        </div>
    </div>
    
    <div class="div4">perfil</div>
</div>
    
</body>
</html>