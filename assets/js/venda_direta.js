document.addEventListener("DOMContentLoaded", function () {
    const container = document.querySelector(".card-body");
    const salvarBtn = document.getElementById("salvarVenda");
    const formaPagamento = document.getElementById("forma_pagamento");
    const parcelasWrapper = document.getElementById("parcelasWrapper");

    // HTML fixo para valor pago e troco
    const valorPagoHTML = `
        <label class="form-label">Valor Pago</label>
        <input type="number" min="0" step="0.01" class="form-control mb-2" id="valorPago" placeholder="Ex: 100.00">
        <div class="form-text fw-bold" id="valorTroco">Troco: R$ 0,00</div>
    `;

    // Campo de número de parcelas
    const campoParcelas = `
        <div class="mb-2">
            <label class="form-label">Número de Parcelas</label>
            <input type="number" class="form-control" id="numParcelas" min="1" placeholder="Ex: 3">
        </div>
        <div id="tabelaParcelasContainer" class="mb-2"></div>
    `;

    formaPagamento.addEventListener("change", function () {
        parcelasWrapper.innerHTML = valorPagoHTML;
        if (this.value === "prazo") {
            parcelasWrapper.innerHTML += campoParcelas;
        }
        atualizarTotais();
    });

    // Geração da tabela de parcelas
    container.addEventListener("input", function (e) {
        if (e.target.id === "numParcelas") {
            const qtd = parseInt(e.target.value);
            const tabela = document.getElementById("tabelaParcelasContainer");

            if (!isNaN(qtd) && qtd > 0) {
                let html = `
                    <label class="form-label">Detalhes das Parcelas</label>
                    <table class="table table-bordered text-center">
                        <thead><tr><th>Parcela</th><th>Data</th><th>Valor</th></tr></thead><tbody>
                `;
                for (let i = 1; i <= qtd; i++) {
                    html += `
                        <tr>
                            <td>${i}</td>
                            <td><input type="date" class="form-control" name="data_parcela_${i}"></td>
                            <td><input type="number" step="0.01" class="form-control parcelaValor" name="valor_parcela_${i}"></td>
                        </tr>`;
                }
                html += "</tbody></table>";
                tabela.innerHTML = html;
            } else {
                tabela.innerHTML = "";
            }
        }

        if (e.target.id === "valorPago" || e.target.classList.contains("parcelaValor")) {
            atualizarTotais();
        }
    });

    // Produtos
    document.getElementById("adicionarProduto").addEventListener("click", function () {
        const produto = document.getElementById("produtoInput").value.trim();
        if (produto === "") return;

        const tableBody = document.querySelector("#tabelaProdutos tbody");
        const row = document.createElement("tr");

        row.innerHTML = `
            <td>${produto}</td>
            <td><input type="number" min="1" value="1" class="form-control quantidade"></td>
            <td><input type="number" step="0.01" value="0.00" class="form-control preco"></td>
            <td class="subtotal">R$ 0,00</td>
            <td><button class="btn btn-danger btn-sm removerProduto">Remover</button></td>
        `;
        tableBody.appendChild(row);
        document.getElementById("produtoInput").value = "";
        atualizarTotais();
    });

    document.querySelector("#tabelaProdutos tbody").addEventListener("input", atualizarTotais);
    document.querySelector("#tabelaProdutos tbody").addEventListener("click", function (e) {
        if (e.target.classList.contains("removerProduto")) {
            e.target.closest("tr").remove();
            atualizarTotais();
        }
    });

    function atualizarTotais() {
        let total = 0;
        const linhas = document.querySelectorAll("#tabelaProdutos tbody tr");

        linhas.forEach(linha => {
            const qtd = parseFloat(linha.querySelector(".quantidade").value) || 0;
            const preco = parseFloat(linha.querySelector(".preco").value) || 0;
            const subtotal = qtd * preco;
            linha.querySelector(".subtotal").textContent = "R$ " + subtotal.toFixed(2).replace(".", ",");
            total += subtotal;
        });

        document.getElementById("totalVenda").textContent = "R$ " + total.toFixed(2).replace(".", ",");

        const valorPago = parseFloat(document.getElementById("valorPago")?.value || 0);
        const trocoEl = document.getElementById("valorTroco");

        let valorParcelas = 0;
        document.querySelectorAll(".parcelaValor").forEach(input => {
            valorParcelas += parseFloat(input.value) || 0;
        });

        const tipo = formaPagamento.value;
        const valorBase = (tipo === "prazo") ? valorParcelas : valorPago;
        const troco = valorBase - total;

        if (trocoEl) {
            trocoEl.textContent = "Troco: R$ " + troco.toFixed(2).replace(".", ",");
            trocoEl.classList.toggle("text-danger", troco < 0);
            trocoEl.classList.toggle("text-success", troco >= 0);
        }
    }

    // Modal cliente
    document.getElementById("salvarNovoCliente").addEventListener("click", () => {
        const nome = document.getElementById("novo_nome").value.trim();
        const tel = document.getElementById("novo_telefone").value.trim();
        const nasc = document.getElementById("novo_nascimento").value;

        if (!nome || !tel || !nasc) return alert("Preencha todos os campos.");

        fetch('php/salvar_cliente_venda_direta.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ nome, telefone: tel, nascimento: nasc })
        })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                const select = document.getElementById("cliente");
                const opt = document.createElement("option");
                opt.value = res.id;
                opt.text = nome;
                opt.selected = true;
                select.appendChild(opt);

                bootstrap.Modal.getInstance(document.getElementById('modalNovoCliente')).hide();

                document.getElementById("novo_nome").value = "";
                document.getElementById("novo_telefone").value = "";
                document.getElementById("novo_nascimento").value = "";

                alert("Cliente salvo com sucesso!");
            } else {
                alert("Erro ao salvar cliente.");
            }
        })
        .catch(err => {
            console.error(err);
            alert("Erro ao tentar salvar o cliente.");
        });
    });

    // Corrige fundo escuro ao fechar modal
    document.addEventListener('hidden.bs.modal', function () {
        document.body.classList.remove('modal-open');
        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
    });

    document.getElementById("salvarVenda").addEventListener("click", function () {
    const clienteId = document.getElementById("cliente").value;
    const dataVenda = document.getElementById("data_venda").value;
    const formaPagamento = document.getElementById("forma_pagamento").value;
    const observacoes = document.getElementById("observacoes").value.trim();

    // Total da venda (pegando do campo total já calculado na tela)
    const totalTexto = document.getElementById("totalVenda").textContent.replace("R$ ", "").replace(",", ".");
    const valorTotal = parseFloat(totalTexto) || 0;

    // Parcelas (se for à prazo)
    let numParcelas = 0;
    let listaDeParcelas = [];

    if (formaPagamento === "prazo") {
        numParcelas = parseInt(document.getElementById("numParcelas").value) || 0;

        for (let i = 1; i <= numParcelas; i++) {
            const campoData = document.querySelector(`input[name="data_parcela_${i}"]`);
            const campoValor = document.querySelector(`input[name="valor_parcela_${i}"]`);

            if (campoData && campoValor) {
                const data = campoData.value;
                const valor = parseFloat(campoValor.value) || 0;

                if (data && valor > 0) {
                    listaDeParcelas.push({ data, valor });
                }
            }
        }
    }

    // Validação mínima
    if (!clienteId || clienteId === "Selecionar cliente" || !dataVenda || !formaPagamento || valorTotal <= 0) {
        alert("Preencha todos os campos obrigatórios e adicione pelo menos um produto.");
        return;
    }

    // Montar os dados a serem enviados
    const dados = new URLSearchParams();
    dados.append("cliente_id", clienteId);
    dados.append("data_venda", dataVenda);
    dados.append("valor_total", valorTotal.toFixed(2));
    dados.append("forma_pagamento", formaPagamento);
    dados.append("parcelas", numParcelas);
    dados.append("observacoes", observacoes);
    dados.append("dados_parcelas", JSON.stringify(listaDeParcelas)); // muito importante enviar como JSON

    // Enviar via fetch
    fetch("php/salvar_venda_direta.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: dados
    })
    .then(response => response.json())
    .then(res => {
        if (res.success) {
            alert("Venda salva com sucesso!");
            location.reload(); // ou redirecionar para outra página, se quiser
        } else {
            alert("Erro ao salvar venda: " + res.mensagem);
        }
    })
    .catch(erro => {
        console.error("Erro na requisição:", erro);
        alert("Erro inesperado ao tentar salvar a venda.");
    });
});

});

document.addEventListener("DOMContentLoaded", () => {
    const modalPagamento = new bootstrap.Modal(document.getElementById('modalPagamento'));

    // Abrir modal
    document.querySelectorAll(".marcar-pago").forEach(btn => {
        btn.addEventListener("click", () => {
            const id = btn.dataset.id;
            document.getElementById("id_parcela_pagamento").value = id;
            document.getElementById("data_pagamento").value = ""; // limpa o campo
            modalPagamento.show();
        });
    });

    // Submeter pagamento
    document.getElementById("formPagamento").addEventListener("submit", function (e) {
        e.preventDefault();

        const id = document.getElementById("id_parcela_pagamento").value;
        const data = document.getElementById("data_pagamento").value;

        if (!data) {
            alert("Informe a data do pagamento.");
            return;
        }

        fetch("php/registrar_pagamento_parcela.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams({ id_parcela: id, data_pagamento: data })
        })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                alert("Pagamento registrado com sucesso!");
                location.reload();
            } else {
                alert("Erro ao registrar pagamento: " + res.mensagem);
            }
        })
        .catch(err => {
            console.error(err);
            alert("Erro inesperado ao registrar o pagamento.");
        });
    });
});
