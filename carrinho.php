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
    $tamanho = isset($_GET['tamanho']) ? $_GET['tamanho'] : 'Único';
    $quantidade = isset($_GET['quantidade']) ? (int) $_GET['quantidade'] : 1;

    // Verifica se o produto com esse tamanho existe e está ativo
    $stmt = $conn->prepare("SELECT quantidade FROM produtos WHERE id = ? AND tamanho = ? AND status = 'ativo'");
    $stmt->bind_param("is", $id_produto, $tamanho);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $dados = $result->fetch_assoc();
        $estoque_disponivel = (int) $dados['quantidade'];

        $chave = $id_produto . '_' . $tamanho;
        $quantidade_atual = isset($_SESSION['carrinho'][$chave]) ? $_SESSION['carrinho'][$chave]['quantidade'] : 0;
        $nova_quantidade = $quantidade_atual + $quantidade;

        if ($nova_quantidade > $estoque_disponivel) {
            $_SESSION['erro_carrinho'] = "Quantidade indisponível em estoque para o tamanho $tamanho.";
        } else {
            $_SESSION['carrinho'][$chave] = [
                'id' => $id_produto,
                'quantidade' => $nova_quantidade,
                'tamanho' => $tamanho
            ];
        }
    } else {
        $_SESSION['erro_carrinho'] = "Este produto com tamanho $tamanho não está disponível.";
    }

    header('Location: carrinho.php');
    exit;
}

// Remove produto do carrinho
if (isset($_GET['remover'])) {
    $chave = $_GET['remover'];

    if (isset($_SESSION['carrinho'][$chave])) {
        unset($_SESSION['carrinho'][$chave]);
    }

    header('Location: carrinho.php');
    exit;
}
?>

<?php include 'php/header.php'; ?>

<div class="container my-5">
    <h2 class="mb-4">Seu Carrinho</h2>

    <?php if (isset($_SESSION['erro_carrinho'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['erro_carrinho']; unset($_SESSION['erro_carrinho']); ?>
        </div>
    <?php endif; ?>

    <?php if (empty($_SESSION['carrinho'])): ?>
        <p>Seu carrinho está vazio.</p>
    <?php else: ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Tamanho</th>
                    <th>Quantidade</th>
                    <th>Preço Unitário</th>
                    <th>Total</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $totalGeral = 0;
                foreach ($_SESSION['carrinho'] as $chave => $item):
                    if (!is_array($item) || !isset($item['id'], $item['quantidade'], $item['tamanho'])) {
                        continue;
                    }

                    $id = $item['id'];
                    $qtd = $item['quantidade'];
                    $tamanho = $item['tamanho'];

                    $stmt = $conn->prepare("SELECT nome, preco, imagem FROM produtos WHERE id = ?");
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $produto = $result->fetch_assoc();

                    if (!$produto) continue;

                    $subtotal = $produto['preco'] * $qtd;
                    $totalGeral += $subtotal;
                ?>
                    <tr>
                        <td>
                            <?php
                                $imagem_original = $produto['imagem'];
                                $nome_base = pathinfo($imagem_original, PATHINFO_FILENAME);
                                $extensao = pathinfo($imagem_original, PATHINFO_EXTENSION);
                                $imagem_thumb = 'assets/imagens/thumbs/' . $nome_base . '.webp';

                                if (file_exists($imagem_thumb)) {
                                    echo '<img src="' . $imagem_thumb . '" alt="Thumb" style="width: 60px; height: auto; margin-right: 10px;">';
                                } else {
                                    echo '<img src="assets/imagens/thumbs/padrao.webp" alt="Sem imagem" style="width: 60px; height: auto; margin-right: 10px;">';
                                }
                            ?>
                            <?= htmlspecialchars($produto['nome']) ?>
                        </td>
                        <td><?= htmlspecialchars($tamanho) ?></td>
                        <td><?= $qtd ?></td>
                        <td>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></td>
                        <td>R$ <?= number_format($subtotal, 2, ',', '.') ?></td>
                        <td><a href="?remover=<?= urlencode($chave) ?>" class="btn btn-sm btn-danger">Remover</a></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="4" class="text-end fw-bold">Total Geral</td>
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
