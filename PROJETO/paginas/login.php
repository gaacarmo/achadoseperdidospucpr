<?php
require_once 'paginas/conecta_db.php';

// Inicia a sessão (caso ainda não esteja iniciada)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $obj = conecta_db();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nome_usuario = $_POST['nome_usuario'];
        $senha = $_POST['senha'];

        //  Primeiro ele tenta encontrar o usuário na tabela Moderador
        $stmt2 = $obj->prepare("SELECT moderador_usuario_nome, moderador_senha FROM Moderador WHERE moderador_usuario_nome = ?");
        $stmt2->bind_param("s", $nome_usuario);
        $stmt2->execute();
        $resultado2 = $stmt2->get_result();

        if ($resultado2 && $resultado2->num_rows > 0) {
            $user = $resultado2->fetch_assoc();
            if ($senha == $user['moderador_senha']) {
                $_SESSION['is_logged_adm'] = true;
                $_SESSION['moderador'] = $user['moderador_usuario_nome'];
                header("Location: index_adm.php");
                exit;
            } else {
                echo "<div class='alert alert-danger'>Senha incorreta</div>";
                exit;
            }
        }

        // caso nao seja moderador, tenta na tabela Usuario
        $stmt = $obj->prepare("SELECT nome_usuario, senha, usuario_id FROM Usuario WHERE nome_usuario = ?");
        $stmt->bind_param("s", $nome_usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado && $resultado->num_rows > 0) {
            $user = $resultado->fetch_assoc();
            if ($senha == $user['senha']) {
                $_SESSION['is_logged_user'] = true;
                $_SESSION['usuario_id'] = $user['usuario_id']; // Armazena o ID do usuário na sessão
                $_SESSION['usuario'] = $user['nome_usuario'];
                header("Location: index.php");
                exit;
            } else {
                echo "<div class='alert alert-danger'>Senha incorreta</div>";
            }
        } else {
            echo "<div class='alert alert-warning'>Nome de usuário não encontrado</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - AcheiNaPuc</title>
    <link rel="stylesheet" href="../CSS/login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<style>
    .side-help-box {
    position: fixed;
    top: 80px;
    
    width: 400px;

    border: 1px solid #dee2e6;
    border-radius: 12px;
    padding: 30px;
 
    font-size: 30px; 
  
    z-index: 999;
}
</style>
<body>
    <div class="container">
        <h2 class="text-center mb-4">Login</h2>

        <form method="POST" class="needs-validation" novalidate>
            <div class="form-group">
                <label for="nomeUsuario">Nome de usuário:</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="text" class="form-control" id="nomeUsuario" name="nome_usuario" required>
                </div>
            </div>

            <div class="form-group mb-3">
                <label for="senha">Senha:</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" class="form-control" id="senha" name="senha" required>
                </div>
            </div>

            <div class="d-flex flex-column gap-2 mb-3">
                <a href="include.php?dir=paginas&file=cadastro" class="text-decoration-none">
                    <i class="fas fa-user-plus"></i> Cadastre-se
                </a>
                <a href="include.php?dir=paginas&file=update" class="text-decoration-none">
                    <i class="fas fa-key"></i> Esqueci minha senha
                </a>
            </div>

            <button type="submit" class="btn btn-danger">
                <i class="fas fa-sign-in-alt"></i> Entrar
            </button>
        </form>
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
    </script>
</body>
</html>
