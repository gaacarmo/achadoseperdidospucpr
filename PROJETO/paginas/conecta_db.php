<?php
	    
		//Função para conectar no banco de dados
        //Retorna o objeto da Conexão.
        
		function conecta_db() {
			$host = "localhost"; // ou 127.0.0.1
			$usuario = "root"; // usuário padrão do XAMPP
			$senha = ""; // senha vazia no XAMPP
			$banco = "db_achei"; // Substitua pelo nome correto do seu banco
		
			$conn = new mysqli($host, $usuario,$senha,  $banco);
		
			if ($conn->connect_error) {
				die("Falha na conexão: " . $conn->connect_error);
			}
		
			return $conn;
		}
		
?>