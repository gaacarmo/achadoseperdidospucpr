<section class="hero hero-sm">
    <p class="eyebrow">Moderação</p>
    <h1>Usuários <span class="muted"><?= count($usuarios) ?></span></h1>
</section>

<div class="panel table-wrap">
    <table class="table">
        <thead><tr><th>Usuário</th><th>E-mail</th><th>Curso</th><th class="num">Itens</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($usuarios as $u): ?>
            <tr class="<?= $u['usuario_ativo'] ? '' : 'is-off' ?>">
                <td><span class="person"><?= avatar_html($u, 'sm') ?><span><b><?= e($u['nome']) ?></b><span class="muted">@<?= e($u['nome_usuario']) ?></span></span></span></td>
                <td><?= e($u['email']) ?></td>
                <td class="muted"><?= e($u['curso_usuario']) ?></td>
                <td class="num"><?= (int)$u['posts'] ?></td>
                <td class="num">
                    <form method="post">
                        <?= csrf_field() ?>
                        <input type="hidden" name="usuario_id" value="<?= (int)$u['usuario_id'] ?>">
                        <?php if ($u['usuario_ativo']): ?>
                            <button class="btn btn-ghost btn-sm" name="acao" value="desativar" data-confirm="Desativar @<?= e($u['nome_usuario']) ?>?">Desativar</button>
                        <?php else: ?>
                            <button class="btn btn-ghost btn-sm" name="acao" value="reativar">Reativar</button>
                        <?php endif; ?>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
