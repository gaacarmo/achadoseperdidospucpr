<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/conecta_db.php';

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $obj = conecta_db();
    $nome_usuario = $_POST['nome_usuario'];
    $senha = $_POST['senha'];

    // Primeiro ele tenta encontrar o usuário na tabela Moderador
    $stmt2 = $obj->prepare("SELECT moderador_usuario_nome, moderador_senha FROM Moderador WHERE moderador_usuario_nome = ?");
    $stmt2->bind_param("s", $nome_usuario);
    $stmt2->execute();
    $resultado2 = $stmt2->get_result();

    if ($resultado2 && $resultado2->num_rows > 0) {
        $user = $resultado2->fetch_assoc();
        if ($senha == $user['moderador_senha']) {
            $_SESSION['is_logged_adm'] = true;
            $_SESSION['moderador'] = $user['moderador_usuario_nome'];
            header("Location: ./index_adm.php");
            exit;
        } else {
            $error_message = "Senha incorreta";
        }
    } else {
        // caso nao seja moderador, tenta na tabela Usuario
        $stmt = $obj->prepare("SELECT nome_usuario, senha, usuario_id FROM Usuario WHERE nome_usuario = ?");
        $stmt->bind_param("s", $nome_usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado && $resultado->num_rows > 0) {
            $user = $resultado->fetch_assoc();
            if (password_verify($senha, $user['senha'])) {
                $_SESSION['is_logged_user'] = true;
                $_SESSION['usuario_id'] = $user['usuario_id'];
                $_SESSION['usuario'] = $user['nome_usuario'];
                header("Location: index.php");
                exit;
            } else {
                $error_message = "Senha incorreta";
            }
        } else {
            $error_message = "Nome de usuário não encontrado";
        }
    }
}

// Only output the form content
?>
<style>
    .btn-danger,
    .mobile-menu-toggle {
    background-color: #7b0828;
    border-color: #7b0828;
    color: white;
}
</style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="form-container">
                <h2 class="text-center mb-4">
                    <i class="fas fa-sign-in-alt"></i> Login
                </h2>

                <?php if ($error_message): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label for="nome_usuario" class="form-label">
                            <i class="fas fa-user"></i> Nome de Usuário <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                            class="form-control" 
                            id="nome_usuario" 
                            name="nome_usuario" 
                            required
                            placeholder="Digite seu nome de usuário">
                        <div class="invalid-feedback">Por favor, digite seu nome de usuário.</div>
                    </div>

                    <div class="mb-3">
                        <label for="senha" class="form-label">
                            <i class="fas fa-lock"></i> Senha <span class="text-danger">*</span>
                        </label>
                        <input type="password" 
                            class="form-control" 
                            id="senha" 
                            name="senha" 
                            required
                            placeholder="Digite sua senha">
                        <div class="invalid-feedback">Por favor, digite sua senha.</div>
                    </div>

                    <div class="mb-4">
                        <div class="form-text text-center">
                            <a href="include.php?dir=paginas&file=cadastro">
                                <i class="fas fa-user-plus"></i> Criar uma conta
                            </a>
                            <span class="mx-2">•</span>
                            <a href="include.php?dir=paginas&file=update">
                                <i class="fas fa-key"></i> Esqueceu a senha?
                            </a>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-sign-in-alt"></i> Entrar
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
</script>
