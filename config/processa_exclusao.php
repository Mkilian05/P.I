<?php
session_start();
require_once 'database.php';

// Proteção: Apenas usuários logados podem excluir
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../views/login.php");
    exit;
}

// Verifica se o ID do usuário a ser excluído foi passado pela URL
if (isset($_GET['id'])) {
    $id_usuario_a_excluir = intval($_GET['id']); // intval para segurança

    // Evita que o usuário exclua a si mesmo
    if ($id_usuario_a_excluir == $_SESSION['usuario_id']) {
        header("Location: ../views/admin/dashboard_admin.php?erro=Você não pode excluir sua própria conta!");
        exit;
    }

    try {
        // Prepara e executa a query de exclusão
        $stmt = $conn->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
        $stmt->bind_param("i", $id_usuario_a_excluir);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            // Sucesso
            header("Location: ../views/admin/dashboard_admin.php?sucesso=Usuário excluído com sucesso!");
        } else {
            // Usuário não encontrado ou falha
            header("Location: ../views/admin/dashboard_admin.php?erro=Usuário não encontrado ou já foi excluído.");
        }
        exit;

    } catch (mysqli_sql_exception $e) {
        header("Location: ../views/admin/dashboard_admin.php?erro=Ocorreu um erro ao excluir o usuário.");
        exit;
    }

} else {
    // Se nenhum ID for fornecido, redireciona de volta
    header("Location: ../views/admin/dashboard_admin.php");
    exit;
}