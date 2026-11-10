<?php
// Popula o banco configurado (útil para MySQL): php database/seed.php
// Com SQLite não é necessário — o banco é criado e populado na primeira visita.
declare(strict_types=1);

$_SERVER['REQUEST_METHOD'] = 'CLI';
require __DIR__ . '/../app/bootstrap.php';
require_once __DIR__ . '/seed_data.php';

if ((int)q('SELECT COUNT(*) FROM Usuario')->fetchColumn() > 0) {
    fwrite(STDERR, "O banco já tem dados; nada a fazer.\n");
    exit(0);
}
seed_database(db());
echo "Dados de demonstração inseridos.\n";
