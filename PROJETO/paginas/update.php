<?php
require_once 'conecta_db.php';

// Variáveis para armazenar erros específicos
$erro_email = '';
$erro_nova_senha = '';
$erro_confirmar_senha = '';
$sucesso = '';

// Verifica se o formulário foi enviado
if (isset($_POST['nova_senha']) && isset($_POST['confirmar_senha']) && isset($_POST['email'])) {
    $email = $_POST['email'];
    $nova_senha = $_POST['nova_senha'];
    $hash_nova_senha = password_hash($nova_senha, PASSWORD_DEFAULT);
    $confirmar_senha = $_POST['confirmar_senha'];
    $validado = true;

    // Verifica se as senhas coincidem
    if ($nova_senha !== $confirmar_senha) {
        $erro_confirmar_senha = "As senhas digitadas não coincidem.";
        $validado = false;
    }

    // Conecta ao banco de dados apenas se as senhas coincidirem
    if ($validado) {
        $obj = conecta_db();

        // Verifica se o email existe e recupera a senha atual
        $query_check = "SELECT senha FROM Usuario WHERE email = ?";
        $stmt = $obj->prepare($query_check);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado_check = $stmt->get_result();

        if ($resultado_check->num_rows == 0) {
            $erro_email = "Email não encontrado."; // Verifica se o email existe 
            $validado = false;
        } else {
            $usuario = $resultado_check->fetch_assoc();
            $senha_atual = $usuario['senha'];

            // Verifica se a nova senha é igual à senha atual (agora comparação direta)
            if ($nova_senha === $senha_atual) {
                $erro_nova_senha = "A nova senha não pode ser igual à senha atual.";
                $validado = false;
            }
        }
    }

    // Se tudo estiver validado, atualiza a senha (sem criptografia)
    if ($validado) {
        $query = "UPDATE Usuario SET senha = ? WHERE email = ?";
        $stmt = $obj->prepare($query);
        $stmt->bind_param("ss", $hash_nova_senha, $email);
        $resultado = $stmt->execute();

        if ($resultado) {
            $sucesso = "Senha atualizada com sucesso! Redirecionando para a página de login...";
            header("Refresh: 3; url=include.php?dir=paginas&file=login");
        } else {
            $erro_geral = "Erro ao atualizar a senha.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha - AcheiNaPuc</title>
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
        }
    </style>
</head>
<body>
    <button class="mobile-menu-toggle" id="mobileMenuToggle">
        <i class="fas fa-bars"></i>
    </button>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="form-container">
                    <h2 class="text-center mb-4">
                        <i class="fas fa-key"></i> Redefinir Senha
                    </h2>
                    
                    <?php if ($sucesso): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> <?php echo $sucesso; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($erro_geral)): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i> <?php echo $erro_geral; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope"></i> Email
                            </label>
                            <input type="email" class="form-control <?php echo $erro_email ? 'is-invalid' : ''; ?>" 
                                id="email" name="email" required 
                                value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                                placeholder="Digite seu email">
                            <?php if ($erro_email): ?>
                                <div class="invalid-feedback"><?php echo $erro_email; ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="nova_senha" class="form-label">
                                <i class="fas fa-lock"></i> Nova Senha
                            </label>
                            <input type="password" class="form-control <?php echo $erro_nova_senha ? 'is-invalid' : ''; ?>" 
                                id="nova_senha" name="nova_senha" required
                                placeholder="Digite sua nova senha">
                            <?php if ($erro_nova_senha): ?>
                                <div class="invalid-feedback"><?php echo $erro_nova_senha; ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-4">
                            <label for="confirmar_senha" class="form-label">
                                <i class="fas fa-lock"></i> Confirmar Nova Senha
                            </label>
                            <input type="password" class="form-control <?php echo $erro_confirmar_senha ? 'is-invalid' : ''; ?>" 
                                id="confirmar_senha" name="confirmar_senha" required
                                placeholder="Confirme sua nova senha">
                            <?php if ($erro_confirmar_senha): ?>
                                <div class="invalid-feedback"><?php echo $erro_confirmar_senha; ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-save"></i> Atualizar Senha
                            </button>
                            <a href="include.php?dir=paginas&file=login" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Voltar para Login
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