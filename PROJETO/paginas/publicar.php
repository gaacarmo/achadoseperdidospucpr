<?php
require_once 'paginas/conecta_db.php';

if (isset($_POST['postagem_nome'])) {
    $obj = conecta_db();

    // Verifica se o arquivo foi enviado
    $imagem_nome = null;
    if (!empty($_FILES['postagem_image']['name'])) {
        $imagem_nome = 'uploads/' . basename($_FILES['postagem_image']['name']);
        $caminho_temp = $_FILES['postagem_image']['tmp_name'];
        move_uploaded_file($caminho_temp, $imagem_nome);
    }

    $query = "INSERT INTO Postagem (
        postagem_nome,
        postagem_descricao,
        postagem_local,
        postagem_cor,
        postagem_categoria,
        postagem_data,
        postagem_image,
        postagem_usuario_tipo
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $obj->prepare($query);

    $stmt->bind_param(
        "ssssssss",
        $_POST['postagem_nome'],
        $_POST['postagem_descricao'],
        $_POST['postagem_local'],
        $_POST['postagem_cor'],
        $_POST['postagem_categoria'],
        $_POST['postagem_data'],
        $imagem_nome,
        $_POST['postagem_usuario_tipo']
    );

    $resultado = $stmt->execute();

    if ($resultado) {
        header("Location:index.php");
        exit();
    } else {
        echo "<span class='alert alert-danger'><h5>Não funcionou!</h5></span>";
    }
}

?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Publicar Achado ou Perdido</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            background-color: #ffffff;
            border: 1px solid #ccc;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.05);
            width: auto;
        }
        h2 {
            color: #7b0828;
            font-weight: 600;
        }
        .form-label {
            color: #343a40;
            font-weight: 500;
        }
        .form-control:focus {
            border-color: #7b0828;
            box-shadow: 0 0 0 0.2rem rgba(123, 8, 40, 0.25);
        }
        .btn-danger {
            background-color: #7b0828;
            border-color: #7b0828;
        }
        .btn-danger:hover {
            background-color: #5e061f;
            border-color: #5e061f;
        }
        select.form-control {
            background-color: #fff;
        }
        .alert {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2 class="text-center mb-4">Cadastro de Achado ou Perdido</h2>
                
                <?php
                if(isset($_SESSION['mensagem_erro'])) {
                    echo '<div class="alert alert-danger">'.$_SESSION['mensagem_erro'].'</div>';
                    unset($_SESSION['mensagem_erro']);
                }
                ?>
                
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
            </div>
        </div>
    </div>
</body>
</html>