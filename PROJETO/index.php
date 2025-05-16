<?php
session_start();
require_once 'paginas/conecta_db.php';
$obj = conecta_db();

// Consulta para buscar as postagens
$query = "SELECT p.*, u.nome_usuario 
        FROM Postagem p 
        LEFT JOIN Usuario u ON p.id_usuario = u.usuario_id 
        ORDER BY p.postagem_id DESC";
$resultado_postagens = $obj->query($query);

// Consulta para buscar a foto de perfil do usuário logado
$query_foto = 'SELECT foto_perfil FROM Usuario WHERE usuario_id = ?';
$stmt = $obj->prepare($query_foto);
$stmt->bind_param('i', $_SESSION['usuario_id']);
$stmt->execute();
$stmt->bind_result($foto_perfil);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>AcheiNaPuc</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="./CSS/style.css?v=<?php echo time();?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>
    <button class="mobile-menu-toggle" id="mobileMenuToggle">
        <i class="fas fa-bars"></i>
    </button>

<div class="sidebar">
    <div>
        <div class="title">Achei na PUCPR</div>
        <div class="nav-item"><a class="nav-link" href="index.php"><i class="fa fa-home"></i><img src="assets/home.png" alt="home"> Início</a></div>
        <div class="nav-item"><a class="nav-link" href="include.php?dir=paginas&file=editar"><i class="fa fa-search"></i> <img src="assets/user.png" alt="home">Perfil</a></div>
        <div class="nav-item"><a class="nav-link" href="include.php?dir=paginas&file=login"><i class="fa fa-bell"></i><img src="assets/login.png" alt="home"> Login</a></div>
        <div class="nav-item"><a class="nav-link" href="include.php?dir=paginas&file=publicar"><i class="fa fa-user"></i> <img src="assets/add.png" alt="Publicar">Publicar</a></div>
    </div>
    <button class="publicar-btn" onclick="window.location.href='include.php?dir=paginas&file=editar-perfil'">Publicar</button>
</div>

    <div class="content">
        <div class="container mt-4">
            <h2 class="mb-4">Postagens Recentes</h2>

        <?php 
        if($resultado_postagens->num_rows > 0):
            while ($linha = $resultado_postagens->fetch_assoc()): ?>
                <div class="post-container">
                    <?php if (!empty($linha['postagem_image'])): ?>
                        <img src="<?= htmlspecialchars($linha['postagem_image']) ?>" alt="Imagem do objeto">
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
        <?php else: ?>
            <p>Nenhuma postagem encontrada</p>
        <?php endif; ?>
    </div>
</div>

<?php
// Se estiver logado, exibe o perfil e a foto
if (isset($_SESSION['is_logged_user']) && $_SESSION['is_logged_user'] === true):
    echo "<div class='profile'>";
    
    // Exibe a foto de perfil
    if (!empty($foto_perfil)): 
        echo "<img src='$foto_perfil' alt='Foto de perfil' class='profile-img' style='width: 100px; height: 100px; border-radius: 50%;'>";
    else:
        echo "<p>Foto de perfil não encontrada.</p>";
    endif;

    echo "<h3>{$_SESSION['usuario']}</h3>";
    echo "<p>@{$_SESSION['usuario']}</p>";
    echo "<button class='btn btn-primary' onclick=\"window.location.href='include.php?dir=paginas&file=editar'\">Editar Perfil</button>";
    echo "<button class='btn btn-primary' onclick=\"window.location.href='include.php?dir=paginas&file=del_usu'\">Sair</button>";
    echo '</div>';
else:
    // Caso não esteja logado, exibe o guia de uso
    echo "<div class='profile'>
            <div class='side-help-box'>
                <h5><i class='fas fa-question-circle'></i> Como usar o Achei na PUCPR?</h5>
                <ol class='small mt-3'>
                    <li>🔍 <strong>Pesquise</strong> se o item já foi publicado.</li>
                    <li>📝 <strong>Publique</strong> um novo item se não encontrar.</li>
                    <li>📍 Informe <strong>local, data e descrição</strong> do item.</li>
                    <li>🖼️ Adicione uma <strong>foto</strong> se quiser, para ajudar na identificação.</li>
                    <li>💬 Use os <strong>comentários</strong> para combinar devolução.</li>
                </ol>
            </div>
          </div>";
endif;
?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const sidebar = document.getElementById('sidebar');
            
            mobileMenuToggle.addEventListener('click', function() {
                sidebar.classList.toggle('active');
            });

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                if (window.innerWidth <= 768) {
                    if (!sidebar.contains(event.target) && !mobileMenuToggle.contains(event.target)) {
                        sidebar.classList.remove('active');
                    }
                }
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    sidebar.classList.remove('active');
                }
            });
        });
    </script>
</body>
</html>
