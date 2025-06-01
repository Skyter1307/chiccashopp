<?php
session_start();
include 'php/conexao.php';

// Inicializa o carrinho se não existir
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

// Adiciona produto ao carrinho (com validação)
if (isset($_GET['adicionar'])) {
    $id_produto = (int) $_GET['adicionar'];

    // Verifica se o produto existe e está ativo
    $stmt = $conn->prepare("SELECT id FROM produtos WHERE id = ? AND status = 'ativo'");
    $stmt->bind_param("i", $id_produto);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        if (!isset($_SESSION['carrinho'][$id_produto])) {
            $_SESSION['carrinho'][$id_produto] = 1;
        } else {
            $_SESSION['carrinho'][$id_produto]++;
        }
    }

    header('Location: carrinho.php');
    exit;
}

// Remove produto do carrinho
if (isset($_GET['remover'])) {
    $id_produto = (int) $_GET['remover'];

    if (isset($_SESSION['carrinho'][$id_produto])) {
        unset($_SESSION['carrinho'][$id_produto]);
    }

    header('Location: carrinho.php');
    exit;
}
?>

<?php include 'php/header.php'; ?>

<div class="container my-5">
    <h2 class="mb-4">Seu Carrinho</h2>

    <?php if (empty($_SESSION['carrinho'])): ?>
        <p>Seu carrinho está vazio.</p>
    <?php else: ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Preço Unitário</th>
                    <th>Total</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $totalGeral = 0;
                foreach ($_SESSION['carrinho'] as $id => $qtd):
                    $stmt = $conn->prepare("SELECT nome, preco FROM produtos WHERE id = ?");
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $produto = $result->fetch_assoc();

                    $subtotal = $produto['preco'] * $qtd;
                    $totalGeral += $subtotal;
                ?>
                <tr>
                    <td><?= htmlspecialchars($produto['nome']) ?></td>
                    <td><?= $qtd ?></td>
                    <td>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></td>
                    <td>R$ <?= number_format($subtotal, 2, ',', '.') ?></td>
                    <td><a href="?remover=<?= $id ?>" class="btn btn-sm btn-danger">Remover</a></td>
                </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="3" class="text-end fw-bold">Total Geral</td>
                    <td colspan="2" class="fw-bold">R$ <?= number_format($totalGeral, 2, ',', '.') ?></td>
                </tr>
            </tbody>
        </table>
        <div class="d-flex justify-content-between">
            <a href="produtos.php" class="btn btn-danger">Continuar Comprando</a>
            <a href="finalizar.php" class="btn btn-success">Finalizar Compra</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'php/footer.php'; ?>
