<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}

require_once 'paginas/conecta_db.php';
$obj = conecta_db();

// Processar ações (verificar ou deletar)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['acao'])) {
        $postagem_id = $_POST['postagem_id'];
        
        
        if ($_POST['acao'] === 'verificar' && isset($_SESSION['moderador_id'])) {
            // Marcar postagem como verificada
            $stmt = $obj->prepare("UPDATE Postagem SET id_admin = ? WHERE postagem_id = ?");
            $stmt->bind_param("ii", $_SESSION['moderador_id'], $postagem_id);
            $stmt->execute();
            
        } elseif ($_POST['acao'] === 'deletar' && isset($_SESSION['moderador_id'])) {
            // Deletar postagem
            $stmt = $obj->prepare("UPDATE Postagem SET postagem_ativa = FALSE WHERE postagem_id = ?");
            $stmt->bind_param("i", $postagem_id);
            $stmt->execute();
        }
        
        // Recarregar a página para evitar reenvio do formulário
        header("Location: include_adm.php?dir=paginas_adm&file=verificacao_post");
        exit();
    }
}

$query = "SELECT p.*, u.nome_usuario, u.foto_perfil, u.usuario_ativo
        FROM Postagem p 
        LEFT JOIN Usuario u ON p.id_usuario = u.usuario_id 
        WHERE p.id_admin IS NULL AND (u.usuario_ativo IS NULL OR u.usuario_ativo != false) AND p.postagem_ativa = 1
        ORDER BY p.postagem_id DESC";
$resultado = $obj->query($query);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificação de Publicações</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .container {
            max-width: 1200px;
        }
        
        .post-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 15px;
            display: flex;
            transition: transform 0.3s ease;
            position: relative;
        }
        
        .post-container:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        }
        
        .post-container img {
            width: 200px;
            height: 150px;
            object-fit: cover;
            border-radius: 6px;
            margin-right: 20px;
        }
        
        .post-content {
            flex: 1;
        }
        
        .post-content h5 {
            font-size: 1.25rem;
            margin-bottom: 10px;
            color: #333;
        }
        
        .post-content p {
            font-size: 0.9rem;
            color: #555;
            margin-bottom: 8px;
        }
        
        .badge {
            font-size: 0.75rem;
            padding: 5px 8px;
            margin-left: 8px;
        }
        
        .text-muted {
            font-size: 0.8rem;
        }
        
        .post-actions {
            position: absolute;
            top: 15px;
            right: 15px;
            display: flex;
            gap: 10px;
        }
        
        .btn-verify {
            background-color: #28a745;
            color: white;
            border: none;
        }
        
        .btn-verify:hover {
            background-color: #218838;
        }
        
        .btn-delete {
            background-color: #dc3545;
            color: white;
            border: none;
        }
        
        .btn-delete:hover {
            background-color: #c82333;
        }
        
        .verified-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            color: #28a745;
            font-size: 1.5rem;
        }
        
        @media (max-width: 768px) {
            .post-container {
                flex-direction: column;
            }
            
            .post-container img {
                width: 100%;
                height: auto;
                margin-right: 0;
                margin-bottom: 15px;
            }
            
            .post-actions {
                position: static;
                margin-top: 10px;
                justify-content: flex-end;
            }
        }
    </style>
</head>
<body>
    <div class="content">
        <div class="container mt-4">
            <h1 class="mb-4 text-center">
                <i class="fas fa-check-circle"></i> Verificação de Publicações
            </h1>
            
            <h2 class="mb-4">
                <i class="fas fa-newspaper"></i> Postagens Recentes 
            </h2>

            <?php 
            if($resultado->num_rows > 0):
                while ($linha = $resultado->fetch_assoc()): ?>
                    <div class="post-container">
                        <?php if (!empty($linha['postagem_image'])): ?>
                            <img src="<?= htmlspecialchars($linha['postagem_image']) ?>" alt="Imagem do objeto" class="img-fluid">
                        <?php endif; ?>
                        
                        <div class="post-content">
                            <h5>
                                <?= htmlspecialchars($linha['postagem_nome']) ?>
                                <span class="badge bg-<?= $linha['postagem_usuario_tipo'] === 'Achei' ? 'success' : 'danger' ?>">
                                    <?= htmlspecialchars($linha['postagem_usuario_tipo']) ?>
                                </span>
                            </h5>
                            <p class="post-description"><?= htmlspecialchars($linha['postagem_descricao']) ?></p>
                            <p class="post-meta">
                                <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($linha['postagem_local']) ?> |
                                <i class="fas fa-calendar"></i> <?= htmlspecialchars($linha['postagem_data']) ?>
                            </p>
                            <?php if (!empty($linha['nome_usuario'])): ?>
                                <p class="text-muted mb-1">
                                    <i class="fas fa-user"></i> Postado por: <?= htmlspecialchars($linha['nome_usuario']) ?>
                                </p>
                            <?php endif; ?>
                        </div>
                        
                        <div class="post-actions">
                            <form method="post" style="display: inline;">
                                <input type="hidden" name="postagem_id" value="<?= $linha['postagem_id'] ?>">
                                <input type="hidden" name="acao" value="verificar">
                                <button type="submit" class="btn btn-sm btn-verify" title="Marcar como verificado">
                                    <i class="fas fa-check"></i> Verificar
                                </button>
                            </form>
                            
                            <form method="post" style="display: inline;">
                                <input type="hidden" name="postagem_id" value="<?= $linha['postagem_id'] ?>">
                                <input type="hidden" name="acao" value="deletar">
                                <button type="submit" class="btn btn-sm btn-delete" title="Excluir postagem">
                                    <i class="fas fa-trash"></i> Excluir
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Nenhuma postagem encontrada
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Adicionar confirmação antes de deletar
        document.querySelectorAll('form[action*="deletar"]').forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!confirm('Tem certeza que deseja excluir esta postagem permanentemente?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>