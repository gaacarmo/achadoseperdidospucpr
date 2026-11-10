<?php
$id = (int)$post['postagem_id'];
$found = $post['postagem_usuario_tipo'] === 'Achei';
$cat_icons = ['Eletrônico' => 'device', 'Documentos' => 'id', 'Roupa' => 'shirt', 'Acessório' => 'glasses', 'Material' => 'book', 'Outro' => 'box'];

$comment_html = function (array $c, bool $is_reply) use ($post): void { ?>
    <div class="comment <?= $is_reply ? 'is-reply' : '' ?>" id="c<?= (int)$c['comentario_id'] ?>">
        <?= avatar_html($c, 'sm') ?>
        <div class="comment-body">
            <p class="comment-head">
                <b><?= e($c['nome_usuario']) ?></b>
                <?php if ((int)$c['usuario_id'] === (int)$post['id_usuario']): ?><span class="tag">autor</span><?php endif; ?>
                <?php if ($c['comentario_privado']): ?><span class="tag tag-private"><?= icon('lock') ?>privado</span><?php endif; ?>
                <time class="muted" datetime="<?= e(local_dt($c['comentario_data'])->format(DATE_ATOM)) ?>"><?= time_ago($c['comentario_data']) ?></time>
            </p>
            <p class="comment-text"><?= nl2br(e($c['comentario_conteudo'])) ?></p>
        </div>
    </div>
<?php };
?>
<a class="back" href="/"><?= icon('arrow-left') ?>Todos os itens</a>

<article class="detail">
    <div class="detail-media">
        <?php if ($post['postagem_image']): ?>
            <img src="/<?= e($post['postagem_image']) ?>" alt="Foto de <?= e($post['postagem_nome']) ?>">
        <?php else: ?>
            <span class="media-placeholder cat-<?= array_search($post['postagem_categoria'], CATEGORIAS, true) ?: 0 ?>"><?= icon($cat_icons[$post['postagem_categoria']] ?? 'box') ?></span>
        <?php endif; ?>
    </div>

    <div class="detail-info">
        <div class="badges">
            <span class="badge badge-<?= $found ? 'found' : 'lost' ?>"><?= $found ? 'Achado' : 'Perdido' ?></span>
            <?php if ($post['postagem_resolvida']): ?><span class="badge badge-done"><?= icon('check') ?>Devolvido</span><?php endif; ?>
            <?php if ($post['id_admin']): ?><span class="badge badge-verified"><?= icon('verified') ?>Verificado</span><?php endif; ?>
        </div>
        <h1><?= e($post['postagem_nome']) ?></h1>
        <?php if ($post['postagem_descricao']): ?><p class="lead"><?= nl2br(e($post['postagem_descricao'])) ?></p><?php endif; ?>

        <dl class="facts">
            <div><dt><?= icon('pin') ?>Local</dt><dd><?= e($post['postagem_local']) ?></dd></div>
            <div><dt><?= icon('calendar') ?><?= $found ? 'Encontrado em' : 'Perdido em' ?></dt><dd><?= fmt_date($post['postagem_data']) ?></dd></div>
            <div><dt><?= icon('palette') ?>Cor</dt><dd><?= e($post['postagem_cor']) ?></dd></div>
            <div><dt><?= icon($cat_icons[$post['postagem_categoria']] ?? 'box') ?>Categoria</dt><dd><?= e($post['postagem_categoria']) ?></dd></div>
        </dl>

        <div class="detail-author">
            <?= avatar_html($post, 'md') ?>
            <div>
                <b><?= e($post['nome']) ?></b>
                <span class="muted">@<?= e($post['nome_usuario']) ?> · <?= e($post['curso_usuario']) ?> · <?= time_ago($post['postagem_timestamp']) ?></span>
            </div>
        </div>

        <div class="detail-actions">
            <form method="post" action="/curtir.php">
                <?= csrf_field() ?>
                <input type="hidden" name="postagem_id" value="<?= $id ?>">
                <button type="submit" class="pill-btn pill-lg <?= $post['usuario_curtiu'] ? 'is-on' : '' ?>" aria-pressed="<?= $post['usuario_curtiu'] ? 'true' : 'false' ?>">
                    <?= icon('heart') ?><span><?= (int)$post['total_curtidas'] ?></span>
                </button>
            </form>
            <?php if ($is_owner): ?>
                <form method="post" action="/gerenciar.php">
                    <?= csrf_field() ?>
                    <input type="hidden" name="postagem_id" value="<?= $id ?>">
                    <?php if ($post['postagem_resolvida']): ?>
                        <button class="btn btn-ghost btn-sm" name="acao" value="reabrir"><?= icon('undo') ?>Reabrir</button>
                    <?php else: ?>
                        <button class="btn btn-primary btn-sm" name="acao" value="resolver"><?= icon('check') ?>Marcar como devolvido</button>
                    <?php endif; ?>
                    <button class="btn btn-ghost btn-sm" name="acao" value="remover" data-confirm="Remover esta publicação?"><?= icon('trash') ?>Remover</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</article>

<section class="comments" id="comentarios" aria-labelledby="comentarios-titulo">
    <h2 id="comentarios-titulo">Conversa <span class="muted"><?= count($threads) + array_sum(array_map('count', $replies)) ?></span></h2>

    <?php if (!$threads): ?>
        <p class="muted comments-empty"><?= $found ? 'É seu? Comente para combinar a devolução.' : 'Viu esse item por aí? Deixe um comentário.' ?></p>
    <?php endif; ?>

    <?php foreach ($threads as $c): ?>
        <div class="thread">
            <?php $comment_html($c, false); ?>
            <?php foreach ($replies[(int)$c['comentario_id']] ?? [] as $r) $comment_html($r, true); ?>
            <?php if ($user): ?>
                <details class="reply">
                    <summary><?= icon('reply') ?>Responder</summary>
                    <form method="post" action="/comentar.php" class="comment-form">
                        <?= csrf_field() ?>
                        <input type="hidden" name="postagem_id" value="<?= $id ?>">
                        <input type="hidden" name="comentario_pai_id" value="<?= (int)$c['comentario_id'] ?>">
                        <textarea name="comentario_conteudo" rows="2" maxlength="500" required placeholder="Responder a @<?= e($c['nome_usuario']) ?>…"></textarea>
                        <div class="comment-form-foot">
                            <label class="check"><input type="checkbox" name="comentario_privado" value="1"> Privado <span class="muted">(só você, o autor do item e moderadores veem)</span></label>
                            <button class="btn btn-primary btn-sm" type="submit">Enviar</button>
                        </div>
                    </form>
                </details>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

    <?php if ($user): ?>
        <form method="post" action="/comentar.php" class="comment-form comment-new">
            <?= csrf_field() ?>
            <input type="hidden" name="postagem_id" value="<?= $id ?>">
            <label class="sr-only" for="novo-comentario">Comentário</label>
            <textarea id="novo-comentario" name="comentario_conteudo" rows="3" maxlength="500" required placeholder="Escreva um comentário…"></textarea>
            <div class="comment-form-foot">
                <label class="check"><input type="checkbox" name="comentario_privado" value="1"> Privado <span class="muted">(só você, o autor do item e moderadores veem)</span></label>
                <button class="btn btn-primary btn-sm" type="submit"><?= icon('send') ?>Comentar</button>
            </div>
        </form>
    <?php elseif (!$mod): ?>
        <p class="signin-cta"><a href="<?= e(url('entrar.php', ['voltar' => "/item.php?id=$id#comentarios"])) ?>">Entre</a> para comentar e combinar a devolução.</p>
    <?php endif; ?>
</section>
