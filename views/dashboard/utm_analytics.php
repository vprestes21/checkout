<?php
$title = 'Análise de UTMs - CheckoutPro';
ob_start();

// Sample UTM data (replace with actual data from your database)
$utmData = [
    'sources' => [
        ['label' => 'Google', 'visits' => 423, 'conversions' => 87, 'revenue' => 8745.00],
        ['label' => 'Facebook', 'visits' => 318, 'conversions' => 52, 'revenue' => 5180.00],
        ['label' => 'Instagram', 'visits' => 265, 'conversions' => 43, 'revenue' => 4300.00],
        ['label' => 'Direct', 'visits' => 189, 'conversions' => 35, 'revenue' => 3150.00],
        ['label' => 'Email', 'visits' => 156, 'conversions' => 29, 'revenue' => 2700.00],
    ],
    'campaigns' => [
        ['label' => 'Summer Sale', 'visits' => 286, 'conversions' => 58, 'revenue' => 5800.00],
        ['label' => 'Black Friday', 'visits' => 241, 'conversions' => 47, 'revenue' => 4700.00],
        ['label' => 'Launch Promo', 'visits' => 197, 'conversions' => 37, 'revenue' => 3550.00],
    ],
    'mediums' => [
        ['label' => 'cpc', 'visits' => 386, 'conversions' => 76, 'revenue' => 7600.00],
        ['label' => 'social', 'visits' => 312, 'conversions' => 58, 'revenue' => 5800.00],
        ['label' => 'email', 'visits' => 156, 'conversions' => 29, 'revenue' => 2900.00],
        ['label' => 'organic', 'visits' => 135, 'conversions' => 24, 'revenue' => 2400.00],
    ],
    'contents' => [
        ['label' => 'banner_1', 'visits' => 245, 'conversions' => 48, 'revenue' => 4800.00],
        ['label' => 'banner_2', 'visits' => 187, 'conversions' => 35, 'revenue' => 3500.00],
        ['label' => 'popup', 'visits' => 156, 'conversions' => 28, 'revenue' => 2800.00],
    ],
    'daily' => [
        ['date' => '01/06', 'visits' => 45, 'conversions' => 8],
        ['date' => '02/06', 'visits' => 52, 'conversions' => 11],
        ['date' => '03/06', 'visits' => 49, 'conversions' => 9],
        ['date' => '04/06', 'visits' => 63, 'conversions' => 15],
        ['date' => '05/06', 'visits' => 58, 'conversions' => 12],
        ['date' => '06/06', 'visits' => 47, 'conversions' => 8],
        ['date' => '07/06', 'visits' => 51, 'conversions' => 10],
    ],
    'detailed' => [
        ['date' => '2023-06-07', 'product' => 'Curso de Marketing', 'source' => 'google', 'medium' => 'cpc', 'campaign' => 'summer_sale', 'term' => 'marketing digital', 'content' => 'banner_1', 'converted' => true, 'value' => 197.00],
        ['date' => '2023-06-06', 'product' => 'Ebook Premium', 'source' => 'facebook', 'medium' => 'social', 'campaign' => 'launch_promo', 'term' => 'ebook digital', 'content' => 'banner_2', 'converted' => true, 'value' => 47.00],
        ['date' => '2023-06-06', 'product' => 'Curso de Marketing', 'source' => 'instagram', 'medium' => 'social', 'campaign' => 'black_friday', 'term' => 'curso online', 'content' => 'popup', 'converted' => false, 'value' => 0],
        ['date' => '2023-06-05', 'product' => 'Ebook Premium', 'source' => 'email', 'medium' => 'email', 'campaign' => 'newsletter', 'term' => '', 'content' => 'email_1', 'converted' => true, 'value' => 47.00],
        ['date' => '2023-06-05', 'product' => 'Assinatura Mensal', 'source' => 'google', 'medium' => 'organic', 'campaign' => '', 'term' => 'assinatura digital', 'content' => '', 'converted' => false, 'value' => 0],
    ]
];

