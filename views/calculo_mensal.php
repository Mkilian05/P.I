<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';

$page = 'consultas';
$id_usuario = $_SESSION['id_usuario'];

// --- FILTROS ---
// Pega o mês/ano atual se não for informado
$mes_filtro = $_GET['mes'] ?? date('m');
$ano_filtro = $_GET['ano'] ?? date('Y');
// Preço do kWh (Padrão R$ 0.85 se não informado)
$preco_kwh  = isset($_GET['preco']) ? floatval(str_replace(',', '.', $_GET['preco'])) : 0.85;

// --- CONSULTA 1: TOTAIS GERAIS ---
// Soma tudo do mês selecionado
$sql_total = "
    SELECT SUM(h.consumo) as total_wh
    FROM historico_consumo h
    JOIN dispositivos d ON h.fk_disp_histo = d.id_dispositivo
    JOIN ambiente a ON h.fk_ambi_histo = a.id_ambiente
    JOIN casa c ON a.fk_casa_id_casa = c.id_casa
    WHERE c.fk_usuario_id_usuario = ?
    AND MONTH(h.data) = ? AND YEAR(h.data) = ?
";
$stmt = $conn->prepare($sql_total);
$stmt->bind_param("iii", $id_usuario, $mes_filtro, $ano_filtro);
$stmt->execute();
$res_total = $stmt->get_result()->fetch_assoc();

$total_wh = $res_total['total_wh'] ?? 0;
$total_kwh = $total_wh / 1000; // Converte para KWh
$valor_final = $total_kwh * $preco_kwh; // Calcula em Reais

// --- CONSULTA 2: DETALHAMENTO POR DISPOSITIVO ---
// Agrupa para saber o vilão da conta
$sql_detalhe = "
    SELECT 
        d.nome_dispositivo,
        c.nome_casa,
        SUM(h.consumo) as consumo_device
    FROM historico_consumo h
    JOIN dispositivos d ON h.fk_disp_histo = d.id_dispositivo
    JOIN ambiente a ON h.fk_ambi_histo = a.id_ambiente
    JOIN casa c ON a.fk_casa_id_casa = c.id_casa
    WHERE c.fk_usuario_id_usuario = ?
    AND MONTH(h.data) = ? AND YEAR(h.data) = ?
    GROUP BY d.id_dispositivo, d.nome_dispositivo, c.nome_casa
    ORDER BY consumo_device DESC
";
$stmt2 = $conn->prepare($sql_detalhe);
$stmt2->bind_param("iii", $id_usuario, $mes_filtro, $ano_filtro);
$stmt2->execute();
$detalhes = $stmt2->get_result();

// Array de meses para o Select
$meses = [
    1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril', 5 => 'Maio', 6 => 'Junho',
    7 => 'Julho', 8 => 'Agosto', 9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
];

include_once __DIR__ . '/../includes/header.php';
include_once __DIR__ . '/../includes/sidebar-nav.php';
?>

