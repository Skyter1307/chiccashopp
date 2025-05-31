<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.html");
    exit;
}

include 'conexao.php';

// Coletando dados do formulário
$nome = $_POST['nome'] ?? '';
$descricao = $_POST['descricao'] ?? '';
$categoria = $_POST['categoria'] ?? '';
$preco = $_POST['preco'] ?? '';
$status = $_POST['status'] ?? 'ativo';

function criarThumb($origem, $destino, $largura = 300, $altura = 300) {
    list($largura_original, $altura_original, $tipo) = getimagesize($origem);

    switch ($tipo) {
        case IMAGETYPE_JPEG:
            $imagem = imagecreatefromjpeg($origem);
            break;
        case IMAGETYPE_PNG:
            $imagem = imagecreatefrompng($origem);
            break;
        case IMAGETYPE_GIF:
            $imagem = imagecreatefromgif($origem);
            break;
        case IMAGETYPE_WEBP:
            $imagem = imagecreatefromwebp($origem);
            break;
        default:
            return false;
    }

    $thumb = imagecreatetruecolor($largura, $altura);

    // Corte central
    $proporcao = min($largura_original / $largura, $altura_original / $altura);
    $novo_largura = $largura * $proporcao;
    $novo_altura = $altura * $proporcao;

    $x = ($largura_original - $novo_largura) / 2;
    $y = ($altura_original - $novo_altura) / 2;

    imagecopyresampled($thumb, $imagem, 0, 0, $x, $y, $largura, $altura, $novo_largura, $novo_altura);

    // Salvar com base no tipo
    switch ($tipo) {
        case IMAGETYPE_JPEG:
            imagejpeg($thumb, $destino, 85);
            break;
        case IMAGETYPE_PNG:
            imagepng($thumb, $destino);
            break;
        case IMAGETYPE_GIF:
            imagegif($thumb, $destino);
            break;
        case IMAGETYPE_WEBP:
            imagewebp($thumb, $destino);
            break;
    }

    imagedestroy($imagem);
    imagedestroy($thumb);
    return true;
}

if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
    $imagem = $_FILES['imagem'];
    $extensao = strtolower(pathinfo($imagem['name'], PATHINFO_EXTENSION));

    // Validação de extensão permitida
    $extensoes_permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (!in_array($extensao, $extensoes_permitidas)) {
        echo "Formato de imagem não permitido. Use jpg, jpeg, png, gif ou webp.";
        exit;
    }

    $nomeImagem = uniqid() . '.' . $extensao;
    $caminhoOriginal = dirname(__DIR__) . '/imagens/' . $nomeImagem;
    $caminhoThumb = dirname(__DIR__) . '/imagens/thumbs/' . $nomeImagem;

    // Move a imagem para a pasta original
    if (move_uploaded_file($imagem['tmp_name'], $caminhoOriginal)) {

        // Criação da miniatura
        if (!criarThumb($caminhoOriginal, $caminhoThumb)) {
            echo "Erro ao criar a miniatura da imagem.";
            exit;
        }

        // Inserção no banco de dados
        $stmt = $conn->prepare("INSERT INTO produtos (nome, descricao, categoria, preco, imagem, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $nome, $descricao, $categoria, $preco, $nomeImagem, $status);

        if ($stmt->execute()) {
            echo "<script>alert('Produto cadastrado com sucesso!'); window.location.href='../painel_admin.php';</script>";
        } else {
            echo "Erro ao cadastrar produto: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Erro ao salvar a imagem original.";
    }
} else {
    echo "Imagem do produto é obrigatória.";
}

$conn->close();
?>
