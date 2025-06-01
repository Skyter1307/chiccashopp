<?php
include 'php/conexao.php';

$id = $_GET['id'] ?? 0;

$sql = "SELECT * FROM produtos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
  echo "Produto não encontrado.";
  exit;
}

$produto = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="pt-br">

<?php include 'php/header.php'; ?>

  <div class="container my-5">
    <div class="row">
      <div class="col-md-6">
        <img src="assets/imagens/<?php echo htmlspecialchars($produto['imagem']); ?>" class="img-fluid rounded">
      </div>
      <div class="col-md-6">
        <h2><?php echo htmlspecialchars($produto['nome']); ?></h2>
        <h4 class="text-danger">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></h4>
        <p><?php echo nl2br(htmlspecialchars($produto['descricao'])); ?></p>
        <a href="carrinho.php?adicionar=<?= $produto['id'] ?>" class="btn btn-success">Adicionar ao Carrinho</a>
      </div>
    </div>
  </div>

<?php include 'php/footer.php' ?>