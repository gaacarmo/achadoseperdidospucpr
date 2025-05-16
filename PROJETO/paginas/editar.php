<?php
require_once 'paginas/conecta_db.php';

// Inicia a sessão (caso ainda não esteja iniciada)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado e se o usuario_id está definido na sessão
if (!isset($_SESSION['is_logged_user']) || $_SESSION['is_logged_user'] !== true || !isset($_SESSION['usuario_id'])) {
    // Redireciona para a página de login e exibe uma mensagem de erro
    header("Location: include.php?dir=paginas&file=login");
    exit();
}

// Obtém o ID do usuário da sessão
$usuario_id = $_SESSION['usuario_id'];

// Conecta ao banco de dados
$conexao = conecta_db();

// Atualiza os dados se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Filtra e obtém os dados do formulário para evitar SQL injection
    $foto = filter_input(INPUT_POST, 'foto_perfil', FILTER_SANITIZE_URL);
    $nomeCompleto = filter_input(INPUT_POST, 'nome_completo', FILTER_SANITIZE_STRING);
    $nome_usuario = filter_input(INPUT_POST, 'nome_usuario', FILTER_SANITIZE_STRING);
    $curso = filter_input(INPUT_POST, 'curso', FILTER_SANITIZE_STRING);

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
        echo "<p style='color: green;'>Dados atualizados com sucesso!</p>";
    } else {
        echo "<p style='color: red;'>Erro ao atualizar: " . $conexao->error . "</p>";
    }

    // Fecha a declaração
    $stmt->close();
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
    <style>
        body {
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f5f8fa;
            color: #0f1419;
            overflow-x: hidden;
        }
        .form-container {
            background-color: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            margin: 2rem auto;
            max-width: 800px;
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
        .btn-danger {
            background-color: #7b0828;
            border-color: #7b0828;
        }
        .btn-danger:hover {
            background-color: #5a061f;
            border-color: #5a061f;
        }
        .alert {
            border-radius: 8px;
        }
        .profile-image-preview {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 1.5rem;
            display: block;
            border: 3px solid #7b0828;
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
        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: block;
            }
            .form-container {
                margin: 1rem;
                padding: 1rem;
            }
            .profile-image-preview {
                width: 120px;
                height: 120px;
            }
        }
    </style>
</head>
<body>
    <button class="mobile-menu-toggle" id="mobileMenuToggle">
        <i class="fas fa-bars"></i>
    </button>

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

        // Image preview
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profile-preview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            
            mobileMenuToggle.addEventListener('click', function() {
                // Add your mobile menu toggle logic here if needed
            });
        });
    </script>
</body>
</html>
