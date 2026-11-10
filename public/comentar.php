<?php
declare(strict_types=1);
require __DIR__ . '/../app/bootstrap.php';
require_post();
$user = require_login();

$post_id = (int)($_POST['postagem_id'] ?? 0);
$parent = (int)($_POST['comentario_pai_id'] ?? 0) ?: null;
$texto = post_str('comentario_conteudo', 500);
$privado = isset($_POST['comentario_privado']) ? 1 : 0;

if (!q('SELECT 1 FROM Postagem WHERE postagem_id = ? AND postagem_ativa = 1', [$post_id])->fetchColumn()) not_found();

// Respostas só podem apontar para um comentário raiz do mesmo item.
if ($parent && !q('SELECT 1 FROM Comentarios WHERE comentario_id = ? AND postagem_id = ? AND comentario_pai_id IS NULL', [$parent, $post_id])->fetchColumn()) {
    $parent = null;
}

if ($texto === '') {
    flash('error', 'O comentário está vazio.');
} else {
    q('INSERT INTO Comentarios (comentario_conteudo, comentario_privado, postagem_id, usuario_id, comentario_pai_id, comentario_data) VALUES (?, ?, ?, ?, ?, ?)',
        [$texto, $privado, $post_id, $user['usuario_id'], $parent, now_utc()]);
    $cid = db()->lastInsertId();
}
redirect("/item.php?id=$post_id" . (isset($cid) ? "#c$cid" : '#comentarios'));
