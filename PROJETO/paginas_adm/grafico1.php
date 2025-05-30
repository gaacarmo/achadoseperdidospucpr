<?php
require_once "./paginas/conecta_db.php";

$conexao = conecta_db();
$sql = "SELECT DATE(postagem_timestamp) as data, COUNT(*) as total 
        FROM Postagem 
        GROUP BY DATE(postagem_timestamp)
        ORDER BY data ASC";
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
    <title>Análise de Atividade de Postagens</title>
</head>
<body>
    <h1 class="titulo">Atividade de Postagens ao Longo do Tempo</h1>
    <div class="container">
        <div id="linechart" style="width: 1000px; height: 500px;"></div>
    </div> 
</body>
</html>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('date', 'Data');
        data.addColumn('number', 'Postagens');
        
        data.addRows([
            <?php
            foreach ($registros as $registro) {
                $dataParts = explode('-', $registro['data']);
                echo "[new Date(".$dataParts[0].", ".($dataParts[1]-1).", ".$dataParts[2]."), ".$registro['total']."],";
            }
            ?>
        ]);

        var options = {
            title: 'Evolução das Postagens por Dia',
            curveType: 'function',
            legend: { position: 'bottom' },
            hAxis: {
                title: 'Data',
                format: 'dd/MM/yyyy'
            },
            vAxis: {
                title: 'Número de Postagens'
            },
            chartArea: {width: '85%', height: '70%'},
            colors: ['#4285F4']
        };

        var chart = new google.visualization.LineChart(document.getElementById('linechart'));
        chart.draw(data, options);
        
        // Redimensiona o gráfico quando a janela muda de tamanho
        window.addEventListener('resize', function() {
            chart.draw(data, options);
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