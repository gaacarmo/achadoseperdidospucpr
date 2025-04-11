<?php
session_start();
require_once 'paginas/conecta_db.php';
$obj = conecta_db();

$query = "SELECT p.*, u.nome_usuario 
          FROM Postagem p 
          LEFT JOIN Usuario u ON p.id_usuario = u.usuario_id 
          ORDER BY p.postagem_id DESC";
$resultado = $obj->query($query);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>AcheiNaPuc</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="style.css">
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
            <a class="nav-link text-white" href="index.php">
                <i class="fas fa-home"></i> Home
            </a>
        </li>
        <li class="nav-item">
            <a class="btn btn-light w-100 publicar-btn" href="include.php?dir=paginas&file=publicar">
                <i class="fas fa-feather-alt"></i> Publicar
            </a>
        </li>
    </ul>
</div>

<div class="content">
    <div class="container mt-4">
        <h2 class="mb-4">Postagens Recentes</h2>

        <?php while ($linha = $resultado->fetch_assoc()): ?>
            <div class="post-container">
                <?php if (!empty($linha['postagem_image'])): ?>
                    <img src="uploads/<?= htmlspecialchars($linha['postagem_image']) ?>" alt="Imagem do objeto">
                <?php endif; ?>
                
                <div class="post-content">
                    <h5><?= htmlspecialchars($linha['postagem_nome']) ?> <span class="badge bg-secondary"><?= htmlspecialchars($linha['postagem_usuario_tipo']) ?></span></h5>
                    <p><?= htmlspecialchars($linha['postagem_descricao']) ?></p>
                    <p><strong>Local:</strong> <?= htmlspecialchars($linha['postagem_local']) ?> | <strong>Data:</strong> <?= htmlspecialchars($linha['postagem_data']) ?></p>
                    <?php if (!empty($linha['nome_usuario'])): ?>
                        <p class="text-muted mb-1">Postado por: <?= htmlspecialchars($linha['nome_usuario']) ?></p>
                    <?php endif; ?>
                    <div class="post-actions">
                        <button><i class="far fa-thumbs-up text-primary"></i> Curtir</button>
                        <button><i class="far fa-comment text-secondary"></i> Comentar</button>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
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
            </li>
            </div>";

        echo "<li class='nav-item'>
                <a class='nav-link' href='include.php?dir=paginas&file=cadastro'>
                    <i class='fas fa-user-plus'></i> Cadastro
                </a>
            </li>;
        </div>";
    }
?>

</body>
</html>
