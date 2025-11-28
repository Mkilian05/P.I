<?php
// config/processa_meta.php
session_start();
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_SESSION['id_usuario'];
    $valor_meta = $_POST['valor_meta'];

    // Tratamento simples: troca vírgula por ponto para o banco
    $valor_meta = floatval(str_replace(',', '.', $valor_meta));

    if ($valor_meta <= 0) {
        $_SESSION['erros'] = ["O valor da meta deve ser maior que zero."];
        header("Location: ../views/dashboard.php");
        exit;
    }

    try {
        // Truque do MySQL: Insere, mas se já existir (pelo ID do usuário), atualiza!
        $sql = "INSERT INTO metas_usuario (fk_usuario_id_usuario, valor_meta) 
                VALUES (?, ?) 
                ON DUPLICATE KEY UPDATE valor_meta = VALUES(valor_meta)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("id", $id_usuario, $valor_meta);
        
        if ($stmt->execute()) {
            $_SESSION['sucesso'] = "Meta de gastos atualizada!";
        } else {
            throw new Exception("Erro ao salvar meta.");
        }
        
    } catch (Exception $e) {
        $_SESSION['erros'] = ["Erro: " . $e->getMessage()];
    }

    header("Location: ../views/dashboard.php");
    exit;
}