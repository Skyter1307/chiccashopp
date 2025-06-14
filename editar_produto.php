<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.html");
    exit;
}

include 'php/conexao.php';

$id = $_GET['id'] ?? '';
if (!$id) {
    echo "ID inválido.";
    exit;
}

$sql = "SELECT * FROM produtos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    echo "Produto não encontrado.";
    exit;
}

$produto = $resultado->fetch_assoc();
$stmt->close();
$conn->close();
?>

<?php include 'php/header_admin.php'; ?>

<div class="container py-5">
    <h2 class="mb-4">Editar Produto</h2>

    <form action="php/atualizar_produto.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $produto['id'] ?>">

        <div class="mb-3">
            <label class="form-label">Nome</label>
            <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($produto['nome']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Descrição</label>
            <textarea name="descricao" class="form-control" required><?= htmlspecialchars($produto['descricao']) ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Preço</label>
            <input type="text" name="preco" class="form-control" value="<?= $produto['preco'] ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Quantidade</label>
            <input type="number" name="quantidade" class="form-control" value="<?= $produto['quantidade'] ?>" min="0" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="ativo" <?= $produto['status'] == 'ativo' ? 'selected' : '' ?>>Ativo</option>
                <option value="inativo" <?= $produto['status'] == 'inativo' ? 'selected' : '' ?>>Inativo</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Tamanho</label>
            <select name="tamanho" class="form-select" required>
                <?php
                $tamanhos = ['P', 'M', 'G', 'GG', 'G1', 'G2', 'G3'];
                foreach ($tamanhos as $t) {
                    $selected = ($produto['tamanho'] == $t) ? 'selected' : '';
                    echo "<option value=\"$t\" $selected>$t</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Imagem Atual</label><br>
            <img src="assets/imagens/thumbs/<?= $produto['imagem'] ?>" width="100">
        </div>

        <div class="mb-3">
            <label class="form-label">Nova Imagem (opcional)</label>
            <input type="file" name="imagem" class="form-control" accept="image/*">
        </div>

        <button type="submit" class="btn btn-danger">Salvar Alterações</button>
        <a href="lista_admin.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?php include 'php/footer.php'; ?>