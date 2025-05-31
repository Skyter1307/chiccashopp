<?php include 'php/header_admin.php'; ?>

  <div class="container py-5">
    <h2 class="mb-4">Cadastrar Produto</h2>

    <form action="backend/cadastrar_produto.php" method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label class="form-label">Nome do Produto</label>
        <input type="text" name="nome" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Descrição</label>
        <textarea name="descricao" class="form-control" rows="3" required></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label">Preço</label>
        <input type="text" name="preco" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Imagem</label>
        <input type="file" name="imagem" class="form-control" accept="image/*" required>
      </div>

      <button type="submit" class="btn btn-danger">Cadastrar</button>
    </form>
  </div>

<?php include 'php/footer.php';?>