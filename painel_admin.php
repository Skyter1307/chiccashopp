<?php
session_start();

// Verifica se é admin
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit;
}
?>

<?php include 'php/header_admin.php'; ?>

<div class="container mt-5">
    <h2>Bem-vindo ao Painel Administrativo!</h2>
    <p>Aqui você poderá gerenciar os produtos, pedidos e clientes.</p>

    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Produtos</div>
                <div class="card-body">
                    <h5 class="card-title">Gerenciar Produtos</h5>
                    <p class="card-text">Cadastrar, editar ou excluir produtos.</p>
                    <a href="admin_produtos.php" class="btn btn-light">Acessar</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Pedidos</div>
                <div class="card-body">
                    <h5 class="card-title">Visualizar Pedidos</h5>
                    <p class="card-text">Acompanhar os pedidos feitos pelos clientes.</p>
                    <a href="admin_pedidos.php" class="btn btn-light">Acessar</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-header">Clientes</div>
                <div class="card-body">
                    <h5 class="card-title">Lista de Clientes</h5>
                    <p class="card-text">Consultar todos os clientes cadastrados.</p>
                    <a href="admin_clientes.php" class="btn btn-light">Acessar</a>
                </div>
            </div>
        </div>

    </div>
</div>

</body>
</html>
