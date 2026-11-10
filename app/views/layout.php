<?php
/** @var string $view_file */
$user = current_user();
$mod = current_mod();
$page_title = isset($title) ? $title . ' · ' . APP_NAME : APP_NAME . ' — achados e perdidos da PUCPR';
$active = $active ?? '';
$is_admin_area = str_starts_with($_SERVER['SCRIPT_NAME'] ?? '', '/admin/');
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($page_title) ?></title>
    <meta name="description" content="Achados e perdidos da PUCPR: publique o que perdeu ou encontrou no campus e combine a devolução.">
    <meta name="theme-color" content="#121014" media="(prefers-color-scheme: dark)">
    <meta name="theme-color" content="#f7f4f2" media="(prefers-color-scheme: light)">
    <link rel="icon" href="/assets/logo.svg" type="image/svg+xml">
    <meta property="og:title" content="AcheiNaPuc">
    <meta property="og:description" content="Achados e perdidos da PUCPR.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/app.css?v=5">
    <script src="/assets/app.js?v=5" defer></script>
</head>
<body>
<a class="skip" href="#conteudo">Pular para o conteúdo</a>
<header class="topbar">
    <div class="wrap topbar-inner">
        <a class="brand" href="/" aria-label="AcheiNaPuc — início">
            <img src="/assets/logo.svg" alt="" width="22" height="22">
            <span>AcheiNa<b>Puc</b></span>
        </a>
        <nav class="nav" aria-label="Principal">
            <?php if ($mod): ?>
                <a href="/admin/" class="<?= $active === 'admin' ? 'is-active' : '' ?>"><?= icon('shield') ?><span>Painel</span></a>
                <a href="/admin/moderacao.php" class="<?= $active === 'moderacao' ? 'is-active' : '' ?>"><?= icon('check') ?><span>Moderação</span></a>
                <a href="/admin/usuarios.php" class="<?= $active === 'usuarios' ? 'is-active' : '' ?>"><?= icon('users') ?><span>Usuários</span></a>
                <a href="/" class="<?= $active === 'feed' ? 'is-active' : '' ?>"><?= icon('grid') ?><span>Feed</span></a>
                <form method="post" action="/sair.php"><?= csrf_field() ?><button class="nav-btn" type="submit"><?= icon('logout') ?><span>Sair</span></button></form>
            <?php else: ?>
                <a href="/" class="<?= $active === 'feed' ? 'is-active' : '' ?>"><?= icon('grid') ?><span>Itens</span></a>
                <?php if ($user): ?>
                    <a href="/perfil.php" class="nav-user <?= $active === 'perfil' ? 'is-active' : '' ?>"><?= avatar_html($user, 'xs') ?><span><?= e($user['nome_usuario']) ?></span></a>
                <?php else: ?>
                    <a href="/entrar.php" class="<?= $active === 'entrar' ? 'is-active' : '' ?>"><?= icon('login') ?><span>Entrar</span></a>
                <?php endif; ?>
                <a href="/publicar.php" class="btn btn-primary btn-sm"><?= icon('plus') ?><span>Publicar</span></a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<main id="conteudo" class="wrap main">
    <?php foreach (take_flashes() as [$type, $msg]): ?>
        <div class="flash flash-<?= e($type) ?>" role="status"><?= e($msg) ?></div>
    <?php endforeach; ?>
    <?php require $view_file; ?>
</main>

<footer class="footer">
    <div class="wrap footer-inner">
        <p>Projeto acadêmico de <b>Experiência Criativa</b> · PUCPR, 3º período.<br>
            Feito por Gabriel Carmo, Marcel Araújo e Igor Lara.</p>
        <p class="muted">Demonstração: os dados voltam ao estado inicial de tempos em tempos. Não publique dados reais.</p>
    </div>
</footer>
</body>
</html>
