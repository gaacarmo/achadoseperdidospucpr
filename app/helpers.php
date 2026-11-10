<?php
declare(strict_types=1);

const CATEGORIAS = ['Eletrônico', 'Documentos', 'Roupa', 'Acessório', 'Material', 'Outro'];
const TIPOS = ['Perdi', 'Achei'];

/* ---------- Saída ---------- */

function e(?string $s): string
{
    return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function url(string $path, array $query = []): string
{
    $q = array_filter($query, fn($v) => $v !== null && $v !== '' && $v !== []);
    return '/' . ltrim($path, '/') . ($q ? '?' . http_build_query($q) : '');
}

function redirect(string $to): never
{
    header('Location: ' . $to, true, 303);
    exit;
}

function render(string $view, array $vars = []): void
{
    extract($vars, EXTR_SKIP);
    $view_file = APP_ROOT . '/app/views/' . $view . '.php';
    require APP_ROOT . '/app/views/layout.php';
}

function not_found(): never
{
    http_response_code(404);
    render('erro', ['title' => 'Não encontrado', 'mensagem' => 'Essa página não existe ou o item foi removido.']);
    exit;
}

function icon(string $name, string $class = 'icon'): string
{
    return '<svg class="' . e($class) . '" aria-hidden="true"><use href="/assets/icons.svg#' . e($name) . '"/></svg>';
}

/* ---------- Mensagens flash ---------- */

function flash(string $type, string $msg): void
{
    $_SESSION['flash'][] = [$type, $msg];
}

function take_flashes(): array
{
    $f = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $f;
}

/* ---------- CSRF ---------- */

function csrf_token(): string
{
    return $_SESSION['csrf'] ??= bin2hex(random_bytes(32));
}

function csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . csrf_token() . '">';
}

function require_post(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        exit('Método não permitido');
    }
    if (!hash_equals(csrf_token(), (string)($_POST['_csrf'] ?? ''))) {
        http_response_code(403);
        render('erro', ['title' => 'Sessão expirada', 'mensagem' => 'Sua sessão expirou. Volte e tente de novo.']);
        exit;
    }
}

function is_post(): bool
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') return false;
    require_post();
    return true;
}

/* ---------- Autenticação ---------- */

function current_user(): ?array
{
    static $user = false;
    if ($user !== false) return $user;
    $id = $_SESSION['usuario_id'] ?? null;
    $user = $id ? (q('SELECT * FROM Usuario WHERE usuario_id = ? AND usuario_ativo = 1', [$id])->fetch() ?: null) : null;
    if ($id && !$user) unset($_SESSION['usuario_id']);
    return $user;
}

function current_mod(): ?array
{
    static $mod = false;
    if ($mod !== false) return $mod;
    $id = $_SESSION['moderador_id'] ?? null;
    $mod = $id ? (q('SELECT moderador_id, moderador_usuario_nome FROM Moderador WHERE moderador_id = ?', [$id])->fetch() ?: null) : null;
    return $mod;
}

function require_login(): array
{
    $u = current_user();
    if (!$u) {
        flash('info', 'Entre na sua conta para continuar.');
        redirect(url('entrar.php', ['voltar' => $_SERVER['REQUEST_URI'] ?? '/']));
    }
    return $u;
}

function require_mod(): array
{
    $m = current_mod();
    if (!$m) {
        flash('info', 'Área restrita a moderadores.');
        redirect(url('entrar.php'));
    }
    return $m;
}

function login_as(string $key, int $id): void
{
    session_regenerate_id(true);
    unset($_SESSION['usuario_id'], $_SESSION['moderador_id']);
    $_SESSION[$key] = $id;
}

/** Só aceita caminhos internos como destino pós-login. */
function safe_return(?string $to): string
{
    return ($to && str_starts_with($to, '/') && !str_starts_with($to, '//')) ? $to : '/';
}

/* ---------- Datas ---------- */

function local_dt(string $utc): DateTimeImmutable
{
    return (new DateTimeImmutable($utc, new DateTimeZone('UTC')))->setTimezone(new DateTimeZone('America/Sao_Paulo'));
}

function time_ago(string $utc): string
{
    $diff = time() - local_dt($utc)->getTimestamp();
    if ($diff < 60) return 'agora';
    if ($diff < 3600) return 'há ' . intdiv($diff, 60) . ' min';
    if ($diff < 86400) return 'há ' . intdiv($diff, 3600) . ' h';
    $d = intdiv($diff, 86400);
    if ($d === 1) return 'ontem';
    if ($d < 30) return "há $d dias";
    return local_dt($utc)->format('d/m/Y');
}

function fmt_date(string $ymd): string
{
    $meses = ['jan', 'fev', 'mar', 'abr', 'mai', 'jun', 'jul', 'ago', 'set', 'out', 'nov', 'dez'];
    $d = DateTimeImmutable::createFromFormat('Y-m-d', substr($ymd, 0, 10));
    if (!$d) return e($ymd);
    return $d->format('j') . ' ' . $meses[(int)$d->format('n') - 1] . ($d->format('Y') !== date('Y') ? ' ' . $d->format('Y') : '');
}

/* ---------- Formulários ---------- */

function post_str(string $key, int $max): string
{
    $v = trim((string)($_POST[$key] ?? ''));
    return mb_substr($v, 0, $max);
}

function avatar_html(?array $u, string $size = 'md'): string
{
    $name = $u['nome'] ?? $u['nome_usuario'] ?? '?';
    if (!empty($u['foto_perfil'])) {
        return '<img class="avatar avatar-' . $size . '" src="/' . e($u['foto_perfil']) . '" alt="">';
    }
    $initial = mb_strtoupper(mb_substr($name, 0, 1));
    $tone = crc32($name) % 6;
    return '<span class="avatar avatar-' . $size . ' tone-' . $tone . '" aria-hidden="true">' . e($initial) . '</span>';
}

/**
 * Valida e salva uma imagem enviada. Retorna o caminho relativo (uploads/…),
 * null se nada foi enviado, ou lança RuntimeException com mensagem amigável.
 */
function save_upload(string $field, string $prefix): ?string
{
    $f = $_FILES[$field] ?? null;
    if (!$f || $f['error'] === UPLOAD_ERR_NO_FILE) return null;
    if ($f['error'] === UPLOAD_ERR_INI_SIZE || $f['error'] === UPLOAD_ERR_FORM_SIZE || $f['size'] > 3 * 1024 * 1024) {
        throw new RuntimeException('A imagem deve ter no máximo 3 MB.');
    }
    if ($f['error'] !== UPLOAD_ERR_OK || !is_uploaded_file($f['tmp_name'])) {
        throw new RuntimeException('Não foi possível enviar a imagem. Tente de novo.');
    }
    $types = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'];
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']);
    if (!isset($types[$mime]) || @getimagesize($f['tmp_name']) === false) {
        throw new RuntimeException('Formato não suportado. Use JPG, PNG, WebP ou GIF.');
    }
    $dir = APP_ROOT . '/public/uploads';
    if (!is_dir($dir)) mkdir($dir, 0775, true);
    $name = $prefix . '_' . bin2hex(random_bytes(12)) . '.' . $types[$mime];
    if (!move_uploaded_file($f['tmp_name'], "$dir/$name")) {
        throw new RuntimeException('Não foi possível salvar a imagem.');
    }
    return 'uploads/' . $name;
}
