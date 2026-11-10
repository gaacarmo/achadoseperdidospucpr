<?php
$err = fn(string $k) => isset($erros[$k]) ? '<span class="field-error">' . e($erros[$k]) . '</span>' : '';
$inv = fn(string $k) => isset($erros[$k]) ? ' aria-invalid="true"' : '';
?>
<div class="auth">
    <div class="auth-card auth-wide">
        <h1>Criar conta</h1>
        <p class="muted">Leva menos de um minuto.</p>
        <form method="post" class="form" novalidate>
            <?= csrf_field() ?>
            <div class="form-row">
                <label class="field">
                    <span>Nome completo</span>
                    <input name="nome" value="<?= e($v['nome']) ?>" required maxlength="100" autocomplete="name"<?= $inv('nome') ?>>
                    <?= $err('nome') ?>
                </label>
                <label class="field">
                    <span>Nome de usuário</span>
                    <input name="nome_usuario" value="<?= e($v['nome_usuario']) ?>" required maxlength="20" pattern="[a-zA-Z0-9._]{3,20}" autocomplete="username"<?= $inv('nome_usuario') ?>>
                    <?= $err('nome_usuario') ?>
                </label>
            </div>
            <div class="form-row">
                <label class="field">
                    <span>E-mail</span>
                    <input type="email" name="email" value="<?= e($v['email']) ?>" required maxlength="100" autocomplete="email"<?= $inv('email') ?>>
                    <?= $err('email') ?>
                </label>
                <label class="field">
                    <span>Senha</span>
                    <input type="password" name="senha" required minlength="8" autocomplete="new-password"<?= $inv('senha') ?>>
                    <?= $err('senha') ?: '<span class="field-hint">Mínimo de 8 caracteres.</span>' ?>
                </label>
            </div>
            <label class="field">
                <span>Curso</span>
                <select name="curso_usuario" required<?= $inv('curso_usuario') ?>>
                    <option value="">Selecione seu curso</option>
                    <?php foreach (CURSOS as $c): ?>
                        <option <?= $v['curso_usuario'] === $c ? 'selected' : '' ?>><?= e($c) ?></option>
                    <?php endforeach; ?>
                </select>
                <?= $err('curso_usuario') ?>
            </label>
            <button class="btn btn-primary btn-block" type="submit">Criar conta</button>
        </form>
        <p class="auth-alt">Já tem conta? <a href="/entrar.php">Entrar</a></p>
    </div>
</div>
