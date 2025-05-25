<?php
require_once "./paginas/conecta_db.php";
$conexao = conecta_db();

if (isset($_GET['Excluir'])) {
    $conexao->autocommit(FALSE);
    $conexao->begin_transaction();
    $transacao_sucesso = true;

    $excluirProdutosSQL = "DELETE FROM Usuario WHERE usuario_id = ?";
    $stmtProdutos = $conexao->prepare($excluirProdutosSQL);
    $stmtProdutos->bind_param("i", $_GET['Excluir']);
    if (!$stmtProdutos->execute()) {
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

$sql = "SELECT Usuario.usuario_id, Usuario.nome, Usuario.nome_usuario, Usuario.email
        FROM Usuario
        ORDER BY usuario_id";
        
$resultado = $conexao->query($sql);
$registros = [];

if ($resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        $registros[] = $row; 
    }
} elseif ($conexao->error) {
    echo "Erro: " . $conexao->error;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de usuários</title>
    <link rel="stylesheet" href="./CSS/lista_usuarios.css">
</head>
<body>
    <h1 class="titulo">Usuários</h1>

    <?php

    if (count($registros) > 0) {
        echo "<table class='tabela'>
                <tr>
                    <th class='tabela'>ID</th>
                    <th class='tabela'>Nome completo</th>
                    <th class='tabela'>Nome de Usuário</th>
                    <th class='tabela'>Email</th>
                    <th class='tabela'>Ação</th>
                </tr>";
        
        foreach ($registros as $registro) {
            echo "<tr>
                    <td class='registros-tabela'>{$registro['usuario_id']}</td>
                    <td class='registros-tabela'>{$registro['nome']}</td>
                    <td class='registros-tabela'>{$registro['nome_usuario']}</td>
                    <td class='registros-tabela'>{$registro['email']}</td>
                    <td class='registros-tabela'>
                        <a class='botao-excluir' name='Excluir' href='lista_usuarios.php?Excluir=" . $registro['usuario_id'] . "' //precisamos fazer o include
                onclick='return confirm(\"Tem certeza que deseja excluir este usuário?\")'> 
                Excluir
            </a>
                    </td>
                </tr>";
        }
        
        echo "</table>";
    } else {
        echo "Nenhum resultado encontrado.";
    }
    ?>

</body>
</html>