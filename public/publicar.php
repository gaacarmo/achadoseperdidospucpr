<?php
declare(strict_types=1);
require __DIR__ . '/../app/bootstrap.php';
$user = require_login();

$v = ['postagem_usuario_tipo' => $_GET['tipo'] ?? 'Perdi', 'postagem_nome' => '', 'postagem_descricao' => '', 'postagem_local' => '',
      'postagem_data' => date('Y-m-d'), 'postagem_cor' => '', 'postagem_categoria' => ''];
$erros = [];

if (is_post()) {
    $v = [
        'postagem_usuario_tipo' => post_str('postagem_usuario_tipo', 10),
        'postagem_nome' => post_str('postagem_nome', 100),
        'postagem_descricao' => post_str('postagem_descricao', 500),
        'postagem_local' => post_str('postagem_local', 80),
        'postagem_data' => post_str('postagem_data', 10),
        'postagem_cor' => post_str('postagem_cor', 30),
        'postagem_categoria' => post_str('postagem_categoria', 50),
    ];
    if (!in_array($v['postagem_usuario_tipo'], TIPOS, true)) $erros['postagem_usuario_tipo'] = 'Escolha se você perdeu ou achou.';
    if (mb_strlen($v['postagem_nome']) < 3) $erros['postagem_nome'] = 'Dê um título ao item.';
    if (mb_strlen($v['postagem_local']) < 2) $erros['postagem_local'] = 'Onde foi?';
    if ($v['postagem_cor'] === '') $erros['postagem_cor'] = 'Informe a cor.';
    if (!in_array($v['postagem_categoria'], CATEGORIAS, true)) $erros['postagem_categoria'] = 'Escolha uma categoria.';
    $d = DateTimeImmutable::createFromFormat('!Y-m-d', $v['postagem_data']);
    if (!$d || $d->format('Y-m-d') !== $v['postagem_data'] || $d > new DateTimeImmutable('tomorrow') || $d < new DateTimeImmutable('-2 years')) {
        $erros['postagem_data'] = 'Data inválida.';
    }

    $imagem = null;
    if (!$erros) {
        try {
            $imagem = save_upload('postagem_image', 'item');
        } catch (RuntimeException $ex) {
            $erros['postagem_image'] = $ex->getMessage();
        }
    }

    if (!$erros) {
        q('INSERT INTO Postagem (postagem_nome, postagem_descricao, postagem_local, postagem_cor, postagem_categoria, postagem_data,
            postagem_timestamp, postagem_image, postagem_usuario_tipo, id_usuario) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [$v['postagem_nome'], $v['postagem_descricao'], $v['postagem_local'], $v['postagem_cor'], $v['postagem_categoria'],
             $v['postagem_data'], now_utc(), $imagem, $v['postagem_usuario_tipo'], $user['usuario_id']]);
        flash('success', 'Publicado! Avisaremos a comunidade.');
        redirect('/item.php?id=' . db()->lastInsertId());
    }
}

render('publicar', compact('v', 'erros') + ['title' => 'Publicar item', 'active' => 'publicar']);
