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
        $stmt->bind_param("ss", $nova_senha, $email);
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
    <title>Esqueceu sua senha</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        .error-text {
            color: red;
            font-size: 0.8rem;
            margin-top: 0.25rem;
        }
        .success-message {
            margin-top: 20px;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .btn {
            color: white;
            background-color: #a52834;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-center">Redefinir Senha</h2>
                    </div>
                    <div class="card-body">
                        <?php if ($sucesso): ?>
                            <div class="alert alert-success success-message">
                                <?php echo $sucesso; ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($erro_geral)): ?>
                            <div class="alert alert-danger">
                                <?php echo $erro_geral; ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" class="form-control <?php echo $erro_email ? 'is-invalid' : ''; ?>" 
                                    id="email" name="email" required 
                                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                                <?php if ($erro_email): ?>
                                    <div class="error-text"><?php echo $erro_email; ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="form-group">
                                <label for="nova_senha">Nova Senha:</label>
                                <input type="password" class="form-control <?php echo $erro_nova_senha ? 'is-invalid' : ''; ?>" 
                                    id="nova_senha" name="nova_senha" required>
                                <?php if ($erro_nova_senha): ?>
                                    <div class="error-text"><?php echo $erro_nova_senha; ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="form-group">
                                <label for="confirmar_senha">Confirmar Nova Senha:</label>
                                <input type="password" class="form-control <?php echo $erro_confirmar_senha ? 'is-invalid' : ''; ?>" 
                                    id="confirmar_senha" name="confirmar_senha" required>
                                <?php if ($erro_confirmar_senha): ?>
                                    <div class="error-text"><?php echo $erro_confirmar_senha; ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <button type="submit" class="btn  btn-block">Atualizar Senha</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>