<?php
require_once 'conecta_db.php';

$erro = '';
$sucesso = '';

if (isset($_POST['nova_senha']) && isset($_POST['confirmar_senha']) && isset($_POST['email'])) {
    $email = $_POST['email'];
    $nova_senha = $_POST['nova_senha'];
    $confirmar_senha = $_POST['confirmar_senha'];

    // Verifica se as senhas coincidem
    if ($nova_senha !== $confirmar_senha) {
        $erro = "As senhas digitadas não coincidem. Tente novamente.";
    } else {
        // Conecta ao banco de dados
        $obj = conecta_db();

        // Verifica se o email existe e recupera a senha atual
        $query_check = "SELECT senha FROM Usuario WHERE email = ?";
        $stmt = $obj->prepare($query_check);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado_check = $stmt->get_result();

        if ($resultado_check->num_rows == 0) {
            $erro = "Email não encontrado. Verifique o email digitado.";
        } else {
            $usuario = $resultado_check->fetch_assoc();
            $senha_atual = $usuario['senha'];

            // Verifica se a nova senha é igual à senha atual
            if ($nova_senha === $senha_atual) {
                $erro = "A nova senha não pode ser igual à senha atual.";
            } else {
                // Atualiza a senha no banco de dados
                $query = "UPDATE Usuario SET senha = ? WHERE email = ?";
                $stmt = $obj->prepare($query);
                $stmt->bind_param("ss", $nova_senha, $email);
                $resultado = $stmt->execute();

                if ($resultado) {
                    $sucesso = "Senha atualizada com sucesso! Redirecionando para a página de login...";
                    echo "<script>
                            setTimeout(function() {
                                window.location.href = 'login.php';
                            }, 3000);
                          </script>";
                } else {
                    $erro = "Erro ao atualizar a senha.";
                }
            }
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
        .alert {
            margin-top: 20px;
        }
        .error-message {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Esqueceu sua senha</h2>
        
        <?php if ($erro): ?>
            <div class="alert alert-danger"><?php echo $erro; ?></div>
            <script>alert("<?php echo $erro; ?>");</script>
        <?php endif; ?>
        
        <?php if ($sucesso): ?>
            <div class="alert alert-success"><?php echo $sucesso; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="nova_senha">Nova Senha:</label>
                <input type="password" class="form-control" id="nova_senha" name="nova_senha" required>
            </div>
            <div class="form-group">
                <label for="confirmar_senha">Confirmar Nova Senha:</label>
                <input type="password" class="form-control" id="confirmar_senha" name="confirmar_senha" required>
            </div>
            <button type="submit" class="btn btn-primary">Atualizar Senha</button>
        </form>
    </div>
</body>
</html>