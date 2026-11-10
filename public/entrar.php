<?php
declare(strict_types=1);
require __DIR__ . '/../app/bootstrap.php';

if (current_user()) redirect('/');
if (current_mod()) redirect('/admin/');

$voltar = safe_return($_GET['voltar'] ?? $_POST['voltar'] ?? '/');
$erro = '';
$login = '';

if (is_post()) {
    $login = post_str('nome_usuario', 100);
    $senha = (string)($_POST['senha'] ?? '');

    $tries = $_SESSION['login_tries'] ?? [];
    $tries = array_filter($tries, fn($t) => $t > time() - 300);
    if (count($tries) >= 8) {
        $erro = 'Muitas tentativas. Aguarde alguns minutos e tente de novo.';
    } else {
        // Moderadores entram pelo mesmo formulário (como no projeto original).
        $mod = q('SELECT * FROM Moderador WHERE moderador_usuario_nome = ?', [$login])->fetch();
        if ($mod && password_verify($senha, $mod['moderador_senha'])) {
            unset($_SESSION['login_tries']);
            login_as('moderador_id', (int)$mod['moderador_id']);
            flash('success', 'Bem-vindo ao painel de moderação.');
            redirect('/admin/');
        }

        $u = q('SELECT * FROM Usuario WHERE nome_usuario = ? OR email = ?', [$login, $login])->fetch();
        if ($u && password_verify($senha, $u['senha'])) {
            if (!$u['usuario_ativo']) {
                $erro = 'Esta conta foi desativada por um moderador.';
            } else {
                unset($_SESSION['login_tries']);
                if (password_needs_rehash($u['senha'], PASSWORD_DEFAULT)) {
                    q('UPDATE Usuario SET senha = ? WHERE usuario_id = ?', [password_hash($senha, PASSWORD_DEFAULT), $u['usuario_id']]);
                }
                login_as('usuario_id', (int)$u['usuario_id']);
                flash('success', 'Que bom te ver, ' . explode(' ', $u['nome'])[0] . '!');
                redirect($voltar);
            }
        } else {
            $erro = 'Usuário ou senha incorretos.';
        }
        $tries[] = time();
        $_SESSION['login_tries'] = $tries;
    }
}

render('entrar', compact('erro', 'login', 'voltar') + ['title' => 'Entrar', 'active' => 'entrar']);
