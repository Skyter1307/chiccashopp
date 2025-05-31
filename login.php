<?php
session_start();
if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
    header('Location: painel_admin.php');
    exit();
}
if (isset($_SESSION['cliente']) && $_SESSION['cliente'] === true) {
    header('Location: index.php');
    exit();
}
?>

<?php include 'php/header.php' ?>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card shadow p-4" style="width: 100%; max-width: 400px;">
        <h2 class="text-center mb-4">ChiccaShop</h2>

        <!-- Abas Login / Cadastro -->
        <ul class="nav nav-pills mb-3 justify-content-center " id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="login-tab" data-bs-toggle="pill" data-bs-target="#login" type="button" role="tab">Login</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="cadastro-tab" data-bs-toggle="pill" data-bs-target="#cadastro" type="button" role="tab">Cadastro</button>
            </li>
        </ul>

        <div class="tab-content" id="pills-tabContent">

            <!-- Aba Login -->
            <div class="tab-pane fade show active" id="login" role="tabpanel">
                <form action="login_process.php" method="POST">
                    <div class="mb-3">
                        <label for="email_login" class="form-label">Usuário ou Email</label>
                        <input type="text" name="email" id="email_login" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="senha_login" class="form-label">Senha</label>
                        <input type="password" name="senha" id="senha_login" class="form-control" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Entrar</button>
                    </div>
                </form>
            </div>

            <!-- Aba Cadastro -->
            <div class="tab-pane fade" id="cadastro" role="tabpanel">
                <form action="cadastro.php" method="POST">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" name="nome" id="nome" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="email_cadastro" class="form-label">Email</label>
                        <input type="email" name="email" id="email_cadastro" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="senha_cadastro" class="form-label">Senha</label>
                        <input type="password" name="senha" id="senha_cadastro" class="form-control" required>
                        <small class="text-muted">Mínimo 6 caracteres, letra, número e caractere especial.</small>
                    </div>
                    <div class="mb-3">
                        <label for="confirma_senha" class="form-label">Confirme a Senha</label>
                        <input type="password" name="confirma_senha" id="confirma_senha" class="form-control" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success">Cadastrar</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<?php include 'php/footer.php'; ?>

</body>
</html>
