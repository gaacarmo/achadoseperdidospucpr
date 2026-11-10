<?php
declare(strict_types=1);

/*
 * Dados de demonstração. As datas são relativas ao momento do seed, então a
 * vitrine sempre parece recente. Contas de teste:
 *   usuário     demo / demo1234
 *   moderador   moderador / modera1234
 */

function seed_database(PDO $pdo): void
{
    $pdo->beginTransaction();
    $ago = fn(int $hours) => gmdate('Y-m-d H:i:s', time() - $hours * 3600);
    $day = fn(int $days) => date('Y-m-d', time() - $days * 86400);

    $pdo->prepare('INSERT INTO Moderador (moderador_usuario_nome, moderador_senha) VALUES (?, ?)')
        ->execute(['moderador', password_hash('modera1234', PASSWORD_DEFAULT)]);

    $users = [
        ['Conta Demo', 'demo', 'demo@acheinapuc.dev', 'Ciência da Computação'],
        ['Marina Teixeira', 'marina.t', 'marina@acheinapuc.dev', 'Arquitetura e Urbanismo'],
        ['Lucas Andrade', 'lucasand', 'lucas@acheinapuc.dev', 'Engenharia de Software'],
        ['Beatriz Kowalski', 'biakow', 'beatriz@acheinapuc.dev', 'Medicina'],
        ['Rafael Moreira', 'rafa.m', 'rafael@acheinapuc.dev', 'Direito'],
        ['Júlia Santos', 'jujus', 'julia@acheinapuc.dev', 'Design'],
    ];
    $stmt = $pdo->prepare('INSERT INTO Usuario (nome, nome_usuario, email, senha, curso_usuario) VALUES (?, ?, ?, ?, ?)');
    $hash = password_hash('demo1234', PASSWORD_DEFAULT);
    foreach ($users as $u) $stmt->execute([$u[0], $u[1], $u[2], $hash, $u[3]]);

    // [nome, descrição, local, cor, categoria, tipo, dias atrás (data), horas atrás (post), autor, verificado, resolvido]
    $posts = [
        ['AirPods Pro com estojo', 'Achei em cima da mesa perto das tomadas, no 2º andar. O estojo tem um adesivo de planeta. Descreva o adesivo para retirar.', 'Biblioteca Central — 2º andar', 'Branco', 'Eletrônico', 'Achei', 0, 3, 2, true, false],
        ['Carteirinha estudantil', 'Perdi minha carteirinha da PUCPR entre o RU e o Bloco Azul, provavelmente no almoço. Nome: Rafael M.', 'Restaurante Universitário', 'Verde', 'Documentos', 'Perdi', 1, 20, 5, false, false],
        ['Garrafa térmica Stanley', 'Garrafa verde-musgo, um pouco amassada embaixo. Esqueci no vestiário depois do treino.', 'Ginásio de esportes', 'Verde', 'Acessório', 'Perdi', 2, 46, 4, true, false],
        ['Calculadora HP 50g', 'Encontrada na sala 214 depois da prova de Cálculo II. Deixei na secretaria do bloco.', 'Bloco Amarelo — sala 214', 'Preta', 'Eletrônico', 'Achei', 2, 50, 3, true, true],
        ['Moletom da Atlética', 'Moletom cinza tamanho M, da Atlética de Arquitetura. Tem um bottom de cacto na manga.', 'Bloco Verde — ateliê', 'Cinza', 'Roupa', 'Perdi', 3, 70, 2, false, false],
        ['Chave com chaveiro de ursinho', 'Molho com 3 chaves e um ursinho de pelúcia marrom. Estava no banco da praça.', 'Praça da Reitoria', 'Prateada', 'Outro', 'Achei', 4, 96, 6, true, false],
        ['Caderno de Anatomia', 'Caderno universitário com capa azul e muitas anotações coloridas. Valor sentimental enorme!', 'Bloco da Saúde — Lab 3', 'Azul', 'Material', 'Perdi', 5, 118, 4, false, false],
        ['Óculos de grau', 'Armação tartaruga, dentro de um estojo preto. Achei no estacionamento, perto da cancela.', 'Estacionamento P4', 'Marrom', 'Acessório', 'Achei', 6, 140, 3, false, false],
        ['Carregador de notebook USB-C', 'Carregador 65W da Dell, esqueci plugado na tomada da mesa do canto.', 'Biblioteca Central — térreo', 'Preto', 'Eletrônico', 'Perdi', 8, 190, 1, false, true],
    ];
    $stmt = $pdo->prepare('INSERT INTO Postagem (postagem_nome, postagem_descricao, postagem_local, postagem_cor, postagem_categoria,
        postagem_usuario_tipo, postagem_data, postagem_timestamp, id_usuario, id_admin, postagem_resolvida)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
    foreach ($posts as $p) {
        $stmt->execute([$p[0], $p[1], $p[2], $p[3], $p[4], $p[5], $day($p[6]), $ago($p[7]), $p[8], $p[9] ? 1 : null, $p[10] ? 1 : 0]);
    }

    // [post, usuário, texto, horas atrás, privado, índice do pai (ordem deste array) ou null]
    $comments = [
        [1, 4, 'Acho que são meus! O adesivo é do Saturno 🪐', 2, false, null],
        [1, 2, 'Isso! Pode passar na biblioteca às 15h?', 1, true, 0],
        [2, 6, 'Vi uma carteirinha no balcão do RU hoje, pergunta lá!', 18, false, null],
        [2, 5, 'Valeu, vou passar lá depois da aula.', 17, false, 2],
        [4, 3, 'Era minha, já busquei na secretaria. Muito obrigado!', 40, false, null],
        [5, 6, 'Acho que vi um moletom assim no achados da portaria.', 60, false, null],
        [6, 1, 'Posso ter perdido um molho assim. O chaveiro tem uma fitinha vermelha?', 90, true, null],
    ];
    $stmt = $pdo->prepare('INSERT INTO Comentarios (postagem_id, usuario_id, comentario_conteudo, comentario_data, comentario_privado, comentario_pai_id)
        VALUES (?, ?, ?, ?, ?, ?)');
    $ids = [];
    foreach ($comments as $c) {
        $stmt->execute([$c[0], $c[1], $c[2], $ago($c[3]), $c[4] ? 1 : 0, $c[5] === null ? null : $ids[$c[5]]]);
        $ids[] = (int)$pdo->lastInsertId();
    }

    $stmt = $pdo->prepare('INSERT INTO Curtidas (postagem_id, usuario_id) VALUES (?, ?)');
    foreach ([[1, 3], [1, 4], [1, 5], [1, 6], [2, 2], [2, 6], [3, 2], [4, 5], [4, 2], [4, 6], [6, 4], [7, 2], [7, 3], [7, 6]] as $l) {
        $stmt->execute($l);
    }

    $pdo->commit();
}
