<?php
session_start();
require_once '../config/database.php';

// Recebe dados do formulário
$email = trim($_POST['email'] ?? '');
$senha = trim($_POST['senha'] ?? '');

// Verifica se estão preenchidos
if (empty($email) || empty($senha)) {
    $_SESSION['erro_login'] = "Preencha todos os campos!";
    header("Location: ../views/login.php");
    exit;
}

try {
    // Busca usuário pelo email
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email LIMIT 1");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifica senha
    if ($usuario && password_verify($senha, $usuario['senha'])) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];

        header("Location: ../views/admin/dashboard_admin.php");
        exit;
    } else {
        $_SESSION['erro_login'] = "E-mail ou senha incorretos!";
        header("Location: ../views/login.php");
        exit;
    }
} catch (PDOException $e) {
    $_SESSION['erro_login'] = "Erro no servidor: " . $e->getMessage();
    header("Location: ../views/login.php");
    exit;
}
?>
