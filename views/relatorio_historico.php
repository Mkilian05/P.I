<?php
// 1. SEGURANÇA E CONEXÃO
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';

$page = 'consultas'; // Mantém menu ativo
$id_usuario = $_SESSION['id_usuario'];

// 2. DEFINIR DATAS PADRÃO (Início do mês até hoje)
$data_inicio = $_GET['data_inicio'] ?? date('Y-m-01');
$data_fim    = $_GET['data_fim']    ?? date('Y-m-d');

// 3. CONSULTA SQL PODEROSA (JOINs + Filtro de Data + Segurança)
// Usamos as chaves que definimos: fk_disp_histo, fk_ambi_histo, fk_casa_id_casa
$sql = "
    SELECT 
        h.data, 
        h.tempo_uso, 
        h.consumo,
        d.nome_dispositivo,
        a.nome_ambiente,
        c.nome_casa
    FROM historico_consumo h
    JOIN dispositivos d ON h.fk_disp_histo = d.id_dispositivo
    JOIN ambiente a ON h.fk_ambi_histo = a.id_ambiente
    JOIN casa c ON a.fk_casa_id_casa = c.id_casa
    WHERE c.fk_usuario_id_usuario = ? 
    AND h.data BETWEEN ? AND ?
    ORDER BY h.data DESC, c.nome_casa ASC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iss", $id_usuario, $data_inicio, $data_fim);
$stmt->execute();
$resultado = $stmt->get_result();

// Somar totais para o rodapé do relatório
$total_consumo = 0;

include_once __DIR__ . '/../includes/header.php';
include_once __DIR__ . '/../includes/sidebar-nav.php';
?>

<style>
    .tabela-relatorio {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    .tabela-relatorio th, .tabela-relatorio td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
    .tabela-relatorio th {
        background-color: var(--roxo);
        color: white;
    }
    .tabela-relatorio tr:hover {
        background-color: #f5f5f5;
    }

    /* MÁGICA DO PDF: Só aparece na hora de imprimir */
    @media print {
        body * {
            visibility: hidden; /* Esconde tudo */
        }
        .printable-area, .printable-area * {
            visibility: visible; /* Mostra só o relatório */
        }
        .printable-area {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .no-print {
            display: none !important; /* Esconde botões e filtros no PDF */
        }
        .sidebar, header, footer {
            display: none !important;
        }
        .card {
            box-shadow: none !important;
            border: none !important;
        }
    }
</style>

<main class="dashboard-content fade-in">
    
    <header class="top-header no-print">
        <div style="display:flex; align-items:center; gap: 15px;">
            <a href="dashboard.php" class="btn-outline" style="border:none; font-size:1.2rem; color:var(--roxo);">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
            <div>
                <h1>Histórico de Consumo</h1>
                <p>Filtre por período e gere relatórios detalhados.</p>
            </div>
        </div>
    </header>

    <section class="card no-print" style="margin-bottom: 20px; padding: 20px;">
        <form method="GET" action="relatorio_historico.php" style="display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap;">
            
            <div class="form-group" style="margin-bottom: 0;">
                <label>Data Início</label>
                <input type="date" name="data_inicio" value="<?php echo $data_inicio; ?>" required style="padding: 8px; border: 1px solid #ddd; border-radius: 8px;">
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label>Data Fim</label>
                <input type="date" name="data_fim" value="<?php echo $data_fim; ?>" required style="padding: 8px; border: 1px solid #ddd; border-radius: 8px;">
            </div>

            <button type="submit" class="btn-primary">
                <i class="fas fa-filter"></i> Filtrar
            </button>

            <button type="button" onclick="window.print()" class="btn-outline" style="margin-left: auto;">
                <i class="fas fa-file-pdf"></i> Gerar PDF / Imprimir
            </button>
        </form>
    </section>

    <section class="card printable-area" style="padding: 30px;">
        
        <div style="text-align: center; margin-bottom: 30px;">
            <h2 style="color: var(--roxo); margin-bottom: 5px;">Relatório de Consumo Energético</h2>
            <p style="color: #666;">Período: <?php echo date('d/m/Y', strtotime($data_inicio)); ?> até <?php echo date('d/m/Y', strtotime($data_fim)); ?></p>
            <p style="font-size: 0.9em;">Gerado em: <?php echo date('d/m/Y H:i'); ?></p>
        </div>

        <?php if ($resultado->num_rows > 0): ?>
            <table class="tabela-relatorio">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Casa</th>
                        <th>Ambiente</th>
                        <th>Dispositivo</th>
                        <th>Tempo Uso</th>
                        <th>Consumo (Wh)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $resultado->fetch_assoc()): ?>
                        <?php $total_consumo += $row['consumo']; ?>
                        <tr>
                            <td><?php echo date('d/m/Y', strtotime($row['data'])); ?></td>
                            <td><?php echo htmlspecialchars($row['nome_casa']); ?></td>
                            <td><?php echo htmlspecialchars($row['nome_ambiente']); ?></td>
                            <td style="font-weight: 600;"><?php echo htmlspecialchars($row['nome_dispositivo']); ?></td>
                            <td><?php echo htmlspecialchars($row['tempo_uso']); ?></td>
                            <td><?php echo number_format($row['consumo'], 2, ',', '.'); ?> Wh</td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
                <tfoot>
                    <tr style="background-color: #f9f9f9; font-weight: bold;">
                        <td colspan="5" style="text-align: right; padding-right: 20px;">TOTAL NO PERÍODO:</td>
                        <td style="color: var(--roxo); font-size: 1.1em;">
                            <?php echo number_format($total_consumo, 2, ',', '.'); ?> Wh
                            <br>
                            <small style="color: #666; font-weight: normal;">( aprox. <?php echo number_format($total_consumo/1000, 2, ',', '.'); ?> kWh )</small>
                        </td>
                    </tr>
                </tfoot>
            </table>
        <?php else: ?>
            <div style="text-align: center; padding: 40px; color: #888;">
                <i class="fas fa-search" style="font-size: 2rem; margin-bottom: 10px;"></i>
                <p>Nenhum registro encontrado para este período.</p>
            </div>
        <?php endif; ?>

        <div style="margin-top: 50px; border-top: 1px solid #eee; padding-top: 10px; text-align: center; font-size: 0.8em; color: #999;">
            Watt's Up! - Sistema de Monitoramento Inteligente
        </div>

    </section>

</main>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>