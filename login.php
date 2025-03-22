<?php
session_start();

include("conecta_db");

$obj = conect_db();

// Corrigido o SQL
$stmt = $obj->prepare("SELECT nome_usuario, senha FROM Usuario WHERE nome_usuario = ?");
$stmt->bind_param("s", $_POST['nomeUsuario']);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $user = $resultado->fetch_assoc();

    // Aqui corrigido para $_POST['confirmar_senha']
    if ($_POST['confirmar_senha'] == $user['senha']) {
        $_SESSION['is_logged'] = true;
        header("location: index.php");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Senha incorreta</div>";
    }
} else {
    echo "<div class='alert alert-warning'>Nome de usuário não encontrado</div>";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        .alert {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>

        <form method="POST">
            <div class="form-group">
                <label for="email">Nome de usuário:</label>
                <input type="text" class="form-control" id="nomeUsuario" name="nomeUsuario" required>
            </div>

            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" class="form-control" id="senha" name="senha" required>
            </div>

            <button type="submit" class="btn btn-primary">Entrar</button>

        </form>
    </div>
</body>
</html>