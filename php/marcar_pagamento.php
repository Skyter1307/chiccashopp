<?php
require_once "conexao.php";

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $hoje = date('Y-m-d');

    $sql = "UPDATE pagamentos_venda_direta SET data_pagamento = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $hoje, $id);

    if ($stmt->execute()) {
        echo "ok";
    } else {
        echo "erro";
    }

    $stmt->close();
    $conn->close();
}
?>
