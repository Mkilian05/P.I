<?php
// /config/processa_ambiente.php

session_start();
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $acao = $_POST['acao'] ?? 'cadastrar';
    $id_usuario = $_SESSION['id_usuario'] ?? 1; // Pega usuario logado

    try {
        // --- FUNÇÃO AUXILIAR DE SEGURANÇA ---
        // Verifica se a CASA selecionada ou atual pertence ao usuário
        function verificarDonoCasa($conn, $id_casa, $id_usuario) {
            $sql = "SELECT id_casa FROM casa WHERE id_casa = ? AND fk_usuario_id_usuario = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $id_casa, $id_usuario);
            $stmt->execute();
            if ($stmt->get_result()->num_rows === 0) {
                throw new Exception("Acesso negado: Esta casa não te pertence.");
            }
        }
        
        // Verifica se o AMBIENTE pertence a uma casa do usuário
        function verificarDonoAmbiente($conn, $id_ambiente, $id_usuario) {
            $sql = "SELECT a.id_ambiente FROM ambiente a JOIN casa k ON a.fk_casa_id_casa = k.id_casa WHERE a.id_ambiente = ? AND k.fk_usuario_id_usuario = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $id_ambiente, $id_usuario);
            $stmt->execute();
            if ($stmt->get_result()->num_rows === 0) {
                throw new Exception("Acesso negado: Este ambiente não te pertence.");
            }
        }

        // --- EXCLUIR ---
        if ($acao === 'excluir') {
            $id = $_POST['id_ambiente'];
            if (empty($id)) throw new Exception("ID inválido.");

            // SEGURANÇA: Verifica dono antes de excluir
            verificarDonoAmbiente($conn, $id, $id_usuario);

            $sql = "UPDATE ambiente SET is_deleted = 1, deleted_at = NOW() WHERE id_ambiente = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            if (!$stmt->execute()) throw new Exception("Erro ao excluir.");
            
            $_SESSION['sucesso'] = "Ambiente excluído!";
            header("Location: ../views/ambientes.php");
            exit;
        }

        // --- DADOS COMUNS ---
        $nome    = $_POST['ambienteNome'];
        $id_casa = $_POST['ambienteCasa'];
        
        if (empty($nome) || empty($id_casa)) throw new Exception("Preencha todos os campos.");

        // SEGURANÇA: Verifica se a casa escolhida é do usuário
        verificarDonoCasa($conn, $id_casa, $id_usuario);

        // --- EDITAR ---
        if ($acao === 'editar') {
            $id = $_POST['id_ambiente'];
            
            // SEGURANÇA: Verifica se o ambiente a ser editado é meu
            verificarDonoAmbiente($conn, $id, $id_usuario);

            $sql = "UPDATE ambiente SET nome_ambiente = ?, fk_casa_id_casa = ? WHERE id_ambiente = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sii", $nome, $id_casa, $id);
            if (!$stmt->execute()) throw new Exception(message: "Erro ao atualizar.");
            
            $_SESSION['sucesso'] = "Ambiente atualizado!";

        // --- CADASTRAR ---
        } else {
            $sql = "INSERT INTO ambiente (nome_ambiente, fk_casa_id_casa) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $nome, $id_casa);
            if (!$stmt->execute()) throw new Exception("Erro ao cadastrar.");
            
            $_SESSION['sucesso'] = "Ambiente criado!";
        }

        $stmt->close();
        header("Location: ../views/ambientes.php");
        exit;

    } catch (Exception $e) {
        $_SESSION['erros'] = [$e->getMessage()];
        if ($acao === 'editar' && isset($id)) {
            header("Location: ../views/gerencia_ambientes.php?id=" . $id);
        } else {
            header("Location: ../views/ambientes.php");
        }
        exit;
    }
}