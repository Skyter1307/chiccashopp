<?php
function criarThumb($origem, $destino, $largura = 300, $altura = 300) {
    // Verifica e cria a pasta de thumbs se necessário
    $pasta = dirname($destino);
    if (!is_dir($pasta)) {
        mkdir($pasta, 0777, true);
    }

    // Detecta tipo da imagem
    $info = getimagesize($origem);
    if (!$info) return false;

    $tipo = $info[2];

    switch ($tipo) {
        case IMAGETYPE_JPEG:
            $img = imagecreatefromjpeg($origem);
            break;
        case IMAGETYPE_PNG:
            $img = imagecreatefrompng($origem);
            break;
        case IMAGETYPE_WEBP:
            $img = imagecreatefromwebp($origem);
            break;
        default:
            return false;
    }

    if (!$img) return false;

    $larguraOriginal = imagesx($img);
    $alturaOriginal = imagesy($img);

    $thumb = imagecreatetruecolor($largura, $altura);

    // Mantém fundo transparente para PNG/WebP
    if ($tipo == IMAGETYPE_PNG || $tipo == IMAGETYPE_WEBP) {
        imagealphablending($thumb, false);
        imagesavealpha($thumb, true);
        $transparente = imagecolorallocatealpha($thumb, 0, 0, 0, 127);
        imagefilledrectangle($thumb, 0, 0, $largura, $altura, $transparente);
    }

    $src_aspect = $larguraOriginal / $alturaOriginal;
    $thumb_aspect = $largura / $altura;

    if ($src_aspect >= $thumb_aspect) {
        $new_height = $altura;
        $new_width = (int)($larguraOriginal / ($alturaOriginal / $altura));
    } else {
        $new_width = $largura;
        $new_height = (int)($alturaOriginal / ($larguraOriginal / $largura));
    }

    $x = (int)(($largura - $new_width) / 2);
    $y = (int)(($altura - $new_height) / 2);

    imagecopyresampled($thumb, $img, $x, $y, 0, 0, $new_width, $new_height, $larguraOriginal, $alturaOriginal);

    // Salva com base no tipo original
    switch ($tipo) {
        case IMAGETYPE_JPEG:
            imagejpeg($thumb, $destino, 85);
            break;
        case IMAGETYPE_PNG:
            imagepng($thumb, $destino);
            break;
        case IMAGETYPE_WEBP:
            imagewebp($thumb, $destino);
            break;
    }

    imagedestroy($img);
    imagedestroy($thumb);

    return true;
}
?>
