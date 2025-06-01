<?php
session_start();

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: ../produtos.php");
    exit;
}

// Inicializa o carrinho se ainda não existir
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

// Se o produto já estiver no carrinho, aumenta a quantidade
if (isset($_SESSION['carrinho'][$id])) {
    $_SESSION['carrinho'][$id]++;
} else {
    $_SESSION['carrinho'][$id] = 1;
}

header("Location: ../carrinho.php");
exit;
?>
