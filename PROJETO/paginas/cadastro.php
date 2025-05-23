<?php
require_once 'paginas/conecta_db.php';

if (isset($_POST['email'])) {
    $obj = conecta_db();

    // Primeiro verifica se já existe o nome de usuário ou email
    $queryVerifica = "SELECT * FROM Usuario WHERE nome_usuario = ? OR email = ?";
    $stmtVerifica = $obj->prepare($queryVerifica);
    $stmtVerifica->bind_param("ss", $_POST['nome_usuario'], $_POST['email']);
    $stmtVerifica->execute();
    $resultadoVerifica = $stmtVerifica->get_result();

    $password = $_POST['senha'];
    $hash = password_hash($password, PASSWORD_DEFAULT);


    if ($resultadoVerifica->num_rows > 0) {
        echo "<span class='alert alert-danger'><h5>Nome de usuário ou e-mail já cadastrado!</h5></span>";
    } else {
        // Se não existe, então pode inserir
        $query = "INSERT INTO Usuario (nome, nome_usuario, email, senha, curso_usuario) VALUES (?, ?, ?, ?, ?)";
        $stmt = $obj->prepare($query);
        $stmt->bind_param("sssss", $_POST['nome'], $_POST['nome_usuario'], $_POST['email'], $hash, $_POST['curso_usuario']);
        $resultado = $stmt->execute();

        if ($resultado) {
            header("Location: ./include.php?dir=paginas&file=login");
            exit();
        } else {
            echo "
            <div class='container mt-4'>
                <div class='alert alert-danger text-center rounded-pill' role='alert'>
                    <strong>Nome de usuário ou e-mail já cadastrado!</strong>
                </div>
            </div>";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - AcheiNaPuc</title>
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
        .form-container {
            background-color: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            margin: 2rem auto;
            max-width: 800px;
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
                        <i class="fas fa-user-plus"></i> Cadastro
                    </h2>

                    <?php if (isset($error_message)): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
                        </div>
                    <?php endif; ?>

                    <form action="" method="POST" class="needs-validation" novalidate>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nome" class="form-label">
                                    <i class="fas fa-user"></i> Nome completo
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="nome"
                                       name="nome" 
                                       required 
                                       placeholder="Digite seu nome completo">
                                <div class="invalid-feedback">Por favor, digite seu nome completo.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="nome_usuario" class="form-label">
                                    <i class="fas fa-at"></i> Nome de Usuário
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="nome_usuario"
                                       name="nome_usuario" 
                                       required 
                                       placeholder="Digite seu nome de usuário">
                                <div class="invalid-feedback">Por favor, escolha um nome de usuário.</div>
                            </div>
                        </div>

                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope"></i> Email
                                </label>
                                <input type="email" 
                                       class="form-control" 
                                       id="email"
                                       name="email" 
                                       required 
                                       placeholder="Digite seu email">
                                <div class="invalid-feedback">Por favor, digite um email válido.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="senha" class="form-label">
                                    <i class="fas fa-lock"></i> Senha
                                </label>
                                <input type="password" 
                                       class="form-control" 
                                       id="senha"
                                       name="senha" 
                                       required 
                                       placeholder="Crie sua senha">
                                <div class="invalid-feedback">Por favor, crie uma senha.</div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <label for="curso_usuario" class="form-label">
                                <i class="fas fa-graduation-cap"></i> Curso
                            </label>
                            <select class="form-select" id="curso_usuario" name="curso_usuario" required>
                                <option value="" disabled selected>Selecione seu curso</option>
                                <option>Administração</option>
                                <option>Administração Internacional</option>
                                <option>Agronomia</option>
                                <option>Análise e Desenvolvimento de Sistemas</option>
                                <option>Arquitetura e Urbanismo</option>
                                <option>Artes Visuais - Multiartes</option>
                                <option>Bacharelado Interdisciplinar em Negócios</option>
                                <option>Big Data e Inteligência Analítica</option>
                                <option>Biomedicina</option>
                                <option>Ciência da Computação</option>
                                <option>Ciência de Dados e Inteligência Artificial</option>
                                <option>Ciências Biológicas</option>
                                <option>Ciências Contábeis</option>
                                <option>Computação Imersiva</option>
                                <option>Design</option>
                                <option>Direito</option>
                                <option>Economia</option>
                                <option>Economia da Influência Digital</option>
                                <option>Enfermagem</option>
                                <option>Engenharia Civil</option>
                                <option>Engenharia de Controle e Automação</option>
                                <option>Engenharia de Produção</option>
                                <option>Engenharia de Software</option>
                                <option>Engenharia Elétrica</option>
                                <option>Engenharia Mecânica</option>
                                <option>Engenharia Química</option>
                                <option>Estética e Cosmética</option>
                                <option>Experiência do Usuário – UX Design</option>
                                <option>Farmácia</option>
                                <option>Filosofia</option>
                                <option>Física</option>
                                <option>Fisioterapia</option>
                                <option>Fonoaudiologia</option>
                                <option>Gastronomia</option>
                                <option>Gestão Comercial</option>
                                <option>Gestão da Experiência do Cliente (Customer Experience)</option>
                                <option>Gestão da Felicidade e Projeto de Vida</option>
                                <option>Gestão de Marketing em Mídias Digitais</option>
                                <option>Gestão de Recursos Humanos</option>
                                <option>Gestão de Tecnologia da Informação (TI)</option>
                                <option>Gestão Financeira</option>
                                <option>Gestão Integrada de Agronegócios</option>
                                <option>Gestão Internacional</option>
                                <option>Gestão Pública</option>
                                <option>Gestão Socioambiental e Governança (ESG)</option>
                                <option>História - Bacharelado</option>
                                <option>História - Licenciatura</option>
                                <option>Inteligência Artificial Aplicada</option>
                                <option>Inteligência Artificial: Sistemas de Dados Inteligentes</option>
                                <option>Jornalismo</option>
                                <option>Letras - Inglês</option>
                                <option>Letras - Português</option>
                                <option>Logística</option>
                                <option>Marketing</option>
                                <option>Matemática</option>
                                <option>Medicina</option>
                                <option>Medicina Veterinária</option>
                                <option>Nutrição</option>
                                <option>Odontologia</option>
                                <option>Pedagogia</option>
                                <option>Processos Gerenciais</option>
                                <option>Psicologia</option>
                                <option>Publicidade e Propaganda</option>
                                <option>Química</option>
                                <option>Relações Internacionais</option>
                                <option>Saúde e Bem-estar Integral</option>
                                <option>Serviço Social</option>
                                <option>Sistemas de Informação</option>
                                <option>Teatro - Multiartes</option>
                                <option>Teologia</option>
                            </select>
                            <div class="invalid-feedback">Por favor, selecione seu curso.</div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-user-plus"></i> Cadastrar
                            </button>
                            <a href="include.php?dir=paginas&file=login" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Voltar para Login
                            </a>
                        </div>
                    </form>
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
    </script>
</body>
</html>
