<?php include 'php/header_admin.php'; ?>

<div class="container py-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Cadastrar Produto</h2>
    <a href="lista_admin.php" class="btn btn-outline-dark">Estoque de Produtos</a>
  </div>

  <form action="php/cadastrar_produto.php" method="POST" enctype="multipart/form-data">
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
      <label class="form-label">Quantidade</label>
      <input type="number" name="quantidade" class="form-control" min="0" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Tamanho</label>
      <select name="tamanho" class="form-select" required>
        <option value="">Selecione o tamanho</option>
        <option value="P">P</option>
        <option value="M">M</option>
        <option value="G">G</option>
        <option value="GG">GG</option>
        <option value="G1">G1</option>
        <option value="G2">G2</option>
        <option value="G3">G3</option>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Status</label>
      <select name="status" class="form-select" required>
        <option value="ativo">Ativo</option>
        <option value="inativo">Inativo</option>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Imagem</label>
      <input type="file" name="imagem" class="form-control" accept="image/*" required>
    </div>

    <div class="d-flex justify-content-between">
      <button type="submit" class="btn btn-danger">Cadastrar</button>
    </div>
  </form>
</div>

<?php include 'php/footer.php'; ?>