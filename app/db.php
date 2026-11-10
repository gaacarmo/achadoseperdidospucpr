<?php
declare(strict_types=1);

/*
 * Conexão com o banco via PDO.
 *
 * DB_DRIVER=sqlite (padrão) usa um arquivo local em data/ — criado e populado
 * com dados de demonstração automaticamente na primeira execução.
 * DB_DRIVER=mysql usa DB_HOST, DB_PORT, DB_NAME, DB_USER, DB_PASSWORD
 * (rode database/schema.mysql.sql e depois `php database/seed.php`).
 */

function db(): PDO
{
    static $pdo = null;
    if ($pdo) return $pdo;

    $driver = env('DB_DRIVER', 'sqlite');
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    if ($driver === 'mysql') {
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
            env('DB_HOST', '127.0.0.1'),
            env('DB_PORT', '3306'),
            env('DB_NAME', 'db_achei')
        );
        $pdo = new PDO($dsn, env('DB_USER', 'root'), env('DB_PASSWORD', ''), $options);
        $pdo->exec("SET time_zone = '+00:00'");
        return $pdo;
    }

    $path = env('DB_PATH', APP_ROOT . '/data/acheinapuc.sqlite');
    $fresh = !is_file($path);
    if ($fresh && !is_dir(dirname($path))) mkdir(dirname($path), 0775, true);

    $pdo = new PDO('sqlite:' . $path, null, null, $options);
    $pdo->exec('PRAGMA foreign_keys = ON');
    $pdo->exec('PRAGMA journal_mode = WAL');

    if ($fresh) {
        $pdo->exec(file_get_contents(APP_ROOT . '/database/schema.sqlite.sql'));
        require_once APP_ROOT . '/database/seed_data.php';
        seed_database($pdo);
    }
    return $pdo;
}

/** Executa uma consulta preparada e devolve o statement. */
function q(string $sql, array $params = []): PDOStatement
{
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

/** Data/hora atual em UTC, no formato aceito por SQLite e MySQL. */
function now_utc(): string
{
    return gmdate('Y-m-d H:i:s');
}
