<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login_admin.php");
    exit;
}
?>

<?php include 'php/header_admin.php'?>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">Escolha o Relatório</h2>

        <div class="row">
            <div class="col-md-6">
                <div class="card bg-info text-white mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Relatório de Vendas por Cliente</h5>
                        <p class="card-text">Visualize todas as vendas registradas, por cliente.</p>
                        <a href="relatorio_vendas.php" class="btn btn-light">Acessar</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card bg-danger text-white mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Clientes com Débitos</h5>
                        <p class="card-text">Acompanhe todas as parcelas vencidas e não pagas.</p>
                        <a href="relatorio_debitos.php" class="btn btn-light">Acessar</a>
                    </div>
                </div>
            </div>
        </div>

        <a href="painel_admin.php" class="btn btn-dark mt-3">← Voltar ao Painel</a>
    </div>
    <br>

    <?php include 'php/footer.php'?>

