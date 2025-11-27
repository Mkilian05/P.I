<?php
// config/processa_consumo.php

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
session_start();
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $id_usuario = $_SESSION['id_usuario'];
    
    $data_uso = $_POST['data_uso'] ?? '';
    $selecao  = $_POST['selecao_dispositivo'] ?? ''; 
    $tempo    = $_POST['tempo_uso'] ?? '';

    if (empty($data_uso) || empty($selecao) || empty($tempo)) {
        $_SESSION['erros'] = ["Preencha todos os campos."];
        header("Location: ../views/lancar_consumo.php");
        exit;
    }

    $partes = explode('|', $selecao);
    if(count($partes) !== 2) {
        $_SESSION['erros'] = ["Erro na seleção."];
        header("Location: ../views/lancar_consumo.php");
        exit;
    }
    list($id_disp, $id_amb) = $partes;

    try {
        // 1. BUSCA A POTÊNCIA E A QUANTIDADE
        // Adicionamos 'ad.quantidade' ao SELECT
        $sql_pot = "
            SELECT d.potencia_dispositivo, ad.quantidade 
            FROM dispositivos d
            JOIN ambiente_dispositivo ad ON d.id_dispositivo = ad.fk_disp_id_disp
            JOIN ambiente a ON ad.fk_ambi_id_ambi = a.id_ambiente
            JOIN casa k ON a.fk_casa_id_casa = k.id_casa
            WHERE d.id_dispositivo = ? AND k.fk_usuario_id_usuario = ?
        ";
        
        $stmt = $conn->prepare($sql_pot);
        $stmt->bind_param("ii", $id_disp, $id_usuario);
        $stmt->execute();
        $res = $stmt->get_result();
        
        if ($res->num_rows === 0) {
            throw new Exception("Dispositivo não encontrado.");
        }
        
        $row = $res->fetch_assoc();
        $potencia_watts = floatval($row['potencia_dispositivo']);
        // Recuperamos a quantidade cadastrada (ex: 5 lâmpadas)
        $quantidade_itens = intval($row['quantidade']); 

        // 2. CÁLCULO DE TEMPO (Horas Decimais)
        list($horas, $minutos) = explode(':', $tempo);
        $tempo_decimal = $horas + ($minutos / 60);
        
        // 3. CÁLCULO FINAL DE CONSUMO
        // Fórmula: (Potência * Quantidade de Itens) * Tempo de Uso
        $consumo_final = ($potencia_watts * $quantidade_itens) * $tempo_decimal;

        // 4. INSERIR NO BANCO 
        // Nota: Mantive os nomes das colunas conforme seu último envio (fk_ambi_histo, fk_disp_histo)
        $sql_insert = "
            INSERT INTO historico_consumo 
            (data, consumo, tempo_uso, fk_ambi_histo, fk_disp_histo) 
            VALUES (?, ?, ?, ?, ?)
        ";
        
        $stmt_ins = $conn->prepare($sql_insert);
        $stmt_ins->bind_param("sdsii", $data_uso, $consumo_final, $tempo, $id_amb, $id_disp);
        $stmt_ins->execute();

        $_SESSION['sucesso'] = "Consumo registrado! Total: " . number_format($consumo_final, 2) . " Wh (" . $quantidade_itens . " unidades)";
        header("Location: ../views/lancar_consumo.php");
        exit;

    } catch (Exception $e) {
        $_SESSION['erros'] = ["Erro: " . $e->getMessage()];
        header("Location: ../views/lancar_consumo.php");
        exit;
    }

} else {
    header("Location: ../views/dashboard.php");
    exit;
}
?>