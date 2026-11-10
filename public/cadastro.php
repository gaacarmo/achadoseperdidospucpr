<?php
declare(strict_types=1);
require __DIR__ . '/../app/bootstrap.php';
require APP_ROOT . '/app/cursos.php';

if (current_user()) redirect('/');

$v = ['nome' => '', 'nome_usuario' => '', 'email' => '', 'curso_usuario' => ''];
$erros = [];

if (is_post()) {
    $v = [
        'nome' => post_str('nome', 100),
        'nome_usuario' => post_str('nome_usuario', 40),
        'email' => post_str('email', 100),
        'curso_usuario' => post_str('curso_usuario', 80),
    ];
    $senha = (string)($_POST['senha'] ?? '');

    if (mb_strlen($v['nome']) < 2) $erros['nome'] = 'Informe seu nome.';
    if (!preg_match('/^[a-zA-Z0-9._]{3,20}$/', $v['nome_usuario'])) $erros['nome_usuario'] = 'Use 3 a 20 letras, números, ponto ou _.';
    if (!filter_var($v['email'], FILTER_VALIDATE_EMAIL)) $erros['email'] = 'E-mail inválido.';
    if (!in_array($v['curso_usuario'], CURSOS, true)) $erros['curso_usuario'] = 'Selecione seu curso.';
    if (strlen($senha) < 8) $erros['senha'] = 'A senha precisa ter pelo menos 8 caracteres.';

    if (!$erros) {
        $taken_user = q('SELECT 1 FROM Usuario WHERE nome_usuario = ?', [$v['nome_usuario']])->fetchColumn()
            || q('SELECT 1 FROM Moderador WHERE moderador_usuario_nome = ?', [$v['nome_usuario']])->fetchColumn();
        if ($taken_user) $erros['nome_usuario'] = 'Esse nome de usuário já está em uso.';
        if (q('SELECT 1 FROM Usuario WHERE email = ?', [$v['email']])->fetchColumn()) $erros['email'] = 'Já existe uma conta com esse e-mail.';
    }

    if (!$erros) {
        q('INSERT INTO Usuario (nome, nome_usuario, email, senha, curso_usuario) VALUES (?, ?, ?, ?, ?)',
            [$v['nome'], $v['nome_usuario'], mb_strtolower($v['email']), password_hash($senha, PASSWORD_DEFAULT), $v['curso_usuario']]);
        login_as('usuario_id', (int)db()->lastInsertId());
        flash('success', 'Conta criada. Bem-vindo ao AcheiNaPuc!');
        redirect('/');
    }
}

render('cadastro', compact('v', 'erros') + ['title' => 'Criar conta', 'active' => 'entrar']);
