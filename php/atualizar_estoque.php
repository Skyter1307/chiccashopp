<?php
include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
    $quantidade = isset($_POST['quantidade']) ? (int) $_POST['quantidade'] : null;

    if ($id > 0 && $quantidade !== null) {
        $stmt = $conn->prepare("UPDATE produtos SET quantidade = ? WHERE id = ?");
        $stmt->bind_param("ii", $quantidade, $id);
        if ($stmt->execute()) {
            header("Location: ../lista_admin.php?sucesso=1");
        } else {
            header("Location: ../lista_admin.php?erro=1");
        }
    } else {
        header("Location: ../lista_admin.php?erro=1");
    }

    exit;
} else {
    header("Location: ../lista_admin.php");
    exit;
}
