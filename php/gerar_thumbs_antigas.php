<?php
include '../php/conexao.php';

// Função para criar o thumb em .webp
function criarThumbWebP($origem, $destinoWebp, $largura_max = 300) {
    $ext = strtolower(pathinfo($origem, PATHINFO_EXTENSION));

    switch ($ext) {
        case 'jpg':
        case 'jpeg':
        case 'jfif':
            $img = @imagecreatefromjpeg($origem);
            break;
        case 'png':
            $img = @imagecreatefrompng($origem);
            break;
        case 'webp':
            $img = @imagecreatefromwebp($origem);
            break;
        default:
            return false;
    }

    if (!$img) return false;

    $largura = imagesx($img);
    $altura = imagesy($img);
    $nova_largura = $largura_max;
    $nova_altura = floor($altura * ($nova_largura / $largura));

    $thumb = imagecreatetruecolor($nova_largura, $nova_altura);
    imagecopyresampled($thumb, $img, 0, 0, 0, 0, $nova_largura, $nova_altura, $largura, $altura);

    $salvou = imagewebp($thumb, $destinoWebp, 80);
    imagedestroy($img);
    imagedestroy($thumb);

    return $salvou;
}


// HTML layout
echo '<!DOCTYPE html><html lang="pt-br"><head>';
echo '<meta charset="UTF-8">';
echo '<title>Geração de Thumbnails</title>';
echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">';
echo '</head><body class="bg-light">';

echo '<div class="container py-5">';
echo '<h2 class="mb-4">Geração de Miniaturas (Thumbs) - Formato .webp</h2>';

$result = $conn->query("SELECT imagem FROM produtos WHERE imagem IS NOT NULL AND imagem != ''");

$thumbsCriadas = 0;

while ($row = $result->fetch_assoc()) {
    $imagem = $row['imagem'];
    $nome_base = pathinfo($imagem, PATHINFO_FILENAME);
    $caminhoOriginal = dirname(__DIR__) . '/assets/imagens/' . $imagem;
    $caminhoThumb = dirname(__DIR__) . '/assets/imagens/thumbs/' . $nome_base . '.webp';

    if (!file_exists($caminhoThumb) && file_exists($caminhoOriginal)) {
        if (criarThumbWebP($caminhoOriginal, $caminhoThumb)) {
            echo '<div class="alert alert-success">✅ Miniatura criada para: ' . htmlspecialchars($imagem) . '</div>';
            $thumbsCriadas++;
        } else {
            echo '<div class="alert alert-warning">⚠️ Erro ao criar miniatura para: ' . htmlspecialchars($imagem) . '</div>';
        }
    } elseif (file_exists($caminhoThumb)) {
        echo '<div class="alert alert-secondary">📂 Miniatura já existia: ' . htmlspecialchars(basename($caminhoThumb)) . '</div>';
    } else {
        echo '<div class="alert alert-danger">❌ Imagem original não encontrada: ' . htmlspecialchars($imagem) . '</div>';
    }
}

if ($thumbsCriadas === 0) {
    echo '<div class="alert alert-info mt-4">Nenhuma nova miniatura foi gerada.</div>';
}

echo '<a href="../lista_admin.php" class="btn btn-primary mt-4">⬅️ Voltar à Lista de Produtos</a>';
echo '</div></body></html>';

$conn->close();
?>
