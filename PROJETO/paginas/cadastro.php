<?php
require_once 'paginas/conecta_db.php';
if(isset($_POST['email'])) {
    $obj = conecta_db();
    
    $query = "INSERT INTO Usuario (nome, nome_usuario, email, senha, curso_usuario) VALUES (?, ?, ?, ?, ?)";
    $stmt = $obj->prepare($query);
    
    $stmt->bind_param("sssss", $_POST['nome'], $_POST['nome_usuario'], $_POST['email'], $_POST['senha'], $_POST['curso_usuario']);
    $resultado = $stmt->execute();
    
    if($resultado) {
        header("Location: ./include.php?dir=paginas&file=login");
        exit();
    } else {
        echo "<span class='alert alert-danger'><h5>Não funcionou!</h5></span>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Cadastro</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="form-container">
                    <h2 class="text-center mb-4">Cadastro</h2>
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome completo:</label>
                            <input type="text" class="form-control" name="nome" required placeholder="Digite seu nome completo">
                        </div>
                        <div class="mb-3">
                            <label for="nome_usuario" class="form-label">Nome de Usuário:</label>
                            <input type="text" class="form-control" name="nome_usuario" required placeholder="Digite seu nome de usuário">
                        </div>
                        <div class="mb-3">
                            <label for="curso_usuario" class="form-label">Curso:</label>
                            <input type="text" class="form-control" name="curso_usuario" required placeholder="Digite seu curso">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" class="form-control" name="email" required placeholder="Digite seu email">
                        </div>
                        <div class="mb-3">
                            <label for="senha" class="form-label">Senha:</label>
                            <input type="password" class="form-control" name="senha" required placeholder="Crie sua senha">
                        </div>
                        <button type="submit" class="btn btn-custom w-100">Enviar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
