<?php
// Inclui o arquivo com a função de conexão
include('conecta_db.php');

// Verifica se o formulário foi enviado
if (isset($_POST['nova_senha']) && isset($_POST['confirmar_senha']) && isset($_POST['email'])) {
    $email = $_POST['email'];
    $nova_senha = $_POST['nova_senha'];
    $confirmar_senha = $_POST['confirmar_senha'];

    // Verifica se as senhas coincidem
    if ($nova_senha !== $confirmar_senha) {
        echo "<span class='alert alert-danger'>
            <h5>As senhas digitadas não coincidem. Tente novamente.</h5>
            </span>";
        exit();
    }

    // Conecta ao banco de dados
    $obj = conecta_db();

    // Verifica se o email existe e recupera a senha atual
    $query_check = "SELECT senha FROM Usuario WHERE email = '".$email."'";
    $resultado_check = $obj->query($query_check);

    if ($resultado_check->num_rows == 0) {
        echo "<span class='alert alert-danger'>
            <h5>Email não encontrado. Verifique o email digitado.</h5>
            </span>";
        exit();
    }

    $usuario = $resultado_check->fetch_assoc();
    $senha_atual = $usuario['senha'];

    // Verifica se a nova senha é igual à senha atual
    if ($nova_senha === $senha_atual) {
        echo "<span class='alert alert-danger'>
            <h5>A nova senha não pode ser igual à senha atual.</h5>
            </span>";
        exit();
    }

    // Atualiza a senha no banco de dados
    $query = "
        UPDATE Usuario 
        SET senha = '".$nova_senha."' 
        WHERE email = '".$email."'
    ";

    $resultado = $obj->query($query);

    if ($resultado) {
        echo "<span class='alert alert-success'>
            <h5>Senha atualizada com sucesso! Redirecionando para a página de login...</h5>
            </span>";
        header("Refresh: 3; url=login.php"); // Redireciona após 3 segundos
    } else {
        echo "<span class='alert alert-danger'>
            <h5>Erro ao atualizar a senha.</h5>
            </span>";
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Esqueceu sua senha</h2>
        <form method="POST">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
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