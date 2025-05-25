<?php
function include_file($dir, $file) {
    $file_path = __DIR__ . '/' . $dir . '/' . $file . '.php';
    if (file_exists($file_path)) {
        ob_start();
        include $file_path;
        $content = ob_get_clean();
        return $content;
    } else {
        return "<div class='alert alert-danger'>Arquivo não encontrado: $file_path</div>";
    }
}

$page_content = '';
if (isset($_GET['dir']) && isset($_GET['file'])) {
    $page_content = include_file($_GET['dir'], $_GET['file']);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOME - ADM</title>
    <link rel="stylesheet" href="./CSS/index_adm.css">
</head>
<body>
    <nav>
        <div class="nav-img">
            <a href="index_adm.php"><img src="../Documentacao/Logo.png" alt="Logo do site"></a>
        </div>
        <ul>
            <li><a href="index_adm.php">Home</a></li>
            <li><a href="include_adm.php?dir=paginas_adm&file=lista_usuarios">Listar Usuários</a></li>
            <li><a href="include_adm.php?dir=paginas_adm&file=verificacao_post">Verficação de posts</a></li>
            <li><a href="#">Relatórios</a></li>
        </ul>
    </nav>

    <main>
        <?php
            echo $page_content;
        ?>
    </main>

    <footer>
        <p>AcheiNaPUC - &copy; <?php echo date('Y'); ?></p>
    </footer>
</body>
</html>