<?php
include 'php/header_admin.php';
include 'php/conexao.php';

$result = $conn->query("SELECT * FROM produtos ORDER BY id DESC");
?>

<div class="container py-5">

  <?php if (isset($_GET['sucesso']) && $_GET['sucesso'] == 1): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      Estoque atualizado com sucesso!
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
    </div>
  <?php elseif (isset($_GET['erro']) && $_GET['erro'] == 1): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      Ocorreu um erro ao tentar atualizar o estoque. Tente novamente.
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
    </div>
  <?php endif; ?>

  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Estoque de Produtos</h2>
    <div>
      <a href="admin_produtos.php" class="btn btn-danger me-2">Cadastrar Produto</a>
      <a href="php/gerar_thumbs_antigas.php" class="btn btn-secondary">Gerar Thumbs Faltantes</a>
    </div>
  </div>

  <table class="table table-bordered table-hover">
    <thead class="table-dark">
      <tr>
        <th>Imagem</th>
        <th>Nome</th>
        <th>Preço</th>
        <th>Quantidade</th>
        <th>Status</th>
        <th><strong>Tamanho</strong></th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $resultado = $conn->query("SELECT * FROM produtos ORDER BY id DESC");

      while ($row = $resultado->fetch_assoc()) {
        echo '<tr>';
        echo '<td><img src="assets/imagens/thumbs/' . htmlspecialchars($row['imagem']) . '" width="60"></td>';
        echo '<td>' . htmlspecialchars($row['nome']) . '</td>';
        echo '<td>R$ ' . number_format($row['preco'], 2, ',', '.') . '</td>';
        echo '<td>' . intval($row['quantidade']) . '</td>';
        echo '<td>' . htmlspecialchars($row['status']) . '</td>';
        echo '<td>' . htmlspecialchars($row['tamanho']) . '</td>';
        echo '<td>
                <a href="editar_produto.php?id=' . $row['id'] . '" class="btn btn-sm btn-warning">Editar</a>
                <a href="php/excluir_produto.php?id=' . $row['id'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Tem certeza que deseja excluir este produto?\')">Excluir</a>
              </td>';
        echo '</tr>';
      }

      $conn->close();
      ?>
    </tbody>
  </table>
</div>

<?php include 'php/footer.php'; ?>
