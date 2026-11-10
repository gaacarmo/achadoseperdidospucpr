<?php
$cat_icons = ['Eletrônico' => 'device', 'Documentos' => 'id', 'Roupa' => 'shirt', 'Acessório' => 'glasses', 'Material' => 'book', 'Outro' => 'box'];
?>
<section class="hero">
    <p class="eyebrow">Achados e perdidos · PUCPR</p>
    <h1>Perdeu algo no campus?<br><span class="muted">Alguém pode ter achado.</span></h1>
    <p class="hero-stats">
        <span><b><?= (int)$stats['abertos'] ?></b> itens procurando dono</span>
        <span class="dot" aria-hidden="true"></span>
        <span><b><?= (int)$stats['devolvidos'] ?></b> já devolvidos</span>
    </p>
</section>

<form class="filters" method="get" action="/" role="search">
    <label class="search">
        <?= icon('search') ?>
        <span class="sr-only">Buscar</span>
        <input type="search" name="q" value="<?= e($busca) ?>" placeholder="Buscar por item, cor ou local…" autocomplete="off">
    </label>
    <div class="segmented" role="group" aria-label="Tipo">
        <?php foreach (['' => 'Todos', 'Perdi' => 'Perdidos', 'Achei' => 'Achados'] as $val => $label): ?>
            <a href="<?= e(url('', ['tipo' => $val, 'categoria' => $categoria, 'q' => $busca])) ?>" class="<?= $tipo === $val ? 'is-on' : '' ?>"><?= $label ?></a>
        <?php endforeach; ?>
    </div>
    <div class="chips" role="group" aria-label="Categoria">
        <?php foreach (CATEGORIAS as $c): $on = $categoria === $c; ?>
            <a class="chip <?= $on ? 'is-on' : '' ?>" href="<?= e(url('', ['tipo' => $tipo, 'categoria' => $on ? '' : $c, 'q' => $busca])) ?>"><?= icon($cat_icons[$c]) ?><?= e($c) ?></a>
        <?php endforeach; ?>
    </div>
    <noscript><button class="btn btn-ghost btn-sm" type="submit">Buscar</button></noscript>
</form>

<?php if (!$posts): ?>
    <div class="empty">
        <div class="empty-icon"><?= icon($filtering ? 'search' : 'box') ?></div>
        <?php if ($filtering): ?>
            <h2>Nada encontrado</h2>
            <p>Nenhum item bate com esse filtro. Tente outra palavra ou <a href="/">limpe os filtros</a>.</p>
            <a class="btn btn-primary" href="/publicar.php"><?= icon('plus') ?>Publicar um item</a>
        <?php else: ?>
            <h2>Nenhum item por aqui ainda</h2>
            <p>Seja o primeiro a publicar algo que perdeu ou encontrou no campus.</p>
            <a class="btn btn-primary" href="/publicar.php"><?= icon('plus') ?>Publicar um item</a>
        <?php endif; ?>
    </div>
<?php else: ?>
    <div class="grid">
        <?php foreach ($posts as $p): $id = (int)$p['postagem_id']; ?>
            <article class="card <?= $p['postagem_resolvida'] ? 'is-resolved' : '' ?>">
                <a class="card-media" href="/item.php?id=<?= $id ?>" tabindex="-1" aria-hidden="true">
                    <?php if ($p['postagem_image']): ?>
                        <img src="/<?= e($p['postagem_image']) ?>" alt="" loading="lazy">
                    <?php else: ?>
                        <span class="media-placeholder cat-<?= array_search($p['postagem_categoria'], CATEGORIAS, true) ?: 0 ?>"><?= icon($cat_icons[$p['postagem_categoria']] ?? 'box') ?></span>
                    <?php endif; ?>
                    <span class="badge badge-<?= $p['postagem_usuario_tipo'] === 'Achei' ? 'found' : 'lost' ?>"><?= $p['postagem_usuario_tipo'] === 'Achei' ? 'Achado' : 'Perdido' ?></span>
                    <?php if ($p['postagem_resolvida']): ?><span class="badge badge-done"><?= icon('check') ?>Devolvido</span><?php endif; ?>
                </a>
                <div class="card-body">
                    <h2 class="card-title">
                        <a href="/item.php?id=<?= $id ?>"><?= e($p['postagem_nome']) ?></a>
                        <?php if ($p['id_admin']): ?><span class="verified" title="Verificado por um moderador"><?= icon('verified') ?><span class="sr-only">Verificado</span></span><?php endif; ?>
                    </h2>
                    <p class="card-meta"><?= icon('pin') ?><?= e($p['postagem_local']) ?></p>
                    <p class="card-meta"><?= icon('calendar') ?><?= fmt_date($p['postagem_data']) ?> · <?= e($p['postagem_categoria']) ?></p>
                </div>
                <footer class="card-foot">
                    <span class="author"><?= avatar_html($p, 'xs') ?><span title="Publicado <?= e(time_ago($p['postagem_timestamp'])) ?>"><?= e($p['nome_usuario']) ?></span></span>
                    <span class="card-actions">
                        <form method="post" action="/curtir.php">
                            <?= csrf_field() ?>
                            <input type="hidden" name="postagem_id" value="<?= $id ?>">
                            <button type="submit" class="pill-btn <?= $p['usuario_curtiu'] ? 'is-on' : '' ?>" aria-pressed="<?= $p['usuario_curtiu'] ? 'true' : 'false' ?>" aria-label="Curtir">
                                <?= icon('heart') ?><span><?= (int)$p['total_curtidas'] ?></span>
                            </button>
                        </form>
                        <a class="pill-btn" href="/item.php?id=<?= $id ?>#comentarios" aria-label="Comentários"><?= icon('chat') ?><span><?= (int)$p['total_comentarios'] ?></span></a>
                    </span>
                </footer>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
