<?php
declare(strict_types=1);
require __DIR__ . '/../app/bootstrap.php';
$user = require_login();
$uid = (int)$user['usuario_id'];
$erros = [];
$aba = 'dados';

if (is_post()) {
    $acao = $_POST['acao'] ?? '';
    if ($acao === 'dados') {
        $nome = post_str('nome', 100);
        $nome_usuario = post_str('nome_usuario', 40);
        if (mb_strlen($nome) < 2) $erros['nome'] = 'Informe seu nome.';
        if (!preg_match('/^[a-zA-Z0-9._]{3,20}$/', $nome_usuario)) {
            $erros['nome_usuario'] = 'Use 3 a 20 letras, números, ponto ou _.';
        } elseif (q('SELECT 1 FROM Usuario WHERE nome_usuario = ? AND usuario_id <> ?', [$nome_usuario, $uid])->fetchColumn()
            || q('SELECT 1 FROM Moderador WHERE moderador_usuario_nome = ?', [$nome_usuario])->fetchColumn()) {
            $erros['nome_usuario'] = 'Esse nome de usuário já está em uso.';
        }
        $foto = $user['foto_perfil'];
        if (!$erros) {
            try {
                $foto = save_upload('foto_perfil', 'avatar') ?? $foto;
            } catch (RuntimeException $ex) {
                $erros['foto_perfil'] = $ex->getMessage();
            }
        }
        if (!$erros) {
            q('UPDATE Usuario SET nome = ?, nome_usuario = ?, foto_perfil = ? WHERE usuario_id = ?', [$nome, $nome_usuario, $foto, $uid]);
            flash('success', 'Perfil atualizado.');
            redirect('/perfil.php');
        }
        $user = array_merge($user, ['nome' => $nome, 'nome_usuario' => $nome_usuario]);
    } elseif ($acao === 'senha') {
        $aba = 'senha';
        $atual = (string)($_POST['senha_atual'] ?? '');
        $nova = (string)($_POST['nova_senha'] ?? '');
        if (!password_verify($atual, $user['senha'])) $erros['senha_atual'] = 'Senha atual incorreta.';
        elseif (strlen($nova) < 8) $erros['nova_senha'] = 'A nova senha precisa ter pelo menos 8 caracteres.';
        elseif ($nova !== ($_POST['confirmar_senha'] ?? '')) $erros['confirmar_senha'] = 'As senhas não coincidem.';
        if (!$erros) {
            q('UPDATE Usuario SET senha = ? WHERE usuario_id = ?', [password_hash($nova, PASSWORD_DEFAULT), $uid]);
            session_regenerate_id(true);
            flash('success', 'Senha alterada.');
            redirect('/perfil.php');
        }
    }
}

$meus = q('SELECT p.*, (SELECT COUNT(*) FROM Comentarios c WHERE c.postagem_id = p.postagem_id) AS total_comentarios
           FROM Postagem p WHERE p.id_usuario = ? AND p.postagem_ativa = 1 ORDER BY p.postagem_id DESC', [$uid])->fetchAll();

render('perfil', compact('user', 'erros', 'meus', 'aba') + ['title' => 'Seu perfil', 'active' => 'perfil']);
