<?php
require_once __DIR__ . '/conecta_db.php';

if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['is_logged_user']) || $_SESSION['is_logged_user'] !== true || !isset($_SESSION['usuario_id'])) {
    header("Location: ../index.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$conexao = conecta_db();
$success_message = '';
$error_message = '';

$sql_select = "SELECT foto_perfil, nome, nome_usuario FROM Usuario WHERE usuario_id = ?";
$stmt = $conexao->prepare($sql_select);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();

if (!$usuario) {
    echo "<p style='color: red;'>Usuário não encontrado.</p>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomeCompleto = filter_input(INPUT_POST, 'nome_completo', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $nome_usuario = filter_input(INPUT_POST, 'nome_usuario', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $foto = $usuario['foto_perfil'];

    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/../uploads/';
        if (!file_exists($upload_dir)) mkdir($upload_dir, 0777, true);

        $file_extension = strtolower(pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($file_extension, $allowed_extensions)) {
            if ($_FILES['foto_perfil']['size'] <= 2 * 1024 * 1024) {
                $new_filename = uniqid('profile_') . '.' . $file_extension;
                $upload_path = $upload_dir . $new_filename;

                if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $upload_path)) {
                    $foto = 'uploads/' . $new_filename;
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
        $sql_update = "UPDATE Usuario SET foto_perfil = ?, nome = ?, nome_usuario = ? WHERE usuario_id = ?";
        $stmt = $conexao->prepare($sql_update);
        $stmt->bind_param("sssi", $foto, $nomeCompleto, $nome_usuario, $usuario_id);

        if ($stmt->execute()) {
            $success_message = "Dados atualizados com sucesso!";
            $_SESSION['usuario'] = $nome_usuario;
        } else {
            $error_message = "Erro ao atualizar: " . $conexao->error;
        }
        $stmt->close();
    }
}
$conexao->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Perfil - AcheiNaPuc</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f5f8fa;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
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
        .form-container {
            background-color: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            margin: 3rem auto;
            max-width: 600px;
        }
        .form-label {
            font-weight: 500;
            color: #0f1419;
        }
        .form-control {
            border-radius: 8px;
            padding: 0.75rem;
            border: 1px solid #ccc;
        }
        .form-control:focus {
            border-color: #7b0828;
            box-shadow: 0 0 0 0.2rem rgba(123, 8, 40, 0.25);
        }
        .profile-image-preview {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #eee;
        }
        .btn-danger {
            background-color: #7b0828;
            border-color: #7b0828;
        }
        .btn-danger:hover {
            background-color: #5a061f;
            border-color: #5a061f;
        }
        .btn-outline-secondary:hover {
            background-color: #f8f9fa;
            color: #0f1419;
        }
        .alert {
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2 class="text-center mb-4"><i class="fas fa-user-edit"></i> Editar Perfil</h2>

            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?= $success_message ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> <?= $error_message ?>
                </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                <div class="text-center mb-4">
                    <img src="<?= htmlspecialchars($usuario['foto_perfil'] ?? '../assets/default-avatar.png') ?>" 
                        alt="Foto de perfil" class="profile-image-preview" id="profile-preview">
                </div>

                <div class="mb-3">
                    <label for="foto_perfil" class="form-label"><i class="fas fa-camera"></i> Foto de Perfil</label>
                    <input type="file" class="form-control" id="foto_perfil" name="foto_perfil" accept="image/*" onchange="previewImage(this)">
                    <div class="form-text">Formatos permitidos: JPG, PNG, GIF. Máximo: 2MB.</div>
                </div>

                <div class="mb-3">
                    <label for="nome_completo" class="form-label"><i class="fas fa-user"></i> Nome Completo</label>
                    <input type="text" class="form-control" id="nome_completo" name="nome_completo" required 
                        value="<?= htmlspecialchars($usuario['nome'] ?? '') ?>" placeholder="Digite seu nome completo">
                    <div class="invalid-feedback">Por favor, insira seu nome completo.</div>
                </div>

                <div class="mb-4">
                    <label for="nome_usuario" class="form-label"><i class="fas fa-at"></i> Nome de Usuário</label>
                    <input type="text" class="form-control" id="nome_usuario" name="nome_usuario" required 
                        value="<?= htmlspecialchars($usuario['nome_usuario'] ?? '') ?>" placeholder="Digite seu nome de usuário">
                    <div class="invalid-feedback">Por favor, insira seu nome de usuário.</div>
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

    <script>
        // Preview da imagem
        function previewImage(input) {
            const preview = document.getElementById('profile-preview');
            const file = input.files[0];
            if (file) {
                if (file.size > 2 * 1024 * 1024) {
                    alert('A imagem deve ter no máximo 2MB.');
                    input.value = '';
                    return;
                }
                const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Formato inválido. Use JPG, PNG ou GIF.');
                    input.value = '';
                    return;
                }
                const reader = new FileReader();
                reader.onload = e => preview.src = e.target.result;
                reader.readAsDataURL(file);
            }
        }

        // Validação do formulário
        (() => {
            'use strict';
            const forms = document.querySelectorAll('.needs-validation');
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', e => {
                    if (!form.checkValidity()) {
                        e.preventDefault();
                        e.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>
</body>
</html>