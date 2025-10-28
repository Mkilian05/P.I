<?php
session_start();
require_once '../config/database.php';

// Proteção: Apenas usuários logados
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../views/login.php");
    exit;
}

// Verifica se o formulário foi enviado (método POST)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    // Pega os dados do formulário
    $id_usuario = intval($_POST['id_usuario']);
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);

    // Validação simples
    if (empty($id_usuario) || empty($nome) || empty($email)) {
        header("Location: ../views/admin/dashboard_admin.php?erro=Todos os campos são obrigatórios.");
        exit;
    }

    try {
        // Prepara e executa a query de atualização
        $stmt = $conn->prepare("UPDATE usuarios SET nome_usuario = ?, email = ? WHERE id_usuario = ?");
        $stmt->bind_param("ssi", $nome, $email, $id_usuario);
        $stmt->execute();

        header("Location: ../views/admin/dashboard_admin.php?sucesso=Usuário atualizado com sucesso!");
        exit;

    } catch (mysqli_sql_exception $e) {
        // Verifica se o erro é de e-mail duplicado
        if ($conn->errno === 1062) {
             header("Location: ../views/editar_usuario.php?id=$id_usuario&erro=Este e-mail já está em uso por outra conta.");
        } else {
             header("Location: ../views/admin/dashboard_admin.php?erro=Ocorreu um erro ao atualizar o usuário.");
        }
        exit;
    }

} else {
    // Se o acesso não for via POST, redireciona
    header("Location: ../views/admin/dashboard_admin.php");
    exit;
}