<main class="dashboard-content fade-in">
    
    <header class="top-header no-print">
        <div style="display:flex; align-items:center; gap: 15px;">
            <a href="dashboard.php" class="btn-outline" style="border:none; font-size:1.2rem; color:var(--roxo);">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
            <div>
                <h1>Simulador de Fatura</h1>
                <p>Calcule o gasto mensal estimado com base no seu histórico.</p>
            </div>
        </div>
    </header>

    <section class="card no-print" style="margin-bottom: 2rem; padding: 20px;">
        <form method="GET" style="display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap;">
            
            <div class="form-group" style="margin-bottom: 0; flex: 1; min-width: 150px;">
                <label>Mês</label>
                <select name="mes" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 8px;">
                    <?php foreach($meses as $num => $nome): ?>
                        <option value="<?php echo $num; ?>" <?php echo ($num == $mes_filtro) ? 'selected' : ''; ?>>
                            <?php echo $nome; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 0; flex: 1; min-width: 100px;">
                <label>Ano</label>
                <select name="ano" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 8px;">
                    <?php for($i = 2024; $i <= date('Y'); $i++): ?>
                        <option value="<?php echo $i; ?>" <?php echo ($i == $ano_filtro) ? 'selected' : ''; ?>>
                            <?php echo $i; ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 0; flex: 1; min-width: 150px;">
                <label>Tarifa (R$/kWh)</label>
                <input type="number" step="0.01" name="preco" value="<?php echo number_format($preco_kwh, 2); ?>" 
                       style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 8px;" placeholder="0.85">
            </div>

            <button type="submit" class="btn-primary">
                <i class="fas fa-calculator"></i> Calcular
            </button>
        </form>
    </section>

    <section class="cards-container" style="margin-bottom: 2rem;">
        
        <div class="card" style="border-left: 5px solid #2196F3; text-align: center;">
            <p style="color: #666; font-weight: 600; margin-bottom: 5px;">CONSUMO TOTAL</p>
            <h2 style="color: #2196F3; font-size: 2.5rem; margin: 0;">
                <?php echo number_format($total_kwh, 2, ',', '.'); ?> <span style="font-size: 1rem;">kWh</span>
            </h2>
        </div>

        <div class="card" style="border-left: 5px solid #4CAF50; text-align: center;">
            <p style="color: #666; font-weight: 600; margin-bottom: 5px;">VALOR ESTIMADO</p>
            <h2 style="color: #4CAF50; font-size: 2.5rem; margin: 0;">
                R$ <?php echo number_format($valor_final, 2, ',', '.'); ?>
            </h2>
        </div>

    </section>

    <section class="card" style="padding: 20px;">
        <h3 style="color: var(--roxo); margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px;">
            Detalhamento da Fatura
        </h3>

        <?php if ($detalhes->num_rows > 0): ?>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f9f9f9; text-align: left;">
                        <th style="padding: 10px;">Dispositivo</th>
                        <th style="padding: 10px;">Local</th>
                        <th style="padding: 10px;">Consumo (kWh)</th>
                        <th style="padding: 10px;">Custo (R$)</th>
                        <th style="padding: 10px;">% da Conta</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $detalhes->fetch_assoc()): 
                        $kwh_item = $row['consumo_device'] / 1000;
                        $custo_item = $kwh_item * $preco_kwh;
                        $porcentagem = ($total_kwh > 0) ? ($kwh_item / $total_kwh) * 100 : 0;
                    ?>
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 10px; font-weight: 600;"><?php echo htmlspecialchars($row['nome_dispositivo']); ?></td>
                            <td style="padding: 10px; color: #666;"><?php echo htmlspecialchars($row['nome_casa']); ?></td>
                            <td style="padding: 10px;"><?php echo number_format($kwh_item, 2, ',', '.'); ?> kWh</td>
                            <td style="padding: 10px; color: #4CAF50; font-weight: bold;">R$ <?php echo number_format($custo_item, 2, ',', '.'); ?></td>
                            <td style="padding: 10px;">
                                <div style="background: #eee; width: 100px; height: 8px; border-radius: 4px; overflow: hidden;">
                                    <div style="background: var(--roxo); width: <?php echo $porcentagem; ?>%; height: 100%;"></div>
                                </div>
                                <small style="color: #888;"><?php echo number_format($porcentagem, 1); ?>%</small>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div style="text-align: center; padding: 40px; color: #888;">
                <i class="fas fa-file-invoice-dollar" style="font-size: 3rem; margin-bottom: 15px; opacity: 0.5;"></i>
                <p>Nenhum consumo registrado neste mês.</p>
                <a href="lancar_consumo.php" class="btn-primary" style="margin-top: 10px; display: inline-block;">Registrar Agora</a>
            </div>
        <?php endif; ?>
    </section>

</main>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>