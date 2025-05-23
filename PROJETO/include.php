<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Function to check if headers can be sent
function can_send_headers() {
    return !headers_sent();
}

// Function to include a file with proper path handling
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

// Get the content first
$page_content = '';
if (isset($_GET['dir']) && isset($_GET['file'])) {
    $page_content = include_file($_GET['dir'], $_GET['file']);
}

// Only output HTML if we haven't redirected
if (can_send_headers()) {
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>AcheiNaPuc</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        .sidebar .title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #7b0828;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f5f8fa;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            color: #0f1419;
            text-decoration: none;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
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
        .back-button {
            background: none;
            border: none;
            color: #7b0828;
            font-size: 1rem;
            cursor: pointer;
            padding: 0.5rem 0;
            margin-bottom: 1rem;
        }
        .back-button:hover {
            text-decoration: underline;
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
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 1rem;
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
        /* Login form styles */
        .form-container {
            background-color: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            margin: 2rem auto;
            max-width: 500px;
        }
        .form-label {
            font-weight: 500;
            color: #0f1419;
        }
        .form-control {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            border: 1px solid #ddd;
        }
        .form-control:focus {
            border-color: #7b0828;
            box-shadow: 0 0 0 0.2rem rgba(123, 8, 40, 0.25);
        }
        .form-text {
            color: #536471;
        }
        .form-text a {
            color: #7b0828;
            text-decoration: none;
        }
        .form-text a:hover {
            text-decoration: underline;
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
        }
    </style>
</head>
<body>
    <button class="mobile-menu-toggle" id="mobileMenuToggle">
        <i class="fas fa-bars"></i>
    </button>

    <div class="sidebar" id="sidebar">
        <div>
            <div class="title">Achei na PUCPR</div>
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
                    Editar
                </a>
            </div>
            <?php else: ?>
            <div class="nav-item">
                <a class="nav-link" href="include.php?dir=paginas&file=login">
                    <i class="fas fa-sign-in-alt"></i>
                     Login
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
        <button onclick="window.history.back()" class="back-button">
            <i class="fas fa-arrow-left"></i> Voltar
        </button>
        <?php echo $page_content; ?>
    </div>

    <?php
    if(isset($_SESSION['is_logged_user']) && $_SESSION['is_logged_user'] === true) {
        echo "<div class='profile'>";
        echo "<img src='https://via.placeholder.com/80' alt='Foto de perfil'>";
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
<?php
}
?>
