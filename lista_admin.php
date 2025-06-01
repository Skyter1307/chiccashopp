<?php
include 'php/header_admin.php';
include 'php/conexao.php';

$result = $conn->query("SELECT * FROM produtos ORDER BY id DESC");
?>

<div class="container py-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Lista de Produtos</h2>
    <a href="admin_produtos.php" class="btn btn-danger">Cadastrar Produto</a>
  </div>

  <table class="table table-bordered table-striped align-middle">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Imagem</th>
        <th>Nome</th>
        <th>Descrição</th>
        <th>Preço</th>
        <th>Quantidade</th>
        <th>Status</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td>
            <?php if (!empty($row['imagem'])): ?>
              <img src="assets/imagens/<?= htmlspecialchars($row['imagem']) ?>" alt="Imagem" style="width: 80px; height: auto;">
            <?php else: ?>
              Sem imagem
            <?php endif; ?>
          </td>
          <td><?= htmlspecialchars($row['nome']) ?></td>
          <td><?= htmlspecialchars($row['descricao']) ?></td>
          <td>R$ <?= number_format($row['preco'], 2, ',', '.') ?></td>
          <td><?= $row['quantidade'] ?></td>
          <td><?= $row['status'] == 'ativo' ? 'Ativo' : 'Inativo' ?></td>
          <td>
            <a href="editar_produto.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
            <a href="backend/excluir_produto.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este produto?');">Excluir</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<?php include 'php/footer.php'; ?>
