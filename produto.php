<?php
include 'php/conexao.php';
include 'php/header.php';

$id = intval($_GET['id']);
$sql = "SELECT * FROM produtos WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $produto = $result->fetch_assoc();
} else {
    echo "Produto não encontrado.";
    exit;
}
?>

<div class="container my-5">
  <div class="row">
    <div class="col-md-6 text-center">
      <img src="assets/imagens/<?php echo $produto['imagem']; ?>" alt="<?php echo $produto['nome']; ?>" class="img-fluid rounded shadow">
    </div>
    <div class="col-md-6">
      <h2><?php echo $produto['nome']; ?></h2>
      <h4 class="text-danger">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></h4>
      <p><?php echo $produto['descricao']; ?></p>

      <?php if (isset($_SESSION['erro_estoque'])): ?>
        <div class="alert alert-danger">
          <?= $_SESSION['erro_estoque']; unset($_SESSION['erro_estoque']); ?>
        </div>
      <?php endif; ?>

      <form action="php/adicionar_ao_carrinho.php" method="POST">
        <input type="hidden" name="id_produto" value="<?= $produto['id'] ?>">

        <div class="mb-3">
          <label for="tamanho" class="form-label">Tamanho:</label>
          <select name="tamanho" id="tamanho" class="form-select" required>
            <option value="">Selecione</option>
            <?php
            $tamanhos = ['P', 'M', 'G', 'GG', 'G1', 'G2', 'G3'];
            foreach ($tamanhos as $t) {
              echo "<option value=\"$t\">$t</option>";
            }
            ?>
          </select>
        </div>

        <div class="mb-3">
          <label for="quantidade" class="form-label">Quantidade:</label>
          <input type="number" name="quantidade" id="quantidade" class="form-control" value="1" min="1" required>
        </div>

        <button type="submit" class="btn btn-success">Adicionar ao Carrinho</button>
      </form>
    </div>
  </div>
</div>

<!-- Produtos similares -->
<div class="container my-5">
  <h3 class="mb-4">Produtos Similares</h3>
  <div class="row">
    <?php
    $sql_similares = "SELECT id, nome, preco, imagem FROM produtos 
                      WHERE status = 'ativo' AND id != $id 
                      ORDER BY id DESC LIMIT 4";
    $res_similares = $conn->query($sql_similares);

    if ($res_similares->num_rows > 0):
      while ($similar = $res_similares->fetch_assoc()):
    ?>
      <div class="col-md-3 mb-4">
        <div class="card h-100">
          <img src="assets/imagens/<?php echo $similar['imagem']; ?>" alt="<?php echo $similar['nome']; ?>">
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($similar['nome']) ?></h5>
            <p class="card-text">R$ <?= number_format($similar['preco'], 2, ',', '.') ?></p>
            <a href="produto.php?id=<?= $similar['id'] ?>" class="btn btn-dark w-100">Ver Produto</a>
          </div>
        </div>
      </div>
    <?php
      endwhile;
    else:
      echo "<p>Nenhum produto similar encontrado.</p>";
    endif;
    ?>
  </div>
</div>

<?php include 'php/footer.php'; ?>
