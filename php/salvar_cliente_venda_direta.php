<?php
require_once 'conexao.php';

// LOGA TUDO que está chegando
file_put_contents('log_post.txt', print_r($_POST, true), FILE_APPEND);

$nome = $_POST['nome'] ?? '';
$telefone = $_POST['telefone'] ?? '';
$nascimento = $_POST['nascimento'] ?? '';

if (!$nome || !$telefone || !$nascimento) {
    echo json_encode([
        'success' => false,
        'mensagem' => 'Campos obrigatórios não preenchidos.'
    ]);
    exit;
}

$sql = "INSERT INTO clientes_vendas_diretas (nome, telefone, nascimento) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode([
        'success' => false,
        'mensagem' => 'Erro ao preparar a query: ' . $conn->error
    ]);
    exit;
}

$stmt->bind_param("sss", $nome, $telefone, $nascimento);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'id' => $conn->insert_id]);
} else {
    echo json_encode([
        'success' => false,
        'mensagem' => 'Erro ao executar: ' . $stmt->error
    ]);
}
?>
