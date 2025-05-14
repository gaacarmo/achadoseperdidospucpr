<?php

require_once 'paginas/conecta_db.php';  
//verifica se existe a sessao e depois verifica se esta logado
if (isset($_SESSION['is_logged_user']) && $_SESSION['is_logged_user'] === true) {
    //verifica se exite alguma informação no nome da postagem
    if (isset($_POST['postagem_nome'])) {
        
        $obj = conecta_db();
        $imagem_nome = null;
        if (!empty($_FILES['postagem_image']['name'])) {
            $imagem_nome = 'uploads/' . basename($_FILES['postagem_image']['name']);
            $caminho_temp = $_FILES['postagem_image']['tmp_name'];
            move_uploaded_file($caminho_temp, $imagem_nome);
        }
        //comando sql
        $query = "INSERT INTO Postagem (
            postagem_nome,
            postagem_descricao,
            postagem_local,
            postagem_cor,
            postagem_categoria,
            postagem_data,
            postagem_image,
            postagem_usuario_tipo,
            id_usuario
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
      
        $stmt = $obj->prepare($query);
        $stmt->bind_param(
            "ssssssssi",
            $_POST['postagem_nome'],
            $_POST['postagem_descricao'],
            $_POST['postagem_local'],
            $_POST['postagem_cor'],
            $_POST['postagem_categoria'],
            $_POST['postagem_data'],
            $imagem_nome,
            $_POST['postagem_usuario_tipo'],
            $_SESSION['usuario_id']
        );

        $resultado = $stmt->execute();

        if ($resultado) {
            //se der tudo certo
            header("Location: index.php");
            exit();
            //se nao der
        } else {
            echo "<span class='alert alert-danger'><h5>Não funcionou!</h5></span>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Publicar Achado ou Perdido</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link rel="stylesheet" href="./CSS/publicar.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</head>
<body>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2 class="text-center mb-4">Cadastro de Achado ou Perdido</h2>
                
                <?php
                //verifica novamente se esta logado ou nao
                if (isset($_SESSION['is_logged_user']) && $_SESSION['is_logged_user'] === true): ?>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <!-- Dados da postagem -->
                        <div class="mb-3">
                            <label for="postagem_nome" class="form-label">Título da Postagem:</label>
                            <input type="text" class="form-control" name="postagem_nome" required>
                        </div>

                        <div class="mb-3">
                            <label for="postagem_descricao" class="form-label">Descrição:</label>
                            <textarea class="form-control" name="postagem_descricao" rows="3" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="postagem_local" class="form-label">Local onde foi encontrado/perdido:</label>
                            <input type="text" class="form-control" name="postagem_local" required>
                        </div>

                        <div class="mb-3">
                            <label for="postagem_cor" class="form-label">Cor do objeto:</label>
                            <input type="text" class="form-control" name="postagem_cor" required>
                        </div>

                        <div class="mb-3">
                            <label for="postagem_categoria" class="form-label">Categoria:</label>
                            <select class="form-control" name="postagem_categoria" required>
                                <option value="">Selecione</option>
                                <option value="Eletrônico">Eletrônico</option>
                                <option value="Documentos">Documentos</option>
                                <option value="Roupa">Roupa</option>
                                <option value="Outro">Outro</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="postagem_data" class="form-label">Data em que foi encontrado/perdido:</label>
                            <input type="date" class="form-control" name="postagem_data" required>
                        </div>

                        <div class="mb-3">
                            <label for="postagem_image" class="form-label">Imagem do objeto:</label>
                            <input type="file" class="form-control" name="postagem_image" accept="image/*" >
                            <small class="text-muted">Formatos aceitos: JPG, PNG, GIF (Máx. 2MB)</small>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label for="postagem_usuario_tipo" class="form-label">Tipo da postagem</label>
                            <select class="form-control" name="postagem_usuario_tipo" required>
                                <option value="" disabled selected>Selecione o tipo da postagem</option>
                                <option value="Perdi">Perdi</option>
                                <option value="Achei">Achei</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-danger w-100">Publicar</button>
                    </form>
                
                <!--caso o usuario tente acessar sem estar logado -->
                <?php else: ?>
                    <div class="alert text-center">
                        <h5>Você precisa estar logado para publicar um item.</h5>
                        <a href="include.php?dir=paginas&file=login" class="btn btn-outline-danger mt-3">Ir para Login</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>