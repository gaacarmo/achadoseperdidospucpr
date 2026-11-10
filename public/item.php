<?php
declare(strict_types=1);
require __DIR__ . '/../app/bootstrap.php';

$id = (int)($_GET['id'] ?? 0);
$user = current_user();
$mod = current_mod();

$post = q(
    'SELECT p.*, u.nome_usuario, u.nome, u.foto_perfil, u.curso_usuario,
        (SELECT COUNT(*) FROM Curtidas c WHERE c.postagem_id = p.postagem_id) AS total_curtidas,
        (SELECT COUNT(*) FROM Curtidas c WHERE c.postagem_id = p.postagem_id AND c.usuario_id = ?) AS usuario_curtiu
     FROM Postagem p JOIN Usuario u ON u.usuario_id = p.id_usuario
     WHERE p.postagem_id = ? AND p.postagem_ativa = 1 AND u.usuario_ativo = 1',
    [$user['usuario_id'] ?? 0, $id]
)->fetch();
if (!$post) not_found();

$is_owner = $user && (int)$user['usuario_id'] === (int)$post['id_usuario'];

$rows = q(
    'SELECT c.*, u.nome_usuario, u.nome, u.foto_perfil
     FROM Comentarios c JOIN Usuario u ON u.usuario_id = c.usuario_id
     WHERE c.postagem_id = ? AND u.usuario_ativo = 1
     ORDER BY c.comentario_data ASC, c.comentario_id ASC',
    [$id]
)->fetchAll();

// Comentários privados só aparecem para quem escreveu, para o dono do item e para moderadores.
$can_see = function (array $c) use ($user, $mod, $is_owner): bool {
    if (!$c['comentario_privado']) return true;
    if ($mod || $is_owner) return true;
    return $user && (int)$user['usuario_id'] === (int)$c['usuario_id'];
};

$threads = [];
$replies = [];
foreach ($rows as $c) {
    if (!$can_see($c)) continue;
    if ($c['comentario_pai_id']) $replies[(int)$c['comentario_pai_id']][] = $c;
    else $threads[] = $c;
}

render('item', compact('post', 'threads', 'replies', 'user', 'mod', 'is_owner') + ['title' => $post['postagem_nome']]);
