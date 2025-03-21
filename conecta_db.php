<?php
	    
		//Função para conectar no banco de dados
        //Retorna o objeto da Conexão.
        
	function conecta_db(){
		$db_name = "db_achei";
		$user 	 = "root";
		$pass    = "";
		$server  = "localhost:3306";
		$conexao = new mysqli($server, $user, $pass, $db_name);
		return $conexao;
	}
?>