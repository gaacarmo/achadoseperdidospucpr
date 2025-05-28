<?php
require_once "./paginas/conecta_db.php";
$conexao = conecta_db();

// Soft delete se o parâmetro Excluir estiver presente e válido
if (isset($_GET['Excluir']) && is_numeric($_GET['Excluir'])) {
    $usuario_id = (int) $_GET['Excluir'];

    $conexao->autocommit(FALSE);
    $transacao_sucesso = true;

    $sql = "UPDATE Usuario SET usuario_ativo = FALSE WHERE usuario_id = ?";
    $stmt = $conexao->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $usuario_id);
        if (!$stmt->execute()) {
            $transacao_sucesso = false;
        }
        $stmt->close();
    } else {
        $transacao_sucesso = false;
    }

    if ($transacao_sucesso) {
        $conexao->commit();
    } else {
        $conexao->rollback();
        echo "Erro ao excluir dados.";
    }

    $conexao->autocommit(TRUE);
}

// Lista apenas usuários ativos
$sql = "SELECT usuario_id, nome, nome_usuario, email
        FROM Usuario
        WHERE usuario_ativo = TRUE
        ORDER BY usuario_id";

$resultado = $conexao->query($sql);
$registros = [];

if ($resultado && $resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        $registros[] = $row;
    }
} elseif ($conexao->error) {
    echo "Erro: " . $conexao->error;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de usuários</title>
    <link rel="stylesheet" href="./CSS/lista_usuarios.css">
</head>
<body>
    <h1 class="titulo">Usuários</h1>

    <?php if (count($registros) > 0): ?>
        <table class="tabela">
            <tr>
                <th class="tabela">ID</th>
                <th class="tabela">Nome completo</th>
                <th class="tabela">Nome de Usuário</th>
                <th class="tabela">Email</th>
                <th class="tabela">Ação</th>
            </tr>
            <?php foreach ($registros as $registro): ?>
                <tr>
                    <td class="registros-tabela"><?= $registro['usuario_id'] ?></td>
                    <td class="registros-tabela"><?= $registro['nome'] ?></td>
                    <td class="registros-tabela"><?= $registro['nome_usuario'] ?></td>
                    <td class="registros-tabela"><?= $registro['email'] ?></td>
                    <td class="registros-tabela">
                        <a class="botao-excluir" 
                           href="include_adm.php?dir=paginas_adm&file=lista_usuarios&Excluir=<?= $registro['usuario_id'] ?>"
                           onclick="return confirm('Tem certeza que deseja excluir este usuário?')">
                           Excluir
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Nenhum resultado encontrado.</p>
    <?php endif; ?>
</body>
</html>
