<?php
$err = fn(string $k) => isset($erros[$k]) ? '<span class="field-error">' . e($erros[$k]) . '</span>' : '';
$inv = fn(string $k) => isset($erros[$k]) ? ' aria-invalid="true"' : '';
?>
<section class="profile-head">
    <?= avatar_html($user, 'xl') ?>
    <div>
        <h1><?= e($user['nome']) ?></h1>
        <p class="muted">@<?= e($user['nome_usuario']) ?> · <?= e($user['curso_usuario']) ?></p>
    </div>
    <form method="post" action="/sair.php" class="profile-logout"><?= csrf_field() ?><button class="btn btn-ghost btn-sm" type="submit"><?= icon('logout') ?>Sair</button></form>
</section>

<div class="profile-grid">
    <section>
        <h2 class="section-title">Suas publicações <span class="muted"><?= count($meus) ?></span></h2>
        <?php if (!$meus): ?>
            <div class="empty empty-sm">
                <p>Você ainda não publicou nada.</p>
                <a class="btn btn-primary btn-sm" href="/publicar.php"><?= icon('plus') ?>Publicar item</a>
            </div>
        <?php else: ?>
            <ul class="list">
                <?php foreach ($meus as $p): ?>
                    <li>
                        <a href="/item.php?id=<?= (int)$p['postagem_id'] ?>">
                            <span class="badge badge-<?= $p['postagem_usuario_tipo'] === 'Achei' ? 'found' : 'lost' ?>"><?= $p['postagem_usuario_tipo'] === 'Achei' ? 'Achado' : 'Perdido' ?></span>
                            <b><?= e($p['postagem_nome']) ?></b>
                            <span class="muted list-meta">
                                <?php if ($p['postagem_resolvida']): ?><span class="ok"><?= icon('check') ?>devolvido</span> · <?php endif; ?>
                                <?= (int)$p['total_comentarios'] ?> coment. · <?= time_ago($p['postagem_timestamp']) ?>
                            </span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </section>

    <section class="panel">
        <h2 class="section-title">Editar perfil</h2>
        <form method="post" enctype="multipart/form-data" class="form" novalidate>
            <?= csrf_field() ?>
            <input type="hidden" name="acao" value="dados">
            <div class="field">
                <span>Foto</span>
                <label class="avatar-picker" data-dropzone>
                    <?= avatar_html($user, 'lg') ?>
                    <img class="avatar avatar-lg" alt="" hidden data-preview>
                    <input type="file" name="foto_perfil" accept="image/jpeg,image/png,image/webp,image/gif">
                    <span class="btn btn-ghost btn-sm"><?= icon('image') ?>Trocar foto</span>
                </label>
                <?= $err('foto_perfil') ?>
            </div>
            <label class="field">
                <span>Nome completo</span>
                <input name="nome" value="<?= e($user['nome']) ?>" required maxlength="100"<?= $inv('nome') ?>>
                <?= $err('nome') ?>
            </label>
            <label class="field">
                <span>Nome de usuário</span>
                <input name="nome_usuario" value="<?= e($user['nome_usuario']) ?>" required maxlength="20"<?= $inv('nome_usuario') ?>>
                <?= $err('nome_usuario') ?>
            </label>
            <button class="btn btn-primary" type="submit">Salvar</button>
        </form>

        <details class="pw" <?= $aba === 'senha' ? 'open' : '' ?>>
            <summary><?= icon('lock') ?>Alterar senha</summary>
            <form method="post" class="form" novalidate>
                <?= csrf_field() ?>
                <input type="hidden" name="acao" value="senha">
                <label class="field"><span>Senha atual</span><input type="password" name="senha_atual" required autocomplete="current-password"<?= $inv('senha_atual') ?>><?= $err('senha_atual') ?></label>
                <label class="field"><span>Nova senha</span><input type="password" name="nova_senha" required minlength="8" autocomplete="new-password"<?= $inv('nova_senha') ?>><?= $err('nova_senha') ?></label>
                <label class="field"><span>Confirmar nova senha</span><input type="password" name="confirmar_senha" required autocomplete="new-password"<?= $inv('confirmar_senha') ?>><?= $err('confirmar_senha') ?></label>
                <button class="btn btn-ghost" type="submit">Alterar senha</button>
            </form>
        </details>
    </section>
</div>
