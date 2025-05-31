<?php
session_start();
include_once "conexao.php"; // Banco de dados

// Dados do formulário
$email = trim($_POST['email']);
$senha = trim($_POST['senha']);

// Verificação Admin (dados fixos)
$loginAdmin = "CHICCA";
$senhaAdmin = "120599";

// Verifica se é admin
if ($email === $loginAdmin && $senha === $senhaAdmin) {
    $_SESSION['admin'] = true;
    header("Location: painel_admin.php");
    exit;
}

// Se não for admin, é cliente
$sql = $conn->prepare("SELECT id, nome, senha FROM clientes WHERE email = ?");
$sql->bind_param("s", $email);
$sql->execute();
$resultado = $sql->get_result();

if ($resultado->num_rows > 0) {
    $usuario = $resultado->fetch_assoc();

    // Verifica a senha
    if (password_verify($senha, $usuario['senha'])) {
        $_SESSION['cliente'] = true;
        $_SESSION['cliente_nome'] = $usuario['nome'];
        $_SESSION['cliente_id'] = $usuario['id'];
        header("Location: index.php");
        exit;
    } else {
        echo "<script>alert('Senha incorreta!'); history.back();</script>";
        exit;
    }
} else {
    echo "<script>alert('E-mail não encontrado!'); history.back();</script>";
    exit;
}
?>
