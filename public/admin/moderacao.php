<?php
declare(strict_types=1);
require __DIR__ . '/../../app/bootstrap.php';
$mod = require_mod();

if (is_post()) {
    $id = (int)($_POST['postagem_id'] ?? 0);
    switch ($_POST['acao'] ?? '') {
        case 'verificar':
            q('UPDATE Postagem SET id_admin = ? WHERE postagem_id = ?', [$mod['moderador_id'], $id]);
            flash('success', 'Publicação verificada.');
            break;
        case 'remover':
            q('UPDATE Postagem SET postagem_ativa = 0 WHERE postagem_id = ?', [$id]);
            flash('success', 'Publicação removida.');
            break;
    }
    redirect('/admin/moderacao.php');
}

$pendentes = q('SELECT p.*, u.nome_usuario, u.nome, u.foto_perfil FROM Postagem p
    JOIN Usuario u ON u.usuario_id = p.id_usuario
    WHERE p.postagem_ativa = 1 AND p.id_admin IS NULL AND u.usuario_ativo = 1
    ORDER BY p.postagem_id DESC')->fetchAll();

render('admin/moderacao', compact('pendentes') + ['title' => 'Moderação', 'active' => 'moderacao']);
