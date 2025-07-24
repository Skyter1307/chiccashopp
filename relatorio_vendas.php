<?php
require_once 'php/conexao.php';
include 'php/header_admin.php';
?>

    <div class="container mt-5">
        <h3 class="mb-4">Relatório de Vendas por Cliente</h3>

        <div class="table-responsive">
            <table class="table table-bordered table-striped text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Nome</th>
                        <th>Telefone</th>
                        <th>Data da Venda</th>
                        <th>Valor Total</th>
                        <th>Forma de Pagamento</th>
                        <th>Parcelas</th>
                        <th>Observações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT vd.*, c.nome, c.telefone 
                            FROM vendas_diretas vd
                            JOIN clientes_vendas_diretas c ON vd.cliente_id = c.id
                            ORDER BY vd.data_venda DESC";
                    $res = mysqli_query($conn, $sql);

                    if ($res && mysqli_num_rows($res) > 0) {
                        while ($row = mysqli_fetch_assoc($res)) {
                            echo "<tr>
                                <td>{$row['nome']}</td>
                                <td>{$row['telefone']}</td>
                                <td>" . date('d/m/Y', strtotime($row['data_venda'])) . "</td>
                                <td>R$ " . number_format($row['valor_total'], 2, ',', '.') . "</td>
                                <td>{$row['forma_pagamento']}</td>
                                <td>{$row['parcelas']}</td>
                                <td>{$row['observacoes']}</td>
                            </tr>";
                        }
                    } else {
                        echo '<tr><td colspan="7">Nenhuma venda encontrada.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <a href="escolha_relatorio.php" class="btn btn-dark mb-5">← Voltar</a>
        
    </div>

<?php include 'php/footer.php'; ?>