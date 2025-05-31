<?php
include 'php/conexao.php';

$sql = "SELECT * FROM produtos WHERE status = 'ativo' ORDER BY id DESC";
$result = $conn->query($sql);
?>

<?php include 'php/header.php'?>

  <!-- Lista de Produtos -->
  <div class="container my-5">
    <h2 class="mb-4">Todos os Produtos</h2>
    <div class="row">
      <?php if ($result->num_rows > 0): ?>
        <?php while($produto = $result->fetch_assoc()): ?>
          <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
              <img src="assets/imagens/<?= htmlspecialchars($produto['imagem']) ?>" class="card-img-top" alt="<?= htmlspecialchars($produto['nome']) ?>">
              <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($produto['nome']) ?></h5>
                <p class="card-text"><?= htmlspecialchars($produto['descricao']) ?></p>
                <p class="text-danger fw-bold">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></p>
                <a href="produto.php?id=<?= $produto['id'] ?>" class="btn btn-dark w-100">Ver Produto</a>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p class="text-muted">Nenhum produto disponível no momento.</p>
      <?php endif; ?>
    </div>
  </div>

  <!-- Rodapé -->
<?php include 'php/footer.php'?>

<?php $conn->close(); ?>
