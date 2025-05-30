<?php
// Inicia a sessão se ainda não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Importa o arquivo de conexão com o banco de dados
require_once 'paginas/conecta_db.php';
$obj = conecta_db(); // Conecta ao banco de dados

// Consulta que busca as postagens junto com nome de usuário e foto de perfil
$query = "SELECT p.*, u.nome_usuario, foto_perfil
        FROM Postagem p 
        LEFT JOIN Usuario u ON p.id_usuario = u.usuario_id 
       
        ORDER BY p.postagem_id DESC";
$resultado = $obj->query($query);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <!-- Configurações de codificação e responsividade -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AcheiNaPuc - Início</title>

    <!-- Importa CSS do Bootstrap e Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="./CSS/style.css">
</head>

<body>
<!-- Botão de menu responsivo -->
<button class="mobile-menu-toggle" id="mobileMenuToggle">
    <i class="fas fa-bars"></i>
</button>

<!-- Menu lateral (sidebar) -->
<div class="sidebar" id="sidebar">
    <div>
        <!-- Logo e título -->
        <div class="title"><img src="./images/Group.svg" class="logo-nav" alt="">Achei na PUCPR</div>

        <!-- Link para página inicial -->
        <div class="nav-item">
            <a class="nav-link" href="index.php"><i class="fas fa-home"></i> Início</a>
        </div>

        <!-- Verifica se o usuário está logado -->
        <?php if(isset($_SESSION['is_logged_user']) && $_SESSION['is_logged_user'] === true): ?>
        <div class="nav-item">
            <a class="nav-link" href="include.php?dir=paginas&file=editar"><i class="fas fa-user"></i> Perfil</a>
        </div>
        <?php else: ?>
        <div class="nav-item">
            <a class="nav-link" href="include.php?dir=paginas&file=login"><i class="fas fa-sign-in-alt"></i> Entrar</a>
        </div>
        <?php endif; ?>

        <!-- Link para publicar -->
        <div class="nav-item">
            <a class="nav-link" href="include.php?dir=paginas&file=publicar"><i class="fas fa-plus"></i> Publicar</a>
        </div>

        <!-- Filtros por tipo -->
        <div class="mb-3">
            <label class="form-check-label me-2"><b>Filtrar por tipo de postagem:</b></label>
            <div class="form-check form-check">
                <input class="form-check-input filtro-tipo" type="checkbox" value="Achei" id="filtroAchei">
                <label class="form-check-label" for="filtroAchei">Achei</label>
            </div>
            <div class="form-check form-check">
                <input class="form-check-input filtro-tipo" type="checkbox" value="Perdi" id="filtroPerdi">
                <label class="form-check-label" for="filtroPerdi">Perdi</label>
            </div>

            <!-- Filtros por categoria -->
            <br>
            <div class="mb-3">
                <label class="form-check-label me-2"><b>Filtrar por categoria:</b></label>
                <div class="form-check form-check-inline">
                    <input class="form-check-input filtro-categoria" type="checkbox" value="Eletrônico" id="catEletronico">
                    <label class="form-check-label" for="catEletronico">Eletrônico</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input filtro-categoria" type="checkbox" value="Documentos" id="catDocumentos">
                    <label class="form-check-label" for="catDocumentos">Documentos</label>
                </div>
                <div class="form-check form-check">
                    <input class="form-check-input filtro-categoria" type="checkbox" value="Roupa" id="catRoupa">
                    <label class="form-check-label" for="catRoupa">Roupa</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input filtro-categoria" type="checkbox" value="Outro" id="catOutro">
                    <label class="form-check-label" for="catOutro">Outro</label>
                </div>
            </div>
        </div>

        <!-- Botão extra de publicar -->
        <button class="publicar-btn" onclick="window.location.href='include.php?dir=paginas&file=publicar'">
            <i class="fas fa-plus"></i> Publicar
        </button>
    </div>
</div>