// Calculate totals
$totalVisits = array_sum(array_column($utmData['sources'], 'visits'));
$totalConversions = array_sum(array_column($utmData['sources'], 'conversions'));
$totalRevenue = array_sum(array_column($utmData['sources'], 'revenue'));
$conversionRate = $totalVisits > 0 ? ($totalConversions / $totalVisits) : 0;
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Análise de Campanhas (UTM)</h1>
        <div class="btn-group">
            <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                Período: Últimos 30 dias
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="#">Últimos 7 dias</a></li>
                <li><a class="dropdown-item" href="#">Últimos 30 dias</a></li>
                <li><a class="dropdown-item" href="#">Últimos 90 dias</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="#">Todo o período</a></li>
            </ul>
        </div>
    </div>

    <!-- UTM Overview Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Visitas</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($totalVisits) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-people fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Conversões</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($totalConversions) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-cart-check fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Taxa de Conversão</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($conversionRate * 100, 1) ?>%</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-graph-up fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Receita Total</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">R$ <?= number_format($totalRevenue, 2, ',', '.') ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-currency-dollar fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- UTM Charts -->
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Conversões por Fonte (UTM Source)</h6>
                </div>
                <div class="card-body">
                    <canvas id="utmSourceChart" height="400"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Distribuição de Visitas</h6>
                </div>
                <div class="card-body">
                    <canvas id="utmSourcePieChart" height="400"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Desempenho de Campanhas</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Campanha</th>
                                    <th>Visitas</th>
                                    <th>Conversões</th>
                                    <th>Taxa</th>
                                    <th>Receita</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($utmData['campaigns'] as $campaign): ?>
                                <tr>
                                    <td><?= htmlspecialchars($campaign['label']) ?></td>
                                    <td><?= number_format($campaign['visits']) ?></td>
                                    <td><?= number_format($campaign['conversions']) ?></td>
                                    <td><?= number_format(($campaign['conversions'] / $campaign['visits']) * 100, 1) ?>%</td>
                                    <td>R$ <?= number_format($campaign['revenue'], 2, ',', '.') ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Desempenho por Meio (UTM Medium)</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Meio</th>
                                    <th>Visitas</th>
                                    <th>Conversões</th>
                                    <th>Taxa</th>
                                    <th>Receita</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($utmData['mediums'] as $medium): ?>
                                <tr>
                                    <td><?= htmlspecialchars($medium['label']) ?></td>
                                    <td><?= number_format($medium['visits']) ?></td>
                                    <td><?= number_format($medium['conversions']) ?></td>
                                    <td><?= number_format(($medium['conversions'] / $medium['visits']) * 100, 1) ?>%</td>
                                    <td>R$ <?= number_format($medium['revenue'], 2, ',', '.') ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Dados Detalhados de UTM</h6>
            <button class="btn btn-sm btn-outline-primary" id="exportUtmCsv">
                <i class="bi bi-download"></i> Exportar CSV
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="utmDataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Produto</th>
                            <th>UTM Source</th>
                            <th>UTM Medium</th>
                            <th>UTM Campaign</th>
                            <th>UTM Term</th>
                            <th>UTM Content</th>
                            <th>Status</th>
                            <th>Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($utmData['detailed'] as $entry): ?>
                        <tr>
                            <td><?= htmlspecialchars($entry['date']) ?></td>
                            <td><?= htmlspecialchars($entry['product']) ?></td>
                            <td><?= htmlspecialchars($entry['source']) ?></td>
                            <td><?= htmlspecialchars($entry['medium']) ?></td>
                            <td><?= htmlspecialchars($entry['campaign']) ?></td>
                            <td><?= htmlspecialchars($entry['term'] ?: '-') ?></td>
                            <td><?= htmlspecialchars($entry['content'] ?: '-') ?></td>
                            <td>
                                <?php if ($entry['converted']): ?>
                                <span class="badge bg-success">Convertido</span>
                                <?php else: ?>
                                <span class="badge bg-secondary">Visitante</span>
                                <?php endif; ?>
                            </td>
                            <td>R$ <?= number_format($entry['value'], 2, ',', '.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // UTM Source Bar Chart
    const utmCtx = document.getElementById('utmSourceChart').getContext('2d');
    new Chart(utmCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_column($utmData['sources'], 'label')) ?>,
            datasets: [{
                    label: 'Visitas',
                    data: <?= json_encode(array_column($utmData['sources'], 'visits')) ?>,
                    backgroundColor: 'rgba(78, 115, 223, 0.5)',
                    borderColor: 'rgba(78, 115, 223, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Conversões',
                    data: <?= json_encode(array_column($utmData['sources'], 'conversions')) ?>,
                    backgroundColor: 'rgba(28, 200, 138, 0.5)',
                    borderColor: 'rgba(28, 200, 138, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    
    // UTM Source Pie Chart
    const pieCtx = document.getElementById('utmSourcePieChart').getContext('2d');
    new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: <?= json_encode(array_column($utmData['sources'], 'label')) ?>,
            datasets: [{
                data: <?= json_encode(array_column($utmData['sources'], 'visits')) ?>,
                backgroundColor: [
                    '#4e73df',
                    '#1cc88a',
                    '#36b9cc',
                    '#f6c23e',
                    '#e74a3b'
                ],
                hoverBackgroundColor: [
                    '#2e59d9',
                    '#17a673',
                    '#2c9faf',
                    '#dda20a',
                    '#be2617'
                ],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const total = context.dataset.data.reduce((acc, val) => acc + val, 0);
                            const percentage = Math.round((value / total) * 100);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
    
    // Export to CSV functionality
    document.getElementById('exportUtmCsv').addEventListener('click', function() {
        // Get table data
        const table = document.getElementById('utmDataTable');
        let csvContent = "data:text/csv;charset=utf-8,";
        
        // Add headers
        const headers = [];
        const headerCells = table.querySelectorAll('thead th');
        headerCells.forEach(cell => {
            headers.push('"' + cell.innerText + '"');
        });
        csvContent += headers.join(',') + "\r\n";
        
        // Add rows
        const rows = table.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const rowData = [];
            const cells = row.querySelectorAll('td');
            cells.forEach(cell => {
                rowData.push('"' + cell.innerText.replace(/"/g, '""') + '"');
            });
            csvContent += rowData.join(',') + "\r\n";
        });
        
        // Create download link
        const encodedUri = encodeURI(csvContent);
        const link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "utm_data_" + new Date().toISOString().slice(0, 10) + ".csv");
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });
</script>

<?php
$content = ob_get_clean();
include 'views/layouts/dashboard.php';
?>
