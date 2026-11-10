<?php
declare(strict_types=1);
require __DIR__ . '/../app/bootstrap.php';
require_post();

$wants_json = str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json');
$user = current_user();
$id = (int)($_POST['postagem_id'] ?? 0);

if (!$user) {
    if ($wants_json) {
        http_response_code(401);
        header('Content-Type: application/json');
        exit(json_encode(['login' => url('entrar.php', ['voltar' => "/item.php?id=$id"])]));
    }
    flash('info', 'Entre na sua conta para curtir.');
    redirect(url('entrar.php', ['voltar' => "/item.php?id=$id"]));
}

$exists = q('SELECT 1 FROM Postagem WHERE postagem_id = ? AND postagem_ativa = 1', [$id])->fetchColumn();
if (!$exists) not_found();

$uid = (int)$user['usuario_id'];
if (q('SELECT 1 FROM Curtidas WHERE postagem_id = ? AND usuario_id = ?', [$id, $uid])->fetchColumn()) {
    q('DELETE FROM Curtidas WHERE postagem_id = ? AND usuario_id = ?', [$id, $uid]);
    $liked = false;
} else {
    q('INSERT INTO Curtidas (postagem_id, usuario_id) VALUES (?, ?)', [$id, $uid]);
    $liked = true;
}

if ($wants_json) {
    header('Content-Type: application/json');
    $count = (int)q('SELECT COUNT(*) FROM Curtidas WHERE postagem_id = ?', [$id])->fetchColumn();
    exit(json_encode(['liked' => $liked, 'count' => $count]));
}
redirect(safe_return(parse_url($_SERVER['HTTP_REFERER'] ?? '', PHP_URL_PATH) . (($qs = parse_url($_SERVER['HTTP_REFERER'] ?? '', PHP_URL_QUERY)) ? "?$qs" : '')));