<!-- Conteúdo principal -->
<div class="content">
    <div class="container mt-4">
        <h2 class="mb-4"><i class="fas fa-newspaper"></i> Postagens Recentes</h2>

        <?php if($resultado->num_rows > 0): while ($linha = $resultado->fetch_assoc()): $postId = $linha['postagem_id']; ?>
        <div class="post-container" data-tipo="<?= htmlspecialchars($linha['postagem_usuario_tipo']) ?>" data-categoria="<?= htmlspecialchars($linha['postagem_categoria']) ?>">
            <?php if (!empty($linha['postagem_image'])): ?>
                <img src="<?= htmlspecialchars($linha['postagem_image']) ?>" alt="Imagem do objeto">
            <?php endif; ?>

            <div class="post-content">
                <h5>
                    <?= htmlspecialchars($linha['postagem_nome']) ?>
                    <?php if(!empty($linha['id_admin'])): ?>
                        <i class="fas fa-check-circle text-primary" title="Postagem verificada"></i>
                    <?php endif; ?>
                    <span class="badge bg-<?= $linha['postagem_usuario_tipo'] === 'Achei' ? 'success' : 'danger' ?>">
                        <?= htmlspecialchars($linha['postagem_usuario_tipo']) ?>
                    </span>
                </h5>
                <p><?= htmlspecialchars($linha['postagem_descricao']) ?></p>
                <p><?= htmlspecialchars($linha['postagem_categoria']) ?></p>
                <p><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($linha['postagem_local']) ?> | <i class="fas fa-calendar"></i> <?= htmlspecialchars($linha['postagem_data']) ?></p>
                <?php if (!empty($linha['nome_usuario'])): ?>
                    <p class="text-muted mb-1"><i class="fas fa-user"></i> Postado por: <?= htmlspecialchars($linha['nome_usuario']) ?></p>
                <?php endif; ?>

                <div class="post-actions">
                    <button><i class="far fa-thumbs-up"></i> Curtir</button>
                    <button type="button" class="btn-toggle-comentarios">
                        <h6><i class="fas fa-comments"></i> Comentários</h6>
                    </button>

                    <div class="comentarios mt-3">
                        <?php
                        $stmtComentarios = $obj->prepare("SELECT c.*, u.nome_usuario FROM Comentarios c JOIN Usuario u ON c.usuario_id = u.usuario_id WHERE postagem_id = ? AND comentario_pai_id IS NULL ORDER BY comentario_data DESC");
                        $stmtComentarios->bind_param("i", $postId);
                        $stmtComentarios->execute();
                        $resComentarios = $stmtComentarios->get_result();

                        while ($comentario = $resComentarios->fetch_assoc()):
                        ?>
                        <div class="comentario mb-2">
                            <strong><?= htmlspecialchars($comentario['nome_usuario']) ?>:</strong>
                            <?= htmlspecialchars($comentario['comentario_conteudo']) ?>
                            <?php if ($comentario['comentario_privado']): ?>
                                <span class="badge bg-warning text-dark ms-2">Privado</span>
                            <?php endif; ?>
                            <small class="text-muted d-block"><?= date('d/m/Y H:i', strtotime($comentario['comentario_data'])) ?></small>

                            <?php
                            $comentarioId = $comentario['comentario_id'];
                            $stmtRespostas = $obj->prepare("SELECT r.*, u.nome_usuario FROM Comentarios r JOIN Usuario u ON r.usuario_id = u.usuario_id WHERE comentario_pai_id = ? ORDER BY comentario_data ASC");
                            $stmtRespostas->bind_param("i", $comentarioId);
                            $stmtRespostas->execute();
                            $resRespostas = $stmtRespostas->get_result();

                            while ($resposta = $resRespostas->fetch_assoc()):
                            $visivel = true;

                            if ($resposta['comentario_privado']) {
                                $usuarioAtual = $_SESSION['usuario_id'] ?? null;
                                if ($usuarioAtual !== $linha['id_usuario'] && $usuarioAtual !== $resposta['usuario_id']) {
                                    $visivel = false;
                                }
                            }

                            if ($visivel):
                            ?>
                            <div class="resposta ms-4 mb-2">
                                <strong><?= htmlspecialchars($resposta['nome_usuario']) ?>:</strong>
                                <?= htmlspecialchars($resposta['comentario_conteudo']) ?>
                                <?php if ($resposta['comentario_privado']): ?>
                                    <span class="badge bg-warning text-dark ms-2">Privado</span>
                                <?php endif; ?>
                                <small class="text-muted d-block"><?= date('d/m/Y H:i', strtotime($resposta['comentario_data'])) ?></small>
                            </div>
                            <?php endif; ?>
                            <?php endwhile; ?>

                            <?php if (isset($_SESSION['is_logged_user']) && $_SESSION['is_logged_user']): ?>
                            <button type="button" class="btn btn-sm btn-link btn-toggle-resposta">
                            <i class="fas fa-reply"></i> Responder
                        </button>

                        <form action="./paginas/inserir_comentario.php" method="post" class="mt-2 ms-4 form-resposta" style="display: none;">
                            <input type="hidden" name="postagem_id" value="<?= $postId ?>">
                            <input type="hidden" name="comentario_pai_id" value="<?= $comentarioId ?>">
                            <div class="mb-2">
                                <textarea name="comentario_conteudo" class="form-control" placeholder="Responder comentário..." required></textarea>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="comentario_privado" value="1" id="privado<?= $comentarioId ?>">
                                <label class="form-check-label" for="privado<?= $comentarioId ?>">Comentário privado</label>
                            </div>
                            <button type="submit" class="btn btn-sm btn-secondary">
                                <i class="fas fa-reply"></i> Enviar resposta
                            </button>
                         </form>
                        <?php endif; ?>
                            
                            <form action="./paginas/inserir_comentario.php" method="post" class="mt-2 ms-4 form-resposta" style="display: none;">
                                <input type="hidden" name="postagem_id" value="<?= $postId ?>">
                                <input type="hidden" name="comentario_pai_id" value="<?= $comentarioId ?>">
                                <div class="mb-2">
                                    <textarea name="comentario_conteudo" class="form-control" placeholder="Responder comentário..." required></textarea>
                                </div>
                                <button type="submit" class="btn btn-sm btn-secondary"><i class="fas fa-reply"></i> Enviar resposta</button>
                            </form>
                     
                            <?php endwhile; ?>
                        </div>
                        

                        <?php if (isset($_SESSION['is_logged_user']) && $_SESSION['is_logged_user']): ?>
                        <form action="./paginas/inserir_comentario.php" method="post" class="mt-2">
                            <input type="hidden" name="postagem_id" value="<?= $postId ?>">
                            <div class="mb-2">
                                <textarea name="comentario_conteudo" class="form-control" placeholder="Digite um comentário..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-paper-plane"></i> Enviar</button>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endwhile; else: ?>
        <div class="alert alert-info"><i class="fas fa-info-circle"></i> Nenhuma postagem encontrada</div>
        <?php endif; ?>
    </div>
