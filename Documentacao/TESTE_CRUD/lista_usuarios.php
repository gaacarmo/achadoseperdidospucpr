<h1 class="titulo">Usuários</h1>

<?php

require_once "conecta_db.php";
$conexao = conecta_db();



if (isset($_GET['Excluir'])) {
    $conexao->autocommit(FALSE);
    $conexao->begin_transaction();
    $transacao_sucesso = true;

    $excluirProdutosSQL = "DELETE FROM Usuario WHERE id = ?";
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

        header("Location: index.php");
        exit();
}



$sql = "SELECT Usuario.id, Usuario.nome, Usuario.nome_usuario, Usuario.email
        FROM Usuario
        ORDER BY id";
        
$resultado = $conexao->query($sql);
$registros = [];

if ($resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        $registros[] = $row; 
    }
} elseif ($conexao->error) {
    echo "Erro: " . $conexao->error;
}

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
                <td class='registros-tabela'>{$registro['id']}</td>
                <td class='registros-tabela'>{$registro['nome']}</td>
                <td class='registros-tabela'>{$registro['nome_usuario']}</td>
                <td class='registros-tabela'>{$registro['email']}</td>
                <td class='registros-tabela'>
                    <a class='botao-excluir' name='Excluir' href='lista_usuarios.php?Excluir=" . $registro['id'] . "' //precisamos fazer o include
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

<style>
    .titulo{
        text-align: center;
        font-weight: 1.2rem;
    }
    table.tabela {
        width: 100%;
        border-collapse: collapse;
        margin: 3% 0px 0px 30px;
    }

    table.tabela th {
        background-color: black;
        color: white;
        padding: 10px;
        text-align: left;
    }

    table.tabela tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    table.tabela tr:nth-child(odd) {
        background-color: #ffffff;
    }

    table.tabela td {
        padding: 10px;
        text-align: left;
    }

    .botao-excluir {
        display: inline-block;
        padding: 8px 16px;
        background-color: #FF4C4C;
        color: white;
        border: none;
        border-radius: 4px;
        text-decoration: none;
        font-weight: bold;
        transition: background-color 0.3s;
        cursor: pointer;
    }

    .botao-excluir:hover {
        background-color: #FF1C1C;
    }
</style>