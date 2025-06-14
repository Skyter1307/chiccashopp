<?php
session_start();
include 'conexao.php';

$id_produto = intval($_POST['id_produto']);
$tamanho = $_POST['tamanho'];
$quantidade = intval($_POST['quantidade']);

// Verifica estoque
$sql = "SELECT nome, quantidade FROM produtos WHERE id = $id_produto";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $produto = $result->fetch_assoc();

    if ($quantidade > $produto['quantidade']) {
        // Estoque insuficiente
        $_SESSION['erro_estoque'] = "Estoque insuficiente! Apenas {$produto['quantidade']} unidade(s) disponível(is).";
        header("Location: ../produto.php?id=$id_produto");
        exit;
    }

    // Adiciona ao carrinho (exemplo básico usando sessão)
    $_SESSION['carrinho'][] = [
        'id' => $id_produto,
        'tamanho' => $tamanho,
        'quantidade' => $quantidade
    ];

    header("Location: ../carrinho.php");
    exit;
} else {
    echo "Produto não encontrado.";
}
?>
