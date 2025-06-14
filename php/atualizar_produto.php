<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.html");
    exit;
}

include 'conexao.php';

$id = $_POST['id'] ?? '';
$nome = $_POST['nome'] ?? '';
$descricao = $_POST['descricao'] ?? '';
$preco = $_POST['preco'] ?? '';
$quantidade = $_POST['quantidade'] ?? '';
$status = $_POST['status'] ?? '';
$tamanho = $_POST['tamanho'] ?? '';

if (!$id || !$nome || !$descricao || !$preco || !$quantidade || !$status || !$tamanho) {
    echo "Todos os campos são obrigatórios.";
    exit;
}

// Verifica se uma nova imagem foi enviada
if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
    $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
    $novoNome = uniqid() . '.' . $ext;
    $caminhoOriginal = "../assets/imagens/original/" . $novoNome;
    $caminhoThumb = "../assets/imagens/thumbs/" . $novoNome;

    // Salva a imagem original
    move_uploaded_file($_FILES['imagem']['tmp_name'], $caminhoOriginal);

    // Redimensiona a imagem para 800x800
    list($largura, $altura) = getimagesize($caminhoOriginal);
    $imagem = imagecreatefromstring(file_get_contents($caminhoOriginal));
    $nova = imagecreatetruecolor(800, 800);
    imagecopyresampled($nova, $imagem, 0, 0, 0, 0, 800, 800, $largura, $altura);
    imagejpeg($nova, $caminhoThumb, 90);

    // Atualiza com nova imagem
    $sql = "UPDATE produtos SET nome=?, descricao=?, preco=?, quantidade=?, status=?, tamanho=?, imagem=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdisssi", $nome, $descricao, $preco, $quantidade, $status, $tamanho, $novoNome, $id);
} else {
    // Atualiza sem nova imagem
    $sql = "UPDATE produtos SET nome=?, descricao=?, preco=?, quantidade=?, status=?, tamanho=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdissi", $nome, $descricao, $preco, $quantidade, $status, $tamanho, $id);
}

if ($stmt->execute()) {
    header("Location: ../lista_admin.php");
    exit;
} else {
    echo "Erro ao atualizar produto.";
}

$stmt->close();
$conn->close();
