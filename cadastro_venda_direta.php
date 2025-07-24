<?php
require_once 'php/conexao.php';
include 'php/header.php';
?>

<div class="container mt-5">
    <div class="card mx-auto shadow-sm" style="max-width: 700px;">
        <div class="card-body">
            <h4 class="mb-4 text-center fw-bold">Cadastrar Venda Direta</h4>

            <!-- CLIENTE -->
            <div class="mb-3">
                <label class="form-label">Cliente</label>
                <div class="d-flex gap-2">
                    <select class="form-select" id="cliente" name="cliente">
                        <option selected disabled>Selecionar cliente</option>
                        <?php
                        $sql = "SELECT id, nome FROM clientes_vendas_diretas ORDER BY nome";
                        $res = mysqli_query($conn, $sql);
                        if ($res && mysqli_num_rows($res) > 0) {
                            while ($row = mysqli_fetch_assoc($res)) {
                                echo "<option value='{$row['id']}'>{$row['nome']}</option>";
                            }
                        } else {
                            echo "<option disabled>Nenhum cliente encontrado</option>";
                        }
                        ?>
                    </select>
                    <button class="btn btn-dark btn-sm" id="novoCliente" type="button" data-bs-toggle="modal" data-bs-target="#modalNovoCliente">Novo Cliente</button>
                </div>
            </div>

            <!-- DATA -->
            <div class="mb-3">
                <label class="form-label">Data da Venda</label>
                <input type="date" class="form-control" id="data_venda" name="data_venda" value="<?= date('Y-m-d') ?>">
            </div>

            <!-- PRODUTO -->
            <div class="mb-3">
                <label class="form-label">Produto</label>
                <div class="d-flex gap-2">
                    <input type="text" class="form-control" id="produtoInput" placeholder="Digite o nome do produto">
                    <button class="btn btn-dark" type="button" id="adicionarProduto">Adicionar</button>
                </div>
            </div>

            <!-- TABELA DE PRODUTOS -->
            <div class="table-responsive mb-3">
                <table class="table table-bordered text-center align-middle" id="tabelaProdutos">
                    <thead class="table-light">
                        <tr>
                            <th>Produto</th>
                            <th>Quantidade</th>
                            <th>Preço Unitário</th>
                            <th>Subtotal</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end fw-bold">Total:</td>
                            <td colspan="2" class="fw-bold" id="totalVenda">R$ 0,00</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- FORMA DE PAGAMENTO -->
            <div class="mb-3">
                <label class="form-label">Forma de Pagamento</label>
                <select class="form-select" id="forma_pagamento" name="forma_pagamento">
                    <option disabled selected>Selecionar forma de pagamento</option>
                    <option value="pix">Pix</option>
                    <option value="dinheiro">Dinheiro</option>
                    <option value="credito">Cartão de crédito</option>
                    <option value="debito">Cartão de débito</option>
                    <option value="prazo">À prazo</option> <!-- Mostra com acento, envia sem -->
                </select>
            </div>
            <!-- CAMPOS EXTRAS: valor pago, parcelas, troco -->
            <div id="parcelasWrapper" class="mb-3"></div>

            <!-- OBSERVAÇÕES -->
            <div class="mb-3">
                <label class="form-label">Observações</label>
                <textarea class="form-control" id="observacoes" name="observacoes" rows="2" placeholder="Opcional"></textarea>
            </div>

            <!-- BOTÕES -->
            <div class="d-grid">
                <button class="btn btn-success" id="salvarVenda" type="button">Salvar Venda</button>
                <br>
                <a class="btn btn-dark" href="painel_admin.php">← Voltar para o painel</a>
            </div>
        </div>
    </div>
</div>

<!-- MODAL NOVO CLIENTE -->
<div class="modal fade" id="modalNovoCliente" tabindex="-1" aria-labelledby="modalNovoClienteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalNovoClienteLabel">Cadastrar Novo Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <div class="mb-2">
                    <label class="form-label">Nome</label>
                    <input type="text" id="novo_nome" class="form-control" placeholder="Nome Completo">
                </div>
                <div class="mb-2">
                    <label class="form-label">Telefone</label>
                    <input type="text" id="novo_telefone" class="form-control" placeholder="(00) 00000-0000">
                </div>
                <div class="mb-2">
                    <label class="form-label">Data de Nascimento</label>
                    <input type="date" id="novo_nascimento" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="salvarNovoCliente">Salvar Cliente</button>
            </div>
        </div>
    </div>
</div>

<script src="assets/js/venda_direta.js"></script>

<?php include 'php/footer.php'; ?>