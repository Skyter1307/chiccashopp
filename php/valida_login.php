<?php
session_start();

$usuario = $_POST['usuario'] ?? '';
$senha = $_POST['senha'] ?? '';

if ($usuario === 'CHICCA' && $senha === '120599') {
    $_SESSION['admin'] = true;
    header("Location: ../painel_admin.php");
    exit;
} else {
    echo "<script>alert('Usuário ou senha inválidos!'); window.location.href='../login.html';</script>";
}