</div>

<!-- Exibe perfil ou caixa de ajuda ao lado -->
<?php
if(isset($_SESSION['is_logged_user']) && $_SESSION['is_logged_user'] === true) {
    $usuario_id = $_SESSION['usuario_id'];
    $sqlUser = "SELECT foto_perfil FROM Usuario WHERE usuario_id = ?";
    $stmt = $obj->prepare($sqlUser);
    $stmt->bind_param('i', $usuario_id);
    $stmt->execute();
    $resultUser = $stmt->get_result();
    $userData = $resultUser->fetch_assoc();
    $fotoPerfil = $userData['foto_perfil'] ?? 'default.jpg';

    echo "<div class='profile'>
        <img src='{$fotoPerfil}' alt='Foto de perfil'>
        <h3>{$_SESSION['usuario']}</h3>
        <p>@{$_SESSION['usuario']}</p>
        <button class='btn btn-dange' onclick=\"window.location.href='include.php?dir=paginas&file=editar'\">Editar Perfil</button>
        <button class='btn btn-outline-secondary' onclick=\"window.location.href='include.php?dir=paginas&file=del_usu'\">Sair</button>
    </div>";
} else {
    echo "<div class='profile'>
        <div class='side-help-box'>
            <h5><i class='fas fa-question-circle'></i> Como usar o Achei na PUCPR?</h5>
            <ol class='small mt-3'>
                <li>🔍 <strong>Pesquise</strong> se o item já foi publicado.</li>
                <li>📝 <strong>Publique</strong> um novo item se não encontrar.</li>
                <li>📍 Informe <strong>local, data e descrição</strong> do item.</li>
                <li>🖼️ Adicione uma <strong>foto</strong> se quiser.</li>
                <li>💬 Use os <strong>comentários</strong> para combinar devolução.</li>
            </ol>
        </div>
    </div>";
}
?>

<!-- Scripts JS para interação -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Mostrar ou ocultar comentários
    document.querySelectorAll('.btn-toggle-comentarios').forEach(botao => {
        botao.addEventListener('click', function () {
            const comentariosDiv = this.closest('.post-container').querySelector('.comentarios');
            if (comentariosDiv) {
                comentariosDiv.style.display = comentariosDiv.style.display === 'none' || comentariosDiv.style.display === '' ? 'block' : 'none';
            }
        });
    });

    // Oculta todos os comentários inicialmente
    document.querySelectorAll('.comentarios').forEach(div => {
        div.style.display = 'none';
    });

    // Filtros por tipo e categoria
    const tipoCheckboxes = document.querySelectorAll('.filtro-tipo');
    const categoriaCheckboxes = document.querySelectorAll('.filtro-categoria');
    const posts = document.querySelectorAll('.post-container');

    function aplicarFiltros() {
        const tiposSelecionados = Array.from(tipoCheckboxes).filter(cb => cb.checked).map(cb => cb.value);
        const categoriasSelecionadas = Array.from(categoriaCheckboxes).filter(cb => cb.checked).map(cb => cb.value);

        posts.forEach(post => {
            const tipo = post.getAttribute('data-tipo');
            const categoria = post.getAttribute('data-categoria');
            const tipoOK = tiposSelecionados.length === 0 || tiposSelecionados.includes(tipo);
            const categoriaOK = categoriasSelecionadas.length === 0 || categoriasSelecionadas.includes(categoria);
            post.style.display = (tipoOK && categoriaOK) ? '' : 'none';
        });
    }

    tipoCheckboxes.forEach(cb => cb.addEventListener('change', aplicarFiltros));
    categoriaCheckboxes.forEach(cb => cb.addEventListener('change', aplicarFiltros));
});

// Mostrar formulário de resposta
document.querySelectorAll('.btn-toggle-resposta').forEach(botao => {
    botao.addEventListener('click', function () {
        const form = this.nextElementSibling;
        if (form && form.classList.contains('form-resposta')) {
            form.style.display = (form.style.display === 'none' || form.style.display === '') ? 'block' : 'none';
        }
    });
});
</script>

</body>
</html>
