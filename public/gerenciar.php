<?php
// Ações do autor sobre a própria publicação: marcar como devolvido, reabrir, remover.
declare(strict_types=1);
require __DIR__ . '/../app/bootstrap.php';
require_post();
$user = require_login();

$id = (int)($_POST['postagem_id'] ?? 0);
$own = q('SELECT 1 FROM Postagem WHERE postagem_id = ? AND id_usuario = ? AND postagem_ativa = 1', [$id, $user['usuario_id']])->fetchColumn();
if (!$own) not_found();

switch ($_POST['acao'] ?? '') {
    case 'resolver':
        q('UPDATE Postagem SET postagem_resolvida = 1 WHERE postagem_id = ?', [$id]);
        flash('success', 'Que bom! O item foi marcado como devolvido.');
        redirect("/item.php?id=$id");
    case 'reabrir':
        q('UPDATE Postagem SET postagem_resolvida = 0 WHERE postagem_id = ?', [$id]);
        flash('success', 'Publicação reaberta.');
        redirect("/item.php?id=$id");
    case 'remover':
        q('UPDATE Postagem SET postagem_ativa = 0 WHERE postagem_id = ?', [$id]);
        flash('success', 'Publicação removida.');
        redirect('/perfil.php');
}
redirect("/item.php?id=$id");
