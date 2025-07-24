<?php
require_once "php/conexao.php";
include 'php/header_admin.php';

// Consulta para buscar todas as parcelas vencidas e não pagas
$sql = "
    SELECT 
        c.nome AS nome_cliente,
        c.telefone,
        v.id AS id_venda,
        v.data_venda,
        v.valor_total,
        v.forma_pagamento,
        p.id AS parcela_id,
        p.numero_parcela,
        p.valor_parcela,
        p.data_vencimento,
        p.data_pagamento
    FROM pagamentos_venda_direta p
    INNER JOIN vendas_diretas v ON p.id_venda = v.id
    INNER JOIN clientes_vendas_diretas c ON v.cliente_id = c.id
    WHERE (p.data_pagamento IS NULL AND p.data_vencimento <= CURDATE())
    ORDER BY c.nome ASC, p.data_vencimento ASC
";

$resultado = $conn->query($sql);
?>


<div class="container mt-5">
    <h2 class="mb-4">Clientes com Débitos em Aberto</h2>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Nome</th>
                <th>Telefone</th>
                <th>Data da Venda</th>
                <th>Parcela</th>
                <th>Valor</th>
                <th>Vencimento</th>
                <th>Pagamento</th>
                <th>Ação</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($resultado->num_rows > 0): ?>
                <?php while ($row = $resultado->fetch_assoc()): ?>
                    <tr id="linha-parcela-<?php echo $row['parcela_id']; ?>">
                        <td><?= $row['nome_cliente']; ?></td>
                        <td><?= $row['telefone']; ?></td>
                        <td><?= $row['data_venda']; ?></td>
                        <td><?= $row['numero_parcela']; ?></td>
                        <td>R$ <?= number_format($row['valor_parcela'], 2, ',', '.'); ?></td>
                        <td><?= $row['data_vencimento']; ?></td>
                        <td><?= $row['data_pagamento'] ?? '—'; ?></td>
                        <td>
                            <?php if (empty($row['data_pagamento'])): ?>
                                <button class="btn btn-success btn-sm marcar-pago" data-id="<?= $id_parcela ?>">Marcar como Pago</button>
                            <?php else: ?>
                                Pago em <?= $row['data_pagamento']; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center">Nenhuma parcela vencida encontrada.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="escolha_relatorio.php" class="btn btn-dark mb-5">← Voltar</a>
    
    <!-- Modal para registrar pagamento -->
    <div class="modal fade" id="modalPagamento" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="formPagamento" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Registrar Pagamento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_parcela" id="id_parcela_pagamento">
                    <label for="data_pagamento" class="form-label">Data do Pagamento</label>
                    <input type="date" class="form-control" name="data_pagamento" id="data_pagamento" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Confirmar Pagamento</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

    <script>
        function marcarComoPago(parcela_id) {
            if (!confirm("Confirmar pagamento desta parcela?")) return;

            $.post("php/marcar_pagamento.php", {
                id: parcela_id
            }, function(response) {
                if (response === "ok") {
                    location.reload();
                } else {
                    alert("Erro ao marcar pagamento: " + response);
                }
            });
        }
    </script>

<?php include "php/footer.php" ?>