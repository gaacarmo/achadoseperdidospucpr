<div class="auth">
    <div class="auth-card">
        <h1>Entrar</h1>
        <p class="muted">Use seu nome de usuário ou e-mail.</p>
        <?php if ($erro): ?><div class="flash flash-error" role="alert"><?= e($erro) ?></div><?php endif; ?>
        <form method="post" class="form">
            <?= csrf_field() ?>
            <input type="hidden" name="voltar" value="<?= e($voltar) ?>">
            <label class="field">
                <span>Usuário ou e-mail</span>
                <input name="nome_usuario" value="<?= e($login) ?>" required autocomplete="username" autofocus>
            </label>
            <label class="field">
                <span>Senha</span>
                <input type="password" name="senha" required autocomplete="current-password">
            </label>
            <button class="btn btn-primary btn-block" type="submit">Entrar</button>
        </form>
        <p class="auth-alt">Ainda não tem conta? <a href="/cadastro.php">Criar conta</a></p>
    </div>
    <aside class="demo-box">
        <p class="eyebrow">Contas de demonstração</p>
        <div class="demo-row"><span>Estudante</span><code>demo</code><code>demo1234</code></div>
        <div class="demo-row"><span>Moderador</span><code>moderador</code><code>modera1234</code></div>
    </aside>
</div>
