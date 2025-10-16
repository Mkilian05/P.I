<?php
session_start();
require_once '../config/database.php';

// Proteção: Apenas usuários logados podem editar
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// Verifica se foi passado um ID na URL
if (!isset($_GET['id'])) {
    header("Location: ../views/admin/dashboard_admin.php?erro=Nenhum usuário selecionado.");
    exit;
}

$id_usuario_a_editar = intval($_GET['id']);
$usuario = null;

// Busca os dados do usuário no banco
$stmt = $conn->prepare("SELECT nome_usuario, email FROM usuarios WHERE id_usuario = ?");
$stmt->bind_param("i", $id_usuario_a_editar);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $usuario = $result->fetch_assoc();
} else {
    // Se não encontrar o usuário, redireciona
    header("Location: ../views/admin/dashboard_admin.php?erro=Usuário não encontrado.");
    exit;
}

$page = 'editar_usuario';
include_once '../includes/header.php';
include_once '../includes/navbar.php';
?>

<section class="py-5 mt-5">
    <div class="container d-flex justify-content-center">
        <div class="card" style="width: 100%; max-width: 500px;">
            <div class="card-body p-4">
                <h3 class="card-title text-center mb-4"><i class="fa-solid fa-user-pen me-2"></i> Editar Usuário</h3>
                
                <form action="../config/processa_edicao.php" method="POST">
                    <input type="hidden" name="id_usuario" value="<?= $id_usuario_a_editar; ?>">

                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($usuario['nome_usuario']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($usuario['email']); ?>" required>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="../views/admin/dashboard_admin.php" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php
$conn->close();
include_once '../includes/footer.php';
?>