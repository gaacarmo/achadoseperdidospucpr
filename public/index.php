<?php
declare(strict_types=1);
require __DIR__ . '/../app/bootstrap.php';

$tipo = in_array($_GET['tipo'] ?? '', TIPOS, true) ? $_GET['tipo'] : '';
$categoria = in_array($_GET['categoria'] ?? '', CATEGORIAS, true) ? $_GET['categoria'] : '';
$busca = mb_substr(trim((string)($_GET['q'] ?? '')), 0, 60);
$user = current_user();

$where = ['p.postagem_ativa = 1', 'u.usuario_ativo = 1'];
$params = [];
if ($tipo) { $where[] = 'p.postagem_usuario_tipo = ?'; $params[] = $tipo; }
if ($categoria) { $where[] = 'p.postagem_categoria = ?'; $params[] = $categoria; }
if ($busca !== '') {
    $where[] = "(p.postagem_nome LIKE ? ESCAPE '!' OR p.postagem_descricao LIKE ? ESCAPE '!' OR p.postagem_local LIKE ? ESCAPE '!' OR p.postagem_cor LIKE ? ESCAPE '!')";
    $like = '%' . str_replace(['!', '%', '_'], ['!!', '!%', '!_'], $busca) . '%';
    array_push($params, $like, $like, $like, $like);
}

$posts = q(
    'SELECT p.*, u.nome_usuario, u.nome, u.foto_perfil,
        (SELECT COUNT(*) FROM Curtidas c WHERE c.postagem_id = p.postagem_id) AS total_curtidas,
        (SELECT COUNT(*) FROM Comentarios c WHERE c.postagem_id = p.postagem_id AND c.comentario_privado = 0) AS total_comentarios,
        (SELECT COUNT(*) FROM Curtidas c WHERE c.postagem_id = p.postagem_id AND c.usuario_id = ?) AS usuario_curtiu
     FROM Postagem p JOIN Usuario u ON u.usuario_id = p.id_usuario
     WHERE ' . implode(' AND ', $where) . '
     ORDER BY p.postagem_resolvida ASC, p.postagem_timestamp DESC, p.postagem_id DESC
     LIMIT 60',
    array_merge([$user['usuario_id'] ?? 0], $params)
)->fetchAll();

$stats = q('SELECT
    SUM(CASE WHEN postagem_resolvida = 0 THEN 1 ELSE 0 END) AS abertos,
    SUM(CASE WHEN postagem_resolvida = 1 THEN 1 ELSE 0 END) AS devolvidos
    FROM Postagem WHERE postagem_ativa = 1')->fetch();

$filtering = $tipo || $categoria || $busca !== '';
render('feed', compact('posts', 'tipo', 'categoria', 'busca', 'stats', 'filtering', 'user') + ['active' => 'feed']);
