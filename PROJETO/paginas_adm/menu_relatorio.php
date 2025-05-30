<?php
require_once "./paginas/conecta_db.php";
$conexao = conecta_db();

?>
<style> 

    .products {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
    gap: 20px;
}

.product {
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 20px;
    height: 300px;
    width: 300px; 
    text-align: center;
    margin: 30px;
}

.img-produto {
    width: 100%;
    height: 200px; 
    overflow: hidden; 
    border-radius: 10px;
}

.img-produto img {
    width: 100%;
    height: 100%;
    object-fit: cover; 
    border-radius: 10px;
}

.texto-produto {
    font-size: 18px;
    color: #333;
    margin: 15px 0;
}

.btn-index {
    display: inline-block;
    padding: 10px 20px;
    font-size: 16px;
    color: #fff;
    background-color: #007bff;
    border: none;
    border-radius: 5px;
    text-decoration: none;
    transition: background-color 0.3s;
}

.btn-index:hover {
    background-color: #0056b3;
}

</style>

<section class="products">
    <div class="product">
        <p class="texto-produto">Quantidade de postagens ao longo do tempo</p>
        <a class="btn-index" href='include_adm.php?dir=paginas_adm&file=grafico1'>Gráfico 01</a>
    </div>
    <div class="product">
        
        <p class="texto-produto">Postagens com mais engajamento </p>
        <a class="btn-index" href='include_adm.php?dir=paginas_adm&file=grafico2'>Gráfico 02</a>
    </div>
    <div class="product">
        <p class="texto-produto">Usuários mais ativos do site</p>
        <a class="btn-index" href='include_adm.php?dir=paginas_adm&file=grafico3'>Gráfico 03</a>
    </div>
</section>