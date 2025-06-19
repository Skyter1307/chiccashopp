<?php
require_once 'conexao.php';

$nome = $_POST['nome'] ?? '';
$telefone = $_POST['telefone'] ?? '';
$nascimento = $_POST['nascimento'] ?? '';

if ($nome && $telefone && $nascimento) {
    $sql = "INSERT INTO vendas_diretas (nome_cliente, telefone_cliente, nascimento_cliente) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $nome, $telefone, $nascimento);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'id' => $conn->insert_id]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false]);
}
?>
