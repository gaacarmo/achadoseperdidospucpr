<?php
$err = fn(string $k) => isset($erros[$k]) ? '<span class="field-error">' . e($erros[$k]) . '</span>' : '';
$inv = fn(string $k) => isset($erros[$k]) ? ' aria-invalid="true"' : '';
?>
<a class="back" href="/"><?= icon('arrow-left') ?>Voltar</a>
<div class="panel narrow">
    <h1>Publicar item</h1>
    <p class="muted">Quanto mais detalhes, mais fácil o item voltar para o dono.</p>

    <form method="post" enctype="multipart/form-data" class="form" novalidate>
        <?= csrf_field() ?>
        <fieldset class="toggle-pair">
            <legend class="sr-only">Tipo</legend>
            <label><input type="radio" name="postagem_usuario_tipo" value="Perdi" <?= $v['postagem_usuario_tipo'] !== 'Achei' ? 'checked' : '' ?>><span><?= icon('search') ?>Perdi algo</span></label>
            <label><input type="radio" name="postagem_usuario_tipo" value="Achei" <?= $v['postagem_usuario_tipo'] === 'Achei' ? 'checked' : '' ?>><span><?= icon('hand') ?>Achei algo</span></label>
        </fieldset>
        <?= $err('postagem_usuario_tipo') ?>

        <label class="field">
            <span>O que é?</span>
            <input name="postagem_nome" value="<?= e($v['postagem_nome']) ?>" required maxlength="100" placeholder="Ex.: Fone de ouvido Bluetooth"<?= $inv('postagem_nome') ?>>
            <?= $err('postagem_nome') ?>
        </label>

        <label class="field">
            <span>Descrição <em class="muted">opcional</em></span>
            <textarea name="postagem_descricao" rows="3" maxlength="500" placeholder="Marcas, adesivos, detalhes que só o dono saberia…"><?= e($v['postagem_descricao']) ?></textarea>
        </label>

        <div class="form-row">
            <label class="field">
                <span>Local</span>
                <input name="postagem_local" value="<?= e($v['postagem_local']) ?>" required maxlength="80" placeholder="Ex.: Biblioteca Central" list="locais"<?= $inv('postagem_local') ?>>
                <datalist id="locais">
                    <option>Biblioteca Central</option><option>Restaurante Universitário</option><option>Bloco Azul</option>
                    <option>Bloco Amarelo</option><option>Bloco Verde</option><option>Ginásio de esportes</option>
                    <option>Praça da Reitoria</option><option>Estacionamento</option>
                </datalist>
                <?= $err('postagem_local') ?>
            </label>
            <label class="field">
                <span>Data</span>
                <input type="date" name="postagem_data" value="<?= e($v['postagem_data']) ?>" max="<?= date('Y-m-d') ?>" required<?= $inv('postagem_data') ?>>
                <?= $err('postagem_data') ?>
            </label>
        </div>

        <div class="form-row">
            <label class="field">
                <span>Categoria</span>
                <select name="postagem_categoria" required<?= $inv('postagem_categoria') ?>>
                    <option value="">Selecione</option>
                    <?php foreach (CATEGORIAS as $c): ?><option <?= $v['postagem_categoria'] === $c ? 'selected' : '' ?>><?= e($c) ?></option><?php endforeach; ?>
                </select>
                <?= $err('postagem_categoria') ?>
            </label>
            <label class="field">
                <span>Cor</span>
                <input name="postagem_cor" value="<?= e($v['postagem_cor']) ?>" required maxlength="30" placeholder="Ex.: Preto"<?= $inv('postagem_cor') ?>>
                <?= $err('postagem_cor') ?>
            </label>
        </div>

        <div class="field">
            <span>Foto <em class="muted">opcional</em></span>
            <label class="dropzone" data-dropzone>
                <input type="file" name="postagem_image" accept="image/jpeg,image/png,image/webp,image/gif">
                <img alt="" hidden data-preview>
                <span class="dropzone-text"><?= icon('image') ?><b>Escolher foto</b> ou arrastar aqui<small>JPG, PNG, WebP ou GIF até 3 MB</small></span>
            </label>
            <?= $err('postagem_image') ?>
        </div>

        <button class="btn btn-primary btn-block" type="submit"><?= icon('send') ?>Publicar</button>
    </form>
</div>
