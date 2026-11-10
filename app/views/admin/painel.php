<?php
$max_dia = max(1, ...array_values($por_dia));
$max_eng = max(1, ...array_map(fn($r) => $r['curtidas'] + $r['comentarios'], $engajadas ?: [['curtidas' => 0, 'comentarios' => 0]]));
$max_cat = max(1, ...array_column($categorias ?: [['n' => 0]], 'n'));
$h = fn(int $v, int $max) => 'h-' . (int)round($v / $max * 20) * 5;
?>
<section class="hero hero-sm">
    <p class="eyebrow">Moderação</p>
    <h1>Painel</h1>
</section>

<div class="stats">
    <div class="stat"><span>Itens ativos</span><b><?= (int)$totais['posts'] ?></b></div>
    <div class="stat"><span>Devolvidos</span><b><?= (int)$totais['devolvidos'] ?></b></div>
    <a class="stat stat-link" href="/admin/moderacao.php"><span>Aguardando verificação</span><b><?= (int)$totais['pendentes'] ?></b></a>
    <div class="stat"><span>Usuários</span><b><?= (int)$totais['usuarios'] ?></b></div>
    <div class="stat"><span>Comentários</span><b><?= (int)$totais['comentarios'] ?></b></div>
</div>

<div class="admin-grid">
    <section class="panel">
        <h2 class="section-title">Publicações por dia <span class="muted">últimos 14 dias</span></h2>
        <div class="columns" role="img" aria-label="Gráfico de publicações por dia">
            <?php foreach ($por_dia as $d => $n): ?>
                <div class="col" title="<?= e(fmt_date($d)) ?>: <?= $n ?>">
                    <span class="col-val"><?= $n ?: '' ?></span>
                    <span class="col-bar <?= $h($n, $max_dia) ?>"></span>
                    <span class="col-label"><?= e(substr($d, 8, 2)) ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="panel">
        <h2 class="section-title">Por categoria</h2>
        <ul class="bars">
            <?php foreach ($categorias as $c): ?>
                <li><span class="bar-label"><?= e($c['c']) ?></span><span class="bar"><span class="bar-fill w-<?= (int)round($c['n'] / $max_cat * 20) * 5 ?>"></span></span><b><?= (int)$c['n'] ?></b></li>
            <?php endforeach; ?>
        </ul>
    </section>

    <section class="panel">
        <h2 class="section-title">Mais engajadas</h2>
        <ul class="bars">
            <?php foreach ($engajadas as $p): $t = $p['curtidas'] + $p['comentarios']; ?>
                <li><a class="bar-label" href="/item.php?id=<?= (int)$p['postagem_id'] ?>"><?= e($p['postagem_nome']) ?></a><span class="bar"><span class="bar-fill w-<?= (int)round($t / $max_eng * 20) * 5 ?>"></span></span><b title="<?= (int)$p['curtidas'] ?> curtidas, <?= (int)$p['comentarios'] ?> comentários"><?= $t ?></b></li>
            <?php endforeach; ?>
        </ul>
    </section>

    <section class="panel">
        <h2 class="section-title">Usuários mais ativos</h2>
        <ul class="people">
            <?php foreach ($ativos as $u): ?>
                <li><?= avatar_html($u, 'sm') ?><span><b><?= e($u['nome']) ?></b><span class="muted">@<?= e($u['nome_usuario']) ?></span></span><span class="muted"><?= (int)$u['posts'] ?> itens · <?= (int)$u['comentarios'] ?> coment.</span></li>
            <?php endforeach; ?>
        </ul>
    </section>
</div>
