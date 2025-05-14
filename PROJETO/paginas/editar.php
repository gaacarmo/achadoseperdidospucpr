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
    <title>Editar Perfil</title>
     <link rel="stylesheet" href="./CSS/editar.css">
     <style></style>
</head>
<body>
    <div class="form-container">
        <h2>Editar Perfil</h2>
        <form method="POST">
            <div class="form-group">
                <label for="foto_perfil">Foto de perfil:</label>
                <input type="file" id="foto_perfil" name="foto_perfil" value="<?= htmlspecialchars($usuario['foto_perfil'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="nome_completo">Nome Completo:</label>
                <input type="text" id="nome_completo" name="nome_completo" value="<?= htmlspecialchars($usuario['nome'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="nome_usuario">Nome de Usuário:</label>
                <input type="text" id="nome_usuario" name="nome_usuario" value="<?= htmlspecialchars($usuario['nome_usuario'] ?? '') ?>">
            </div>
            
            <button type="submit">Alterar</button>
        </form>
    </div>
</body>
</html>
