<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'paginas/conecta_db.php';
$obj = conecta_db();

$query = "SELECT p.*, u.nome_usuario, foto_perfil
        FROM Postagem p 
        LEFT JOIN Usuario u ON p.id_usuario = u.usuario_id 
        ORDER BY p.postagem_id DESC";
$resultado = $obj->query($query);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AcheiNaPuc - Início</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f5f8fa;
            color: #0f1419;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: 280px;
            background-color: white;
            padding: 1.5rem;
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            transition: transform 0.3s ease;
        }
        .sidebar .logo-nav{
            width: 50px;
            height: 50px;
            color: #7b0828;
            
        }
        .sidebar .title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #7b0828;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f5f8fa;
        }
        .nav-item {
            margin-bottom: 1rem;
        }
        .nav-link {
            display: flex;
            align-items: center;
            color: #0f1419;
            text-decoration: none;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            transition: background-color 0.2s;
        }
        .nav-link:hover {
            background-color: #f5f8fa;
            color: #7b0828;
        }
        .nav-link i, .nav-link img {
            margin-right: 0.75rem;
            width: 20px;
            height: 20px;
        }
        .publicar-btn {
            position: absolute;
            bottom: 2rem;
            left: 1.5rem;
            right: 1.5rem;
            background-color: #7b0828;
            color: white;
            border: none;
            padding: 0.75rem;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .publicar-btn:hover {
            background-color: #5a061f;
        }
        .content {
            margin-left: 280px;
            padding: 2rem;
            min-height: 100vh;
        }
        .post-container {
            background-color: white;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        }
        .post-container img {
            width: 100%;
            height: 400px;
            object-fit: contain;
            border-radius: 8px;
            margin-bottom: 1rem;
            background-color: #f8f9fa;
            padding: 0.5rem;
        }
        .post-content {
            padding: 1rem 0;
        }
        .post-content h5 {
            color: #0f1419;
            margin-bottom: 1rem;
            font-size: 1.25rem;
        }
        .post-content p {
            color: #536471;
            margin-bottom: 0.5rem;
            font-size: 1rem;
            line-height: 1.5;
        }
        .post-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #eee;
        }
        .post-actions button {
            background: none;
            border: none;
            color: #536471;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 0.9rem;
        }
        .post-actions button:hover {
            background-color: #f5f8fa;
            color: #7b0828;
        }
        .post-actions button i {
            margin-right: 0.5rem;
        }
        .badge {
            font-size: 0.8rem;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            margin-left: 0.5rem;
        }
        .profile {
            position: fixed;
            right: 2rem;
            top: 2rem;
            background-color: white;
            padding: 1.5rem;
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            max-width: 300px;
        }
          .profile img {
            border-radius: 50%;
            margin-bottom: 1rem;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #eee;
        }
        .profile h3 {
            margin: 0;
            font-size: 1.25rem;
            color: #0f1419;
        }
        .profile p {
            color: #536471;
            margin: 0.5rem 0 1rem;
        }
        .profile .btn {
            width: 100%;
          
            margin-bottom: 0.5rem;
        }
        
        .side-help-box {
            background-color: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            margin-top: 1rem;
        }
        .side-help-box h5 {
            color: #0f1419;
            margin-bottom: 1rem;
        }
        .side-help-box ol {
            color: #536471;
            padding-left: 1.5rem;
        }
        .side-help-box li {
            margin-bottom: 0.5rem;
        }
        .mobile-menu-toggle {
            display: none;
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1001;
            background: #7b0828;
            color: white;
            border: none;
            padding: 0.5rem;
            border-radius: 8px;
            cursor: pointer;
        }

        .fa-check-circle.verified {
            color: #28a745;
            margin-left: 5px;
            font-size: 0.9em;
        }

        @media (max-width: 1024px) {
            .profile {
                position: static;
                margin: 2rem auto;
                max-width: 100%;
            }
        }
        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: block;
            }
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.active {
                transform: translateX(0);
            }
            .content {
                margin-left: 0;
                padding: 1rem;
            }
            .profile {
                margin: 1rem;
            }
            .post-container {
                margin: 1rem;
                padding: 1rem;
            }
            .post-container img {
                height: 300px;
            }
            .post-content h5 {
                font-size: 1.1rem;
            }
            .post-content p {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <button class="mobile-menu-toggle" id="mobileMenuToggle">
        <i class="fas fa-bars"></i>
    </button>

    <div class="sidebar" id="sidebar">
        <div>
            <div class="title"><img src="./images/Group.svg"  class="logo-nav"alt="">Achei na PUCPR</div>
            <div class="nav-item">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-home"></i>
                     Início
                </a>
            </div>
            <?php if(isset($_SESSION['is_logged_user']) && $_SESSION['is_logged_user'] === true): ?>
            <div class="nav-item">
                <a class="nav-link" href="include.php?dir=paginas&file=editar">
                    <i class="fas fa-user"></i>
                     Perfil
                </a>
            </div>
            <?php else: ?>
            <div class="nav-item">
                <a class="nav-link" href="include.php?dir=paginas&file=login">
                    <i class="fas fa-sign-in-alt"></i>
                     Entrar
                </a>
            </div>
            
            <?php endif; ?>
            <div class="nav-item">
                <a class="nav-link" href="include.php?dir=paginas&file=publicar">
                    <i class="fas fa-plus"></i>
                     Publicar
                </a>
            </div>
        </div>
        <button class="publicar-btn" onclick="window.location.href='include.php?dir=paginas&file=publicar'">
            <i class="fas fa-plus"></i> Publicar
        </button>
    </div>

    <div class="content">
        <div class="container mt-4">
            <h2 class="mb-4">
                <i class="fas fa-newspaper"></i> Postagens Recentes
            </h2>

            <?php 
            if($resultado->num_rows > 0):
                while ($linha = $resultado->fetch_assoc()): ?>
                    <div class="post-container">
                        <?php if (!empty($linha['postagem_image'])): ?>
                            <img src="<?= htmlspecialchars($linha['postagem_image']) ?>" alt="Imagem do objeto">
                        <?php endif; ?>
                        
                        <div class="post-content">
                            <h5>
                                <?= htmlspecialchars($linha['postagem_nome']) ?>
                                <?php if(!empty($linha['id_admin'])): ?>
                                    <i class="fas fa-check-circle text-primary" title="Postagem verificada"></i>
                                <?php endif; ?>
                                
                                <span class="badge bg-<?= $linha['postagem_usuario_tipo'] === 'Achei' ? 'success' : 'danger' ?>">
                                    <?= htmlspecialchars($linha['postagem_usuario_tipo']) ?>
                                </span>
                            </h5>
                            <p><?= htmlspecialchars($linha['postagem_descricao']) ?></p>
                            <p>
                                <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($linha['postagem_local']) ?> |
                                <i class="fas fa-calendar"></i> <?= htmlspecialchars($linha['postagem_data']) ?>
                            </p>
                            <?php if (!empty($linha['nome_usuario'])): ?>
                                <p class="text-muted mb-1">
                                    <i class="fas fa-user"></i> Postado por: <?= htmlspecialchars($linha['nome_usuario']) ?>
                                </p>
                            <?php endif; ?>
                            <div class="post-actions">
                                <button>
                                    <i class="far fa-thumbs-up"></i> Curtir
                                </button>
                                <button>
                                    <i class="far fa-comment"></i> Comentar
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Nenhuma postagem encontrada
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php
    if(isset($_SESSION['is_logged_user']) && $_SESSION['is_logged_user'] === true) {
        $usuario_id = $_SESSION['usuario_id']; // Ajuste para a sua variável de sessão
        $sqlUser = "SELECT foto_perfil FROM Usuario WHERE usuario_id = ?";
        $stmt = $obj->prepare($sqlUser);
        $stmt->bind_param('i', $usuario_id);
        $stmt->execute();
        $resultUser = $stmt->get_result();
        $userData = $resultUser->fetch_assoc();
        $fotoPerfil = $userData['foto_perfil'] ?? 'default.jpg';

        echo "<div class='profile'>";
        echo "<img src='{$fotoPerfil}' alt='Foto de perfil'>";
        echo "<h3>{$_SESSION['usuario']}</h3>";
        echo "<p>@{$_SESSION['usuario']}</p>";
        echo "<button class='btn btn-danger' onclick=\"window.location.href='include.php?dir=paginas&file=editar'\">Editar Perfil</button>";
        echo "<button class='btn btn-outline-secondary' onclick=\"window.location.href='include.php?dir=paginas&file=del_usu'\">Sair</button>";
        echo '</div>';
    } else {
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
    }
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
