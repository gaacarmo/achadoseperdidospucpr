<?php
session_start();
require_once 'conecta_db.php';
$obj = conecta_db();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_SESSION['usuario_id'])) {
    $postagem_id = $_POST['postagem_id'];
    $conteudo = $_POST['comentario_conteudo'];
    $usuario_id = $_SESSION['usuario_id'];
    $comentario_pai_id = $_POST['comentario_pai_id'] ?? null;

    $sql = "INSERT INTO Comentarios (comentario_conteudo, comentario_data, usuario_id, postagem_id, comentario_pai_id)
            VALUES (?, NOW(), ?, ?, ?)";

    $stmt = $obj->prepare($sql);
    $stmt->bind_param("siii", $conteudo, $usuario_id, $postagem_id, $comentario_pai_id);
    $stmt->execute();
}

header("Location: ../index.php");
exit;
