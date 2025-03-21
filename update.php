<?php
	if(isset($_POST['descricao'])){
		$obj = conecta_db();
		$query = "
			UPDATE usuario 
			SET descricao = '".$_POST['descricao']."' 
			WHERE teste_id = '".$_GET['teste_id']."'
		";
		
		$resultado = $obj->query($query);
		if($resultado){
			header("location:index.php");
		}else{
			echo "<span class='alert alert-danger'>
			<h5>Não funcionou!</h5>
			</span>";
		}
	}