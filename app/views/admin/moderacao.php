<section class="hero hero-sm">
    <p class="eyebrow">Moderação</p>
    <h1>Aguardando verificação <span class="muted"><?= count($pendentes) ?></span></h1>
</section>

<?php if (!$pendentes): ?>
    <div class="empty">
        <div class="empty-icon"><?= icon('check') ?></div>
        <h2>Tudo em dia</h2>
        <p>Nenhuma publicação esperando verificação.</p>
    </div>
<?php else: ?>
    <ul class="mod-list">
        <?php foreach ($pendentes as $p): $id = (int)$p['postagem_id']; ?>
            <li class="panel mod-item">
                <div>
                    <span class="badge badge-<?= $p['postagem_usuario_tipo'] === 'Achei' ? 'found' : 'lost' ?>"><?= $p['postagem_usuario_tipo'] === 'Achei' ? 'Achado' : 'Perdido' ?></span>
                    <h2><a href="/item.php?id=<?= $id ?>"><?= e($p['postagem_nome']) ?></a></h2>
                    <?php if ($p['postagem_descricao']): ?><p><?= e($p['postagem_descricao']) ?></p><?php endif; ?>
                    <p class="muted"><?= icon('pin') ?><?= e($p['postagem_local']) ?> · @<?= e($p['nome_usuario']) ?> · <?= time_ago($p['postagem_timestamp']) ?></p>
                </div>
                <form method="post" class="mod-actions">
                    <?= csrf_field() ?>
                    <input type="hidden" name="postagem_id" value="<?= $id ?>">
                    <button class="btn btn-primary btn-sm" name="acao" value="verificar"><?= icon('verified') ?>Verificar</button>
                    <button class="btn btn-ghost btn-sm" name="acao" value="remover" data-confirm="Remover esta publicação do feed?"><?= icon('trash') ?>Remover</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
