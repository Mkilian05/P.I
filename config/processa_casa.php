<?php
// config/processa_casas.php

session_start();
require_once __DIR__ . '/../config/database.php';

// Proteção básica de sessão
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $acao = $_POST['acao'] ?? 'cadastrar'; 
    $id_usuario = $_SESSION['id_usuario']; // ID vindo do login seguro

    try {
        // --- GUARDIÃO DE SEGURANÇA ---
        // Verifica se a casa pertence ao usuário logado
        function verificarDonoCasa($conn, $id_casa, $id_usuario) {
            $sql = "SELECT id_casa FROM casa WHERE id_casa = ? AND fk_usuario_id_usuario = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $id_casa, $id_usuario);
            $stmt->execute();
            if ($stmt->get_result()->num_rows === 0) {
                throw new Exception("Acesso negado: Você não tem permissão para alterar esta casa.");
            }
        }

        // ====================================================
        // AÇÃO: EXCLUIR
        // ====================================================
        if ($acao === 'excluir') {
            $id = $_POST['id_casa'];
            if (empty($id) || !is_numeric($id)) throw new Exception("ID inválido.");

            // SEGURANÇA: Verifica dono antes de excluir
            verificarDonoCasa($conn, $id, $id_usuario);

            // Soft Delete
            $sql = "UPDATE casa SET is_deleted = 1, deleted_at = NOW() WHERE id_casa = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            
            if (!$stmt->execute()) throw new Exception("Erro ao excluir: " . $stmt->error);
            
            $_SESSION['sucesso'] = "Casa excluída com sucesso!";
            header("Location: ../views/casas.php");
            exit;
        }

        // ====================================================
        // DADOS COMUNS (Cadastrar e Editar)
        // ====================================================
        $nome   = $_POST['casaNome'];
        $cidade = $_POST['casaCidade'];
        $estado = $_POST['casaEstado'];
        $bairro = $_POST['casaBairro'];
        $cep    = $_POST['casaCep'];
        $numero = $_POST['casaNumero'];

        if (empty($nome) || empty($cidade) || empty($estado)) {
            throw new Exception("Preencha os campos obrigatórios!");
        }

        // ====================================================
        // AÇÃO: EDITAR
        // ====================================================
        if ($acao === 'editar') {
            $id = $_POST['id_casa'];

            // SEGURANÇA: Verifica dono antes de editar
            verificarDonoCasa($conn, $id, $id_usuario);

            $sql = "UPDATE casa SET nome_casa=?, numero_casa=?, sigla_estado=?, cep=?, cidade=?, bairro=? WHERE id_casa=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssi", $nome, $numero, $estado, $cep, $cidade, $bairro, $id);
            
            if (!$stmt->execute()) throw new Exception("Erro ao atualizar: " . $stmt->error);
            
            $_SESSION['sucesso'] = "Casa atualizada com sucesso!";

        // ====================================================
        // AÇÃO: CADASTRAR
        // ====================================================
        } else {
            // Aqui não precisa verificar, pois estamos CRIANDO com o ID do usuário
            $sql = "INSERT INTO casa (nome_casa, numero_casa, sigla_estado, cep, cidade, bairro, fk_usuario_id_usuario) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            // Note o último parâmetro: $id_usuario
            $stmt->bind_param("ssssssi", $nome, $numero, $estado, $cep, $cidade, $bairro, $id_usuario);
            
            if (!$stmt->execute()) throw new Exception("Erro ao cadastrar: " . $stmt->error);
            
            $_SESSION['sucesso'] = "Casa cadastrada com sucesso!";
        }

        $stmt->close();
        header("Location: ../views/casas.php");
        exit;

    } catch (Exception $e) {
        $_SESSION['erros'] = [$e->getMessage()];
        
        if ($acao === 'editar' && isset($id)) {
            header("Location: ../views/gerencia_casas.php?id=" . $id);
        } else {
            header("Location: ../views/casas.php");
        }
        exit;
    }
} else {
    header("Location: ../views/dashboard.php");
    exit;
}
