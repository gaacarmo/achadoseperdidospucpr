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
            <a href="index_adm.php"><img src="/Documentacao/Logo.png" alt="Logo do site"></a>
        </div>
        <ul>
            <li><a href="index_adm.php">Home</a></li>
            <li><a href="paginas_adm/lista_usuarios.php">Listar Usuários</a></li>
            <li><a href="#">Verficação de posts</a></li>
            <li><a href="#">Relatórios</a></li>
        </ul>
    </nav>

    <main>
        <div class="content-section">
            <h1>Home do moderador - Em desenvolvimento</h1>
        </div>
        
        <!-- Grid content sections -->
        <div class="content-format">
            <div class="content-div">
                <h2><a href="paginas_adm/lista_usuarios.php">Lista de usuários</a></h2>
                <p>Lista de todos usuários cadastrados no sistema.</p>
            </div>

            <div class="content-div">
                <h2><a href="#">Verificação de posts</a></h2>
                <p>Gerencie e verifique os posts dos usuários.</p>
            </div>

            <div class="content-div">
                <h2><a href="#">Relatórios</a></h2>
                <p>Acesse relatórios e estatísticas do sistema.</p>
            </div>
        </div>
    </main>

    <footer>
        <p>AcheiNaPUC - &copy; <?php echo date('Y'); ?></p>
    </footer>
</body>
</html>