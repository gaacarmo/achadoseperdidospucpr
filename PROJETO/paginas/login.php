<?php
require_once 'paginas/conecta_db.php';
session_start();

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
                $_SESSION['is_logged'] = true;
                $_SESSION['moderador'] = $user['moderador_nome_usuario'];
                header("Location: index_adm.php");
                exit;
            } else {
                echo "<div class='alert alert-danger'>Senha incorreta</div>";
                exit;
            }
        }
    
        // caso nao seja moderador, tenta na tabela Usuario
        $stmt = $obj->prepare("SELECT nome_usuario, senha FROM Usuario WHERE nome_usuario = ?");
        $stmt->bind_param("s", $nome_usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();
    
        if ($resultado && $resultado->num_rows > 0) {
            $user = $resultado->fetch_assoc();
            if ($senha == $user['senha']) {
                $_SESSION['is_logged'] = true;
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
                <label for="nomeUsuario">Nome de usuário:</label>
                <input type="text" class="form-control" id="nomeUsuario" name="nome_usuario" required>
            </div>

            <div class="form-group mb-3">
                <label for="senha">Senha:</label>
                <input type="password" class="form-control" id="senha" name="senha" required>
            </div>

            <div>
                <a href="include.php?dir=paginas&file=update">Esqueci minha senha</a>
            </div>

            <button type="submit" class="btn btn-danger w-100">Entrar</button>
        </form>
    </div>
</body>
</html>