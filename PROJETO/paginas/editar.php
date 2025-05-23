<?php
require_once __DIR__ . '/conecta_db.php';

// Inicia a sessão (caso ainda não esteja iniciada)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado e se o usuario_id está definido na sessão
if (!isset($_SESSION['is_logged_user']) || $_SESSION['is_logged_user'] !== true || !isset($_SESSION['usuario_id'])) {
    // Redireciona para a página de login e exibe uma mensagem de erro
    header("Location: ../index.php");
    exit();
}

// Obtém o ID do usuário da sessão
$usuario_id = $_SESSION['usuario_id'];

// Conecta ao banco de dados
$conexao = conecta_db();

$success_message = '';
$error_message = '';

// Atualiza os dados se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Filtra e obtém os dados do formulário para evitar SQL injection
    $nomeCompleto = filter_input(INPUT_POST, 'nome_completo', FILTER_SANITIZE_STRING);
    $nome_usuario = filter_input(INPUT_POST, 'nome_usuario', FILTER_SANITIZE_STRING);
    
    // Handle file upload
    $foto = $usuario['foto_perfil']; // Keep existing photo by default
    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/../uploads/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_extension = strtolower(pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION));
        $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');
        
        if (in_array($file_extension, $allowed_extensions)) {
            if ($_FILES['foto_perfil']['size'] <= 2 * 1024 * 1024) { // 2MB limit
                $new_filename = uniqid('profile_') . '.' . $file_extension;
                $upload_path = $upload_dir . $new_filename;
                
                if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $upload_path)) {
                    $foto = 'achadoseperdidospucpr/PROJETO/uploads/' . $new_filename;
                } else {
                    $error_message = "Erro ao fazer upload da imagem.";
                }
            } else {
                $error_message = "A imagem deve ter no máximo 2MB.";
            }
        } else {
            $error_message = "Formato de arquivo não permitido. Use JPG, PNG ou GIF.";
        }
    }

    if (empty($error_message)) {
        // Prepara a consulta SQL para atualização
        $sql_update = "UPDATE Usuario SET
                        foto_perfil = ?,
                        nome = ?,
                        nome_usuario = ?
                        WHERE usuario_id = ?";
        $stmt = $conexao->prepare($sql_update);

        // Vincula os parâmetros de forma segura
        $stmt->bind_param("sssi", $foto, $nomeCompleto, $nome_usuario, $usuario_id);

        // Executa a consulta
        if ($stmt->execute()) {
            $success_message = "Dados atualizados com sucesso!";
            // Update session username if it was changed
            $_SESSION['usuario'] = $nome_usuario;
        } else {
            $error_message = "Erro ao atualizar: " . $conexao->error;
        }

        // Fecha a declaração
        $stmt->close();
    }
}

// Busca os dados do usuário para preencher o formulário
$sql_select = "SELECT foto_perfil, nome, nome_usuario FROM Usuario WHERE usuario_id = ?";
$stmt = $conexao->prepare($sql_select);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();

// Se o usuário não for encontrado
if (!$usuario) {
    echo "<p style='color: red;'>Usuário não encontrado.</p>";
    exit();
}

// Fecha a declaração e a conexão
$stmt->close();
$conexao->close();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil - AcheiNaPuc</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   
</head>
<body>
    <button class="mobile-menu-toggle" id="mobileMenuToggle">
        <i class="fas fa-bars"></i>
    </button>

    <div class="sidebar" id="sidebar">
        <div class="title">
            <i class="fas fa-search"></i> AcheiNaPuc
        </div>
        <nav>
            <div class="nav-item">
                <a href="../index.php" class="nav-link">
                    <i class="fas fa-home"></i> Início
                </a>
            </div>
            <div class="nav-item">
                <a href="include.php?dir=paginas&file=publicar" class="nav-link">
                    <i class="fas fa-plus-circle"></i> Publicar
                </a>
            </div>
            <div class="nav-item">
                <a href="include.php?dir=paginas&file=editar" class="nav-link">
                    <i class="fas fa-user-edit"></i> Editar Perfil
                </a>
            </div>
            <div class="nav-item">
                <a href="include.php?dir=paginas&file=del_usu" class="nav-link">
                    <i class="fas fa-sign-out-alt"></i> Sair
                </a>
            </div>
        </nav>
    </div>

    <div class="content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="form-container">
                        <h2 class="text-center mb-4">
                            <i class="fas fa-user-edit"></i> Editar Perfil
                        </h2>

                        <?php if (isset($success_message)): ?>
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($error_message)): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" class="needs-validation" novalidate enctype="multipart/form-data">
                            <div class="text-center mb-4">
                                <img src="<?php echo htmlspecialchars($usuario['foto_perfil'] ?? '../assets/default-avatar.png'); ?>" 
                                     alt="Foto de perfil" 
                                     class="profile-image-preview" 
                                     id="profile-preview">
                            </div>

                            <div class="mb-3">
                                <label for="foto_perfil" class="form-label">
                                    <i class="fas fa-camera"></i> Foto de Perfil
                                </label>
                                <input type="file" 
                                       class="form-control" 
                                       id="foto_perfil" 
                                       name="foto_perfil" 
                                       accept="image/*"
                                       onchange="previewImage(this)">
                                <div class="form-text">Formatos aceitos: JPG, PNG, GIF (Máx. 2MB)</div>
                            </div>

                            <div class="mb-3">
                                <label for="nome_completo" class="form-label">
                                    <i class="fas fa-user"></i> Nome Completo
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="nome_completo" 
                                       name="nome_completo" 
                                       value="<?php echo htmlspecialchars($usuario['nome'] ?? ''); ?>" 
                                       required
                                       placeholder="Digite seu nome completo">
                                <div class="invalid-feedback">Por favor, digite seu nome completo.</div>
                            </div>

                            <div class="mb-4">
                                <label for="nome_usuario" class="form-label">
                                    <i class="fas fa-at"></i> Nome de Usuário
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="nome_usuario" 
                                       name="nome_usuario" 
                                       value="<?php echo htmlspecialchars($usuario['nome_usuario'] ?? ''); ?>" 
                                       required
                                       placeholder="Digite seu nome de usuário">
                                <div class="invalid-feedback">Por favor, digite seu nome de usuário.</div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-save"></i> Salvar Alterações
                                </button>
                                <a href="index.php" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left"></i> Voltar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Form validation
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()

        // Image preview functionality
        function previewImage(input) {
            const preview = document.getElementById('profile-preview');
            const file = input.files[0];
            
            if (file) {
                // Check file size (2MB limit)
                if (file.size > 2 * 1024 * 1024) {
                    alert('A imagem deve ter no máximo 2MB.');
                    input.value = '';
                    return;
                }
                
                // Check file type
                const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Formato de arquivo não permitido. Use JPG, PNG ou GIF.');
                    input.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        }

        // Mobile menu toggle
        document.getElementById('mobileMenuToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
        });
    </script>
</body>
</html>
