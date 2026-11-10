<?php
declare(strict_types=1);
require __DIR__ . '/../../app/bootstrap.php';
require_mod();

$totais = q('SELECT
    (SELECT COUNT(*) FROM Postagem WHERE postagem_ativa = 1) AS posts,
    (SELECT COUNT(*) FROM Postagem WHERE postagem_ativa = 1 AND postagem_resolvida = 1) AS devolvidos,
    (SELECT COUNT(*) FROM Postagem WHERE postagem_ativa = 1 AND id_admin IS NULL) AS pendentes,
    (SELECT COUNT(*) FROM Usuario WHERE usuario_ativo = 1) AS usuarios,
    (SELECT COUNT(*) FROM Comentarios) AS comentarios')->fetch();

// Publicações por dia (últimos 14 dias), agrupadas no fuso de Curitiba.
$por_dia = [];
for ($i = 13; $i >= 0; $i--) $por_dia[date('Y-m-d', strtotime("-$i days"))] = 0;
$recentes = q('SELECT postagem_timestamp FROM Postagem WHERE postagem_timestamp >= ?', [gmdate('Y-m-d H:i:s', time() - 15 * 86400)])->fetchAll(PDO::FETCH_COLUMN);
foreach ($recentes as $ts) {
    $d = local_dt($ts)->format('Y-m-d');
    if (isset($por_dia[$d])) $por_dia[$d]++;
}

$engajadas = q('SELECT p.postagem_id, p.postagem_nome,
        (SELECT COUNT(*) FROM Curtidas c WHERE c.postagem_id = p.postagem_id) AS curtidas,
        (SELECT COUNT(*) FROM Comentarios c WHERE c.postagem_id = p.postagem_id) AS comentarios
    FROM Postagem p WHERE p.postagem_ativa = 1
    ORDER BY curtidas + comentarios DESC, p.postagem_id DESC LIMIT 5')->fetchAll();

$ativos = q('SELECT u.nome_usuario, u.nome, u.foto_perfil,
        (SELECT COUNT(*) FROM Postagem p WHERE p.id_usuario = u.usuario_id AND p.postagem_ativa = 1) AS posts,
        (SELECT COUNT(*) FROM Comentarios c WHERE c.usuario_id = u.usuario_id) AS comentarios
    FROM Usuario u WHERE u.usuario_ativo = 1
    ORDER BY posts + comentarios DESC LIMIT 5')->fetchAll();

$categorias = q('SELECT postagem_categoria AS c, COUNT(*) AS n FROM Postagem WHERE postagem_ativa = 1
                 GROUP BY postagem_categoria ORDER BY n DESC')->fetchAll();

render('admin/painel', compact('totais', 'por_dia', 'engajadas', 'ativos', 'categorias') + ['title' => 'Painel', 'active' => 'admin']);
