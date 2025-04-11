<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOME - ADM</title>
    <style>
        /* Navigation styles */
        nav {
            background-color: #ffffff;
            color: #7b0828;
            padding: 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            border-bottom: 3px solid black;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }
        
        nav ul {
            display: flex;
            justify-content: center;
            list-style: none;
            padding: 0;
        }
        
        nav li {
            margin: 0 15px;
        }
        
        nav a {
            color: #7b0828;
            text-decoration: none;
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        
        nav a:hover {
            background-color: #f0f0f0;
        }

        .nav-img {
            width: 30%;
            height: 50%;
            text-align: center;
        }

        .nav-img a img{
            width: 30%;
            height: 10%;
        }
        
        /* Main content styles */
        main {
            min-height: calc(100vh - 120px);
            padding: 20px;
        }
        
        .content-section {
            margin: 20px 0;
            padding: 20px;
            border-radius: 5px;
        }

        .content-format {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }

        .content-div {
            background-color: lightgray;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 5px;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .content-div:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        h1 {
            color: #7b0828;
            text-align: center;
            margin-top: 30px;
        }

        h2 {
            color: #7b0828;
            margin-top: 0;
        }

        h2 a {
            color: inherit;
            text-decoration: none;
        }

        h2 a:hover {
            text-decoration: underline;
        }
        
        /* Footer styles */
        footer {
            background-color: #ffffff;
            color: #7b0828;
            text-align: center;
            padding: 15px 0;
            border-top: 3px solid black;
        }
        
        /* List styles */
        ul {
            list-style: none;
            padding: 0;
            max-width: 300px;
            margin: 30px auto;
        }
        
        ul li {
            margin-bottom: 10px;
        }
        
        ul li a {
            display: block;
            padding: 10px;
            background-color: #f8f8f8;
            color: #7b0828;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        
        ul li a:hover {
            background-color: #e8e8e8;
        }

        .logo-placeholder {
            width: 50px;
            height: 50px;
            background-color: #7b0828;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <nav>
        <div class="nav-img">
            <a href="index_adm.php"><img src="assets/Logo.png" alt="Logo do site"></a>
        </div>
        <ul>
            <li><a href="index_adm.php">Home</a></li>
            <li><a href="paginas_adm/lista_usuarios.php">Listar Usuários</a></li>
            <li><a href="#">Verficação de posts</a></li>
            <li><a href="#">Relatórios</a></li>
        </ul>
    </nav>

    <main>
        <div class="content-section">
            <h1>Home do moderador - Em desenvolvimento</h1>
        </div>
        
        <!-- Grid content sections -->
        <div class="content-format">
            <div class="content-div">
                <h2><a href="paginas_adm/lista_usuarios.php">Lista de usuários</a></h2>
                <p>Lista de todos usuários cadastrados no sistema.</p>
            </div>

            <div class="content-div">
                <h2><a href="#">Verificação de posts</a></h2>
                <p>Gerencie e verifique os posts dos usuários.</p>
            </div>

            <div class="content-div">
                <h2><a href="#">Relatórios</a></h2>
                <p>Acesse relatórios e estatísticas do sistema.</p>
            </div>
        </div>
    </main>

    <footer>
        <p>AcheiNaPUC - &copy; <?php echo date('Y'); ?></p>
    </footer>
</body>
</html>