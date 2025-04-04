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
                            <label for="curso_usuario" class="form-label">Curso</label>
                            <select class="form-control" name="curso_usuario" required>
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
                            </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required placeholder="Digite seu email">
                        </div>
                        <div class="mb-3">
                            <label for="senha" class="form-label">Senha</label>
                            <input type="password" class="form-control" name="senha" required placeholder="Crie sua senha">
                        </div>
                        <button type="submit" class="btn btn-danger w-100">Enviar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
