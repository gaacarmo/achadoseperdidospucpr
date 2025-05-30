<?php
require_once "./paginas/conecta_db.php";

$conexao = conecta_db();

// Consulta para obter os usuários mais ativos
$sql = "SELECT u.usuario_id, u.nome_usuario, 
        COUNT(DISTINCT p.postagem_id) as postagens,
        COUNT(DISTINCT c.comentario_id) as comentarios
    FROM Usuario u
    LEFT JOIN Postagem p ON u.usuario_id = p.id_usuario
    LEFT JOIN Comentarios c ON u.usuario_id = c.usuario_id
    GROUP BY u.usuario_id, u.nome_usuario
    ORDER BY (COUNT(DISTINCT p.postagem_id) + COUNT(DISTINCT c.comentario_id)) DESC
    LIMIT 10"; // Limitando aos 10 usuários mais ativos

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
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Análise de Atividade dos Usuários</title>
</head>
<body>
    <h1 class="titulo">Top 10 Usuários Mais Ativos</h1>
    <div class="container">
        <div id="barchart" style="width: 1000px; height: 600px;"></div>
    </div> 
</body>
</html>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {'packages':['bar']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Usuário');
        data.addColumn('number', 'Postagens');
        data.addColumn('number', 'Comentários');
        
        data.addRows([
            <?php
            foreach ($registros as $registro) {
                echo "['" . $registro['nome_usuario'] . "', " . $registro['postagens'] . ", " . $registro['comentarios'] . "],";
            }
            ?>
        ]);

        var options = {
            title: 'Atividade dos Usuários (Top 10)',
            chart: {
                subtitle: 'Postagens e Comentários',
            },
            bars: 'horizontal',
            hAxis: {
                title: 'Quantidade',
                minValue: 0
            },
            vAxis: {
                title: 'Usuários'
            },
            colors: ['#FF9800', '#9C27B0'], // Cores diferentes para diferenciar os tipos de atividade
            legend: { position: 'top' },
            chartArea: {width: '80%', height: '80%'},
            isStacked: false
        };

        var chart = new google.charts.Bar(document.getElementById('barchart'));
        chart.draw(data, google.charts.Bar.convertOptions(options));
        
        window.addEventListener('resize', function() {
            chart.draw(data, google.charts.Bar.convertOptions(options));
        });
    }
</script>
<style>
.titulo {
    text-align: center;
    margin-top: 20px;
    color: #333;
    font-family: Arial, sans-serif;
}
.container {
    margin-top: 30px;
    display: flex;
    justify-content: center;
    width: 100%;
    padding: 20px;
    box-sizing: border-box;
}
.voltar {
    position: absolute;
    top: 20px;
    left: 20px;
    width: 30px;
    height: 30px;
    cursor: pointer;
    transition: transform 0.2s;
}
.voltar:hover {
    transform: scale(1.1);
}
</style>