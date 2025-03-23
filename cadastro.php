<?php
    if(isset($_POST['descricao'])){
		$obj = conecta_db();
		$query = "
		INSERT INTO Usuario(nome, nome_usuario, email, senha, curso)
        VALUES('".$_POST['nome']."','".$_POST['nome_usuario']."','".$_POST['email']."','".$_POST['senha']."','".$_POST['curso']."' )";
        
		$resultado = $obj->query($query);
		if($resultado){
			header("location:index.php");
		}else{
			echo "<span class='alert alert-danger'>
			<h5>Não funcionou!</h5>
			</span>";
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="container mt-3">
  <h2>Stacked form</h2>
  <form action="/action_page.php">
    <div class="mb-3 mt-3">
      <label for="email">Email:</label>
      <input type="email" class="form-control" id="email" placeholder="Enter email" name="email">
    </div>
    <div class="mb-3">
      <label for="pwd">Password:</label>
      <input type="password" class="form-control" id="pwd" placeholder="Enter password" name="pswd">
    </div>
    <div class="form-check mb-3">
            
      <label class="form-check-label" >
        <input class="form-check-input" type="checkbox" name="remember"> Remember me
      </label>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
  </form>
</div>

</body>
</html>
