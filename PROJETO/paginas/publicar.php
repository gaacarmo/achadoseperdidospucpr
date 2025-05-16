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
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publicar - AcheiNaPuc</title>
    
    <link rel="stylesheet" href="../CSS/publicar.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="form-container">
                    <h2 class="text-center mb-4">Publicar Item</h2>
                    
                    <?php if (isset($_SESSION['is_logged_user']) && $_SESSION['is_logged_user'] === true): ?>
                        <form action="" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="postagem_nome" class="form-label">
                                        <i class="fas fa-heading"></i> Título
                                    </label>
                                    <input type="text" class="form-control" name="postagem_nome" required>
                                    <div class="invalid-feedback">Por favor, digite um título.</div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="postagem_usuario_tipo" class="form-label">
                                        <i class="fas fa-tag"></i> Tipo
                                    </label>
                                    <select class="form-select" name="postagem_usuario_tipo" required>
                                        <option value="" disabled selected>Selecione o tipo</option>
                                        <option value="Perdi">Perdi</option>
                                        <option value="Achei">Achei</option>
                                    </select>
                                    <div class="invalid-feedback">Por favor, selecione o tipo.</div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="postagem_descricao" class="form-label">
                                    <i class="fas fa-align-left"></i> Descrição
                                </label>
                                <textarea class="form-control" name="postagem_descricao" rows="3" required></textarea>
                                <div class="invalid-feedback">Por favor, forneça uma descrição.</div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="postagem_local" class="form-label">
                                        <i class="fas fa-map-marker-alt"></i> Local
                                    </label>
                                    <input type="text" class="form-control" name="postagem_local" required>
                                    <div class="invalid-feedback">Por favor, informe o local.</div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="postagem_data" class="form-label">
                                        <i class="fas fa-calendar"></i> Data
                                    </label>
                                    <input type="date" class="form-control" name="postagem_data" required>
                                    <div class="invalid-feedback">Por favor, selecione a data.</div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="postagem_cor" class="form-label">
                                        <i class="fas fa-palette"></i> Cor
                                    </label>
                                    <input type="text" class="form-control" name="postagem_cor" required>
                                    <div class="invalid-feedback">Por favor, informe a cor.</div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="postagem_categoria" class="form-label">
                                        <i class="fas fa-folder"></i> Categoria
                                    </label>
                                    <select class="form-select" name="postagem_categoria" required>
                                        <option value="">Selecione</option>
                                        <option value="Eletrônico">Eletrônico</option>
                                        <option value="Documentos">Documentos</option>
                                        <option value="Roupa">Roupa</option>
                                        <option value="Outro">Outro</option>
                                    </select>
                                    <div class="invalid-feedback">Por favor, selecione uma categoria.</div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="postagem_image" class="form-label">
                                    <i class="fas fa-image"></i> Imagem
                                </label>
                                <input type="file" class="form-control" name="postagem_image" accept="image/*">
                                <small class="text-muted">Formatos aceitos: JPG, PNG, GIF (Máx. 2MB)</small>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-paper-plane"></i> Publicar
                                </button>
                                <a href="index.php" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left"></i> Voltar
                                </a>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-warning text-center">
                            <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                            <h5>Você precisa estar logado para publicar um item.</h5>
                            <a href="include.php?dir=paginas&file=login" class="btn btn-danger mt-3">
                                <i class="fas fa-sign-in-alt"></i> Ir para Login
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Form validation
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>
</body>
</html>