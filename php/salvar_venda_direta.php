<?php
require_once 'conexao.php';

// Verifica se os dados vieram corretamente
$cliente_id = $_POST['cliente_id'] ?? null;
$data_venda = $_POST['data_venda'] ?? null;
$valor_total = floatval($_POST['valor_total'] ?? 0);
$forma_pagamento = $_POST['forma_pagamento'] ?? null;
$parcelas = $_POST['parcelas'] ?? 0;
$observacoes = $_POST['observacoes'] ?? '';
$dadosParcelas = json_decode($_POST['dados_parcelas'] ?? '[]', true);
// $listaItens = $_POST['listaItens']; 

// foreach ($listaitens as $iten){
//     $valorItem = $listaitens['valorItem'];
//     $nomeProduto = $listaitens['nomePorduto'];

//     $id

//     $conexao -> insert
// }

if (!$cliente_id || !$data_venda || !$valor_total || !$forma_pagamento) {
    echo json_encode(['success' => false, 'mensagem' => 'Campos obrigatórios ausentes.']);
    exit;
}

// 1. Inserir venda na tabela `vendas_diretas`
$sql = "INSERT INTO vendas_diretas (cliente_id, data_venda, valor_total, forma_pagamento, parcelas, observacoes)
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(['success' => false, 'mensagem' => 'Erro na preparação da query: ' . $conn->error]);
    exit;
}

$stmt->bind_param("isdsis", $cliente_id, $data_venda, $valor_total, $forma_pagamento, $parcelas, $observacoes);

if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'mensagem' => 'Erro ao salvar venda: ' . $stmt->error]);
    exit;
}

// 2. Recupera o ID da venda salva
$id_venda = $conn->insert_id;

// 3. Se for "À prazo", inserir parcelas na tabela `pagamentos_venda_direta`
if ($forma_pagamento === "prazo" && is_array($dadosParcelas)) {
    $sqlParcela = "INSERT INTO pagamentos_venda_direta (id_venda, data_vencimento, valor_parcela, numero_parcela, status) 
                   VALUES (?, ?, ?, ?, ?)";
    $stmtParcela = $conn->prepare($sqlParcela);

    if (!$stmtParcela) {
        echo json_encode(['success' => false, 'mensagem' => 'Erro ao preparar parcelas: ' . $conn->error]);
        exit;
    }

    $numero = 1;
    foreach ($dadosParcelas as $parcela) {
        $data = $parcela['data'] ?? null;
        $valor = floatval($parcela['valor'] ?? 0);
        $status = "pendente";

        if (!$data || $valor <= 0) continue;

        $stmtParcela->bind_param("isdis", $id_venda, $data, $valor, $numero, $status);

        if (!$stmtParcela->execute()) {
            echo json_encode(['success' => false, 'mensagem' => 'Erro ao salvar parcela: ' . $stmtParcela->error]);
            exit;
        }

        $numero++;
    }
}


// Final
echo json_encode(['success' => true, 'mensagem' => 'Venda salva com sucesso!']);
