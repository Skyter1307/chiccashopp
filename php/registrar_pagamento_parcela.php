<?php
require_once 'conexao.php';

$id = $_POST['id_parcela'] ?? null;
$data_pagamento = $_POST['data_pagamento'] ?? null;

if (!$id || !$data_pagamento) {
    echo json_encode(['success' => false, 'mensagem' => 'Dados incompletos.']);
    exit;
}

$sql = "UPDATE pagamentos_venda_direta 
        SET status = 'pago', data_pagamento = ? 
        WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $data_pagamento, $id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'mensagem' => 'Erro ao atualizar: ' . $stmt->error]);
}
