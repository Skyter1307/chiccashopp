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
<head>
  <meta charset="UTF-8">
  <title><?php echo htmlspecialchars($produto['nome']); ?> - ChiccaShop</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav class="navbar navbar-dark bg-dark px-3">
  <a class="navbar-brand" href="index.html">
    <img src="img/logo.png" alt="ChiccaShop" height="40">
  </a>
</nav>

<div class="container my-5">
  <div class="row">
    <div class="col-md-6">
      <img src="img/produtos/<?php echo htmlspecialchars($produto['imagem']); ?>" class="img-fluid rounded">
    </div>
    <div class="col-md-6">
      <h2><?php echo htmlspecialchars($produto['nome']); ?></h2>
      <h4 class="text-danger">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></h4>
      <p><?php echo nl2br(htmlspecialchars($produto['descricao'])); ?></p>
      <a href="#" class="btn btn-success">Adicionar ao Carrinho</a>
    </div>
  </div>
</div>

<footer class="bg-dark text-light text-center py-3">
  <p class="mb-0">&copy; 2025 ChiccaShop</p>
</footer>

</body>
</html>
