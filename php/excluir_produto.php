<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.html");
    exit;
}

include 'conexao.php';

$id = $_GET['id'] ?? '';

if (!$id) {
    echo "ID do produto não especificado.";
    exit;
}

// Buscar imagem atual para excluir os arquivos
$res = $conn->query("SELECT imagem FROM produtos WHERE id = $id");
if ($res && $res->num_rows > 0) {
    $imagem = $res->fetch_assoc()['imagem'];

    $caminhoImagem = dirname(__DIR__) . '/assets/imagens/' . $imagem;
    $caminhoThumb = dirname(__DIR__) . '/assets/imagens/thumbs/' . $imagem;

    @unlink($caminhoImagem);
    @unlink($caminhoThumb);
}

// Excluir do banco
$sql = "DELETE FROM produtos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "<script>alert('Produto excluído com sucesso!'); window.location.href='../lista_admin.php';</script>";
} else {
    echo "Erro ao excluir produto: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
