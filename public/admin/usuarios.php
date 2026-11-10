<?php
declare(strict_types=1);
require __DIR__ . '/../../app/bootstrap.php';
require_mod();

if (is_post()) {
    $id = (int)($_POST['usuario_id'] ?? 0);
    $ativo = ($_POST['acao'] ?? '') === 'reativar' ? 1 : 0;
    q('UPDATE Usuario SET usuario_ativo = ? WHERE usuario_id = ?', [$ativo, $id]);
    flash('success', $ativo ? 'Conta reativada.' : 'Conta desativada. As publicações dela saem do feed.');
    redirect('/admin/usuarios.php');
}

$usuarios = q('SELECT u.*, (SELECT COUNT(*) FROM Postagem p WHERE p.id_usuario = u.usuario_id AND p.postagem_ativa = 1) AS posts
               FROM Usuario u ORDER BY u.usuario_ativo DESC, u.usuario_id')->fetchAll();

render('admin/usuarios', compact('usuarios') + ['title' => 'Usuários', 'active' => 'usuarios']);
