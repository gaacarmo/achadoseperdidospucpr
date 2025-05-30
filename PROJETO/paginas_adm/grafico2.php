<?php
require_once "./paginas/conecta_db.php";

$conexao = conecta_db();

// Consulta corrigida - usando a expressão completa no ORDER BY
$sql = "SELECT p.postagem_id, p.postagem_nome, 
            COUNT(DISTINCT c.comentario_id) as comentarios,
            COUNT(DISTINCT cu.curtida_id) as curtidas
        FROM Postagem p
        LEFT JOIN Comentarios c ON p.postagem_id = c.postagem_id
        LEFT JOIN Curtidas cu ON p.postagem_id = cu.postagem_id
        GROUP BY p.postagem_id, p.postagem_nome
        ORDER BY COUNT(DISTINCT c.comentario_id) + COUNT(DISTINCT cu.curtida_id) DESC
        LIMIT 10";

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
    <title>Análise de Engajamento das Postagens</title>
</head>
<body>
    <h1 class="titulo">Top 10 Postagens Mais Engajadas</h1>
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
        data.addColumn('string', 'Postagem');
        data.addColumn('number', 'Curtidas');
        data.addColumn('number', 'Comentários');
        
        data.addRows([
            <?php
            foreach ($registros as $registro) {
                $nome = addslashes(substr($registro['postagem_nome'], 0, 30)); // Limita o tamanho do nome para caber no gráfico
                echo "['" . $nome . "', " . $registro['curtidas'] . ", " . $registro['comentarios'] . "],";
            }
            ?>
        ]);

        var options = {
            title: 'Engajamento por Postagem (Top 10)',
            chart: {
                subtitle: 'Curtidas e Comentários',
            },
            bars: 'horizontal',
            hAxis: {
                title: 'Quantidade',
                minValue: 0
            },
            vAxis: {
                title: 'Postagens'
            },
            colors: ['#4285F4', '#34A853'],
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