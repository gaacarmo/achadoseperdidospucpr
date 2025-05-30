<?php
session_start();
require_once 'conecta_db.php';
$obj = conecta_db();

$usuario_id = $_SESSION['usuario_id'];
$postagem_id = $_POST['postagem_id'];
$comentario_conteudo = trim($_POST['comentario_conteudo']);
$comentario_pai_id = $_POST['comentario_pai_id'] ?? null;
$comentario_privado = isset($_POST['comentario_privado']) ? 1 : 0;

if (!empty($comentario_conteudo)) {
    $stmt = $obj->prepare("INSERT INTO Comentarios (comentario_conteudo, comentario_privado, postagem_id, usuario_id, comentario_pai_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("siiii", $comentario_conteudo, $comentario_privado, $postagem_id, $usuario_id, $comentario_pai_id);
    $stmt->execute();
}

header("Location: ../index.php");
exit;
