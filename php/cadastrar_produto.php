<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.html");
    exit;
}

include 'conexao.php';

$nome = $_POST['nome'] ?? '';
$descricao = $_POST['descricao'] ?? '';
$categoria = $_POST['categoria'] ?? '';
$preco = str_replace(',', '.', $_POST['preco'] ?? '');
$tamanho = $_POST['tamanho'] ?? '';
$quantidade = isset($_POST['quantidade']) ? intval($_POST['quantidade']) : 1;
$status = $_POST['status'] ?? 'ativo';

$tamanhos_validos = ['P', 'M', 'G', 'GG', 'G1', 'G2', 'G3'];
if (!in_array($tamanho, $tamanhos_validos)) {
    echo "Tamanho inválido. Os tamanhos permitidos são: P, M, G, GG, G1, G2, G3.";
    exit;
}

if ($quantidade <= 0) {
    $status = 'inativo';
}

function redimensionarEConverterWebp($origem_tmp, $destino, $largura = 800, $altura = 800)
{
    list($largura_original, $altura_original, $tipo) = getimagesize($origem_tmp);

    switch ($tipo) {
        case IMAGETYPE_JPEG:
            $imagem = imagecreatefromjpeg($origem_tmp);
            break;
        case IMAGETYPE_PNG:
            $imagem = imagecreatefrompng($origem_tmp);
            break;
        case IMAGETYPE_GIF:
            $imagem = imagecreatefromgif($origem_tmp);
            break;
        case IMAGETYPE_WEBP:
            $imagem = imagecreatefromwebp($origem_tmp);
            break;
        default:
            return false;
    }

    $thumb = imagecreatetruecolor($largura, $altura);
    imagecopyresampled($thumb, $imagem, 0, 0, 0, 0, $largura, $altura, $largura_original, $altura_original);

    $resultado = imagewebp($thumb, $destino, 85);

    imagedestroy($imagem);
    imagedestroy($thumb);
    return $resultado;
}

function criarThumb($origem, $destino, $largura = 300, $altura = 300)
{
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
    $proporcao = min($largura_original / $largura, $altura_original / $altura);
    $novo_largura = $largura * $proporcao;
    $novo_altura = $altura * $proporcao;
    $x = ($largura_original - $novo_largura) / 2;
    $y = ($altura_original - $novo_altura) / 2;

    imagecopyresampled($thumb, $imagem, 0, 0, $x, $y, $largura, $altura, $novo_largura, $novo_altura);

    $resultado = imagewebp($thumb, $destino, 85);

    imagedestroy($imagem);
    imagedestroy($thumb);
    return $resultado;
}

if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
    $imagem = $_FILES['imagem'];
    $extensao = strtolower(pathinfo($imagem['name'], PATHINFO_EXTENSION));

    $extensoes_permitidas = ['jpg', 'jpeg', 'jfif', 'png', 'gif', 'webp'];
    if (!in_array($extensao, $extensoes_permitidas)) {
        echo "Formato de imagem não permitido. Use jpg, jpeg, png, gif ou webp.";
        exit;
    }

    $nomeImagem = uniqid() . '.webp';
    $caminhoOriginal = dirname(__DIR__) . '/assets/imagens/' . $nomeImagem;
    $caminhoThumb = dirname(__DIR__) . '/assets/imagens/thumbs/' . $nomeImagem;

    if (redimensionarEConverterWebp($imagem['tmp_name'], $caminhoOriginal)) {
        if (!criarThumb($caminhoOriginal, $caminhoThumb)) {
            echo "Erro ao criar a miniatura da imagem.";
            exit;
        }

        $stmt = $conn->prepare("INSERT INTO produtos (nome, descricao, categoria, preco, imagem, status, quantidade, tamanho) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssis", $nome, $descricao, $categoria, $preco, $nomeImagem, $status, $quantidade, $tamanho);

        if ($stmt->execute()) {
            echo "<script>alert('Produto cadastrado com sucesso!'); window.location.href='../painel_admin.php';</script>";
        } else {
            echo "Erro ao cadastrar produto: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Erro ao processar a imagem original.";
    }
} else {
    echo "Imagem do produto é obrigatória.";
}

$conn->close();
