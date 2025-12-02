<?php
// config/dados_ambientes.php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['error' => 'Usuário não logado']);
    exit;
}

require_once __DIR__ . '/../config/database.php';
$id_usuario = $_SESSION['id_usuario'];

try {
    // SOMA O CONSUMO AGRUPADO POR AMBIENTE
    // Usa as chaves: fk_ambi_histo (tabela historico) e fk_casa_id_casa (tabela ambiente)
    $sql = "
        SELECT 
            a.nome_ambiente,
            c.nome_casa,
            SUM(h.consumo) as total_gasto_wh
        FROM historico_consumo h
        JOIN ambiente a ON h.fk_ambi_histo = a.id_ambiente
        JOIN casa c ON a.fk_casa_id_casa = c.id_casa
        WHERE c.fk_usuario_id_usuario = ?
        GROUP BY a.id_ambiente, a.nome_ambiente, c.nome_casa
        ORDER BY total_gasto_wh DESC
    ";

    $stmt = $conn->prepare($sql);
    if(!$stmt) throw new Exception("Erro SQL: " . $conn->error);

    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    $labels = [];
    $data = [];

    while ($row = $result->fetch_assoc()) {
        // Ex: "Cozinha (Casa de Praia)"
        $labels[] = $row['nome_ambiente'] . " - " . $row['nome_casa'];
        
        // Converte para kWh para ficar legível
        $val_kwh = $row['total_gasto_wh'] / 1000;
        $data[] = round($val_kwh, 3);
    }

    echo json_encode(['labels' => $labels, 'data' => $data]);

} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>