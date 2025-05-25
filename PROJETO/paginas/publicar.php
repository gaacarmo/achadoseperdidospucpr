<?php


require_once 'paginas/conecta_db.php';  
//verifica se existe a sessao e depois verifica se esta logado
if (isset($_SESSION['is_logged_user']) && $_SESSION['is_logged_user'] === true) {
    //verifica se exite alguma informação no nome da postagem
    if (isset($_POST['postagem_nome'])) {
        
        $obj = conecta_db();
        $imagem_nome = null;
        
        if (!empty($_FILES['postagem_image']['name'])) {
            // Create uploads directory if it doesn't exist
            if (!file_exists('uploads')) {
                mkdir('uploads', 0777, true);
            }
        
            // Generate unique filename
            $file_extension = strtolower(pathinfo($_FILES['postagem_image']['name'], PATHINFO_EXTENSION));
            $imagem_nome = 'uploads/' . uniqid() . '.' . $file_extension;
        
            // Validate file type
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($file_extension, $allowed_types)) {
                echo "<div class='alert alert-danger'><h5>Tipo de arquivo não permitido!</h5></div>";
                exit();
            }
        
            // Validate file size (2MB max)
            if ($_FILES['postagem_image']['size'] > 2 * 1024 * 1024) {
                echo "<div class='alert alert-danger'><h5>A imagem deve ter no máximo 2MB!</h5></div>";
                exit();
            }
        
            // Move uploaded file to uploads directory without resizing
            if (!move_uploaded_file($_FILES['postagem_image']['tmp_name'], $imagem_nome)) {
                echo "<div class='alert alert-danger'><h5>Erro ao enviar a imagem!</h5></div>";
                exit();
            }
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f5f8fa;
            color: #0f1419;
            overflow-x: hidden;
        }
        .form-label {
            font-weight: 500;
            color: #0f1419;
        }
        .form-control, .form-select {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            border: 1px solid #ddd;
        }
        .form-control:focus, .form-select:focus {
            border-color: #7b0828;
            box-shadow: 0 0 0 0.2rem rgba(123, 8, 40, 0.25);
        }
        .btn-danger {
            background-color: #7b0828;
            border-color: #7b0828;
        }
        .btn-danger:hover {
            background-color: #5a061f;
            border-color: #5a061f;
        }
        .alert {
            border-radius: 8px;
        }
        .mobile-menu-toggle {
            display: none;
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1001;
            background: #7b0828;
            color: white;
            border: none;
            padding: 0.5rem;
            border-radius: 8px;
            cursor: pointer;
        }
        .text-muted {
            color: #536471 !important;
        }
        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: block;
            }
            .form-container {
                margin: 1rem;
                padding: 1rem;
            }
            .row {
                margin: 0;
            }
            .col-md-6 {
                padding: 0;
            }
        }
        .image-preview-container {
            margin-top: 1rem;
            text-align: center;
        }
        .image-preview {
            max-width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 8px;
            display: none;
            margin: 0 auto;
        }
        .image-upload-container {
            position: relative;
            border: 2px dashed #ddd;
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: border-color 0.3s;
        }
        .image-upload-container:hover {
            border-color: #7b0828;
        }
        .image-upload-container i {
            font-size: 2rem;
            color: #7b0828;
            margin-bottom: 1rem;
        }
        .image-upload-container input[type="file"] {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            opacity: 0;
            cursor: pointer;
        }
         
        /*side bar */
        .nav-link {
            display: flex;
            align-items: center;
            color: #0f1419;
            text-decoration: none;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            transition: background-color 0.2s;
        }
        .nav-link:hover {
            background-color: #f5f8fa;
            color: #7b0828;
        }
        .nav-link i, .nav-link img {
            margin-right: 0.75rem;
            width: 20px;
            height: 20px;
        }
        .publicar-btn {
            position: absolute;
            bottom: 2rem;
            left: 1.5rem;
            right: 1.5rem;
            background-color: #7b0828;
            color: white;
            border: none;
            padding: 0.75rem;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .publicar-btn:hover {
            background-color: #5a061f;
        }
        .content {
            margin-left: 280px;
            padding: 2rem;
            min-height: 100vh;
        }
        .back-button {
            background: none;
            border: none;
            color: #7b0828;
            font-size: 1rem;
            cursor: pointer;
            padding: 0.5rem 0;
            margin-bottom: 1rem;
        }
        .back-button:hover {
            text-decoration: underline;
        }
        .profile {
            position: fixed;
            right: 2rem;
            top: 2rem;
            background-color: white;
            padding: 1.5rem;
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            max-width: 300px;
        }
        .profile img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 1rem;
        }
        .profile h3 {
            margin: 0;
            font-size: 1.25rem;
            color: #0f1419;
        }
        .profile p {
            color: #536471;
            margin: 0.5rem 0 1rem;
        }
        .profile .btn {
            width: 100%;
            margin-bottom: 0.5rem;
        }
        .side-help-box {
            background-color: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            margin-top: 1rem;
        }
        .side-help-box h5 {
            color: #0f1419;
            margin-bottom: 1rem;
        }
        .side-help-box ol {
            color: #536471;
            padding-left: 1.5rem;
        }
        .side-help-box li {
            margin-bottom: 0.5rem;
        }
        .mobile-menu-toggle {
            display: none;
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1001;
            background: #7b0828;
            color: white;
            border: none;
            padding: 0.5rem;
            border-radius: 8px;
            cursor: pointer;
        }
        /* Login form styles */
        .form-container {
            background-color: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            margin: 2rem auto;
            max-width: 500px;
        }
        .form-label {
            font-weight: 500;
            color: #0f1419;
        }
        .form-control {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            border: 1px solid #ddd;
        }
        .form-control:focus {
            border-color: #7b0828;
            box-shadow: 0 0 0 0.2rem rgba(123, 8, 40, 0.25);
        }
        .form-text {
            color: #536471;
        }
        .form-text a {
            color: #7b0828;
            text-decoration: none;
        }
        .form-text a:hover {
            text-decoration: underline;
        }
        @media (max-width: 1024px) {
            .profile {
                position: static;
                margin: 2rem auto;
                max-width: 100%;
            }
        }
        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: block;
            }
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.active {
                transform: translateX(0);
            }
            .content {
                margin-left: 0;
                padding: 1rem;
            }
            .profile {
                margin: 1rem;
            }
        }
    </style>
</head>
<body>
    <button class="mobile-menu-toggle" id="mobileMenuToggle">
        <i class="fas fa-bars"></i>
    </button>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="form-container">
                    <h2 class="text-center mb-4">
                        <i class="fas fa-plus-circle"></i> Publicar Item
                    </h2>
                    
                    <?php if (isset($_SESSION['is_logged_user']) && $_SESSION['is_logged_user'] === true): ?>
                        <form action="" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                            <div class="">
                                <div class="col-md-6">
                                    <label for="postagem_nome" class="form-label">
                                        <i class="fas fa-heading"></i> Título <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="postagem_nome"
                                           name="postagem_nome" 
                                           required
                                           placeholder="Digite o título do item">
                                    <div class="invalid-feedback">Por favor, digite um título.</div> 
                                </div>

                                <div class="col-md-6">
                                    <label for="postagem_usuario_tipo" class="form-label">
                                        <i class="fas fa-tag"></i> Tipo <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select" id="postagem_usuario_tipo" name="postagem_usuario_tipo" required>
                                        <option value="" disabled selected>Selecione o tipo</option> <span class="text-danger">*</span>
                                        <option value="Perdi">Perdi</option>
                                        <option value="Achei">Achei</option>
                                    </select>
                                    <div class="invalid-feedback">Por favor, selecione o tipo.</div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <label for="postagem_descricao" class="form-label">
                                    <i class="fas fa-align-left"></i> Descrição <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control" 
                                          id="postagem_descricao"
                                          name="postagem_descricao" 
                                          rows="3" 
                                          required
                                          placeholder="Descreva o item encontrado/perdido"></textarea>
                                <div class="invalid-feedback">Por favor, forneça uma descrição.</div>
                            </div>

                            <div class="row g-3 mt-2">
                                <div class="col-md-6">
                                    <label for="postagem_local" class="form-label">
                                        <i class="fas fa-map-marker-alt"></i> Local <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="postagem_local"
                                           name="postagem_local" 
                                           required
                                           placeholder="Onde o item foi encontrado/perdido">
                                    <div class="invalid-feedback">Por favor, informe o local.</div>
                                </div>

                                <div class="col-md-6">
                                    <label for="postagem_data" class="form-label">
                                        <i class="fas fa-calendar"></i> Data <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" 
                                           class="form-control" 
                                           id="postagem_data"
                                           name="postagem_data" 
                                           required>
                                    <div class="invalid-feedback">Por favor, selecione a data.</div>
                                </div>
                            </div>

                            <div class="row g-3 mt-2">
                                <div class="col-md-6">
                                    <label for="postagem_cor" class="form-label">
                                        <i class="fas fa-palette"></i> Cor <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="postagem_cor"
                                           name="postagem_cor" 
                                           required
                                           placeholder="Cor do item">
                                    <div class="invalid-feedback">Por favor, informe a cor.</div>
                                </div>

                                <div class="col-md-6">
                                    <label for="postagem_categoria" class="form-label">
                                        <i class="fas fa-folder"></i> Categoria <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select" id="postagem_categoria" name="postagem_categoria" required>
                                        <option value="" disabled selected>Selecione uma categoria</option>
                                        <option value="Eletrônico">Eletrônico</option>
                                        <option value="Documentos">Documentos</option>
                                        <option value="Roupa">Roupa</option>
                                        <option value="Outro">Outro</option>
                                    </select>
                                    <div class="invalid-feedback">Por favor, selecione uma categoria.</div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <label class="form-label">
                                    <i class="fas fa-image"></i> Imagem
                                </label>
                                <div class="image-upload-container">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <p class="mb-0">Clique ou arraste uma imagem aqui</p>
                                    <small class="text-muted d-block mt-2">Formatos aceitos: JPG, PNG, GIF (Máx. 2MB)</small>
                                    <input type="file" 
                                           class="form-control" 
                                           id="postagem_image"
                                           name="postagem_image" 
                                           accept="image/*"
                                           onchange="previewImage(this)">
                                </div>
                                <div class="image-preview-container">
                                    <img id="imagePreview" class="image-preview" alt="Preview">
                                </div>
                            </div>

                            <div class="d-grid gap-2 mt-4">
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

        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            
            mobileMenuToggle.addEventListener('click', function() {
                // Add your mobile menu toggle logic here if needed
            });
        });

        // Image preview and validation
        function previewImage(input) {
            const preview = document.getElementById('imagePreview');
            const file = input.files[0];
            
            if (file) {
                // Validate file size (2MB max)
                if (file.size > 2 * 1024 * 1024) {
                    alert('A imagem deve ter no máximo 2MB');
                    input.value = '';
                    preview.style.display = 'none';
                    return;
                }

                // Validate file type
                if (!file.type.match('image.*')) {
                    alert('Por favor, selecione apenas imagens');
                    input.value = '';
                    preview.style.display = 'none';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
            }
        }

        // Drag and drop functionality
        const dropZone = document.querySelector('.image-upload-container');
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            dropZone.classList.add('border-danger');
        }

        function unhighlight(e) {
            dropZone.classList.remove('border-danger');
        }

        dropZone.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            const input = document.getElementById('postagem_image');
            input.files = files;
            previewImage(input);
        }
    </script>
</body>
</html>