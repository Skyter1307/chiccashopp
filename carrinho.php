<?php include 'php/header.php'?>

  <!-- Conteúdo -->
  <div class="container my-5">
    <h2 class="mb-4">Seu Carrinho</h2>
    <div class="table-responsive">
      <table class="table align-middle">
        <thead class="table-dark">
          <tr>
            <th>Produto</th>
            <th>Preço</th>
            <th>Qtd</th>
            <th>Total</th>
            <th></th>
          </tr>
        </thead>
        <tbody id="carrinho-itens">
          <!-- Conteúdo gerado por JS futuramente -->
          <tr>
            <td>Lingerie Sensual</td>
            <td>R$ 89,90</td>
            <td>1</td>
            <td>R$ 89,90</td>
            <td><button class="btn btn-sm btn-danger">Remover</button></td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="text-end">
      <h4>Total: R$ 89,90</h4>
      <a href="#" class="btn btn-success">Finalizar Compra</a>
    </div>
  </div>

  <footer class="bg-dark text-light text-center py-3">
    <p class="mb-0">&copy; 2025 ChiccaShop</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
