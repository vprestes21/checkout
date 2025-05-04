<?php
$title = 'UTM Analytics - CheckoutPro';
ob_start();
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-light">Analytics de UTM</h1>
        <div class="d-flex gap-2">
            <div class="dropdown">
                <button class="btn btn-outline-light dropdown-toggle" type="button" id="periodDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <?= $period ?? 'Últimos 30 dias' ?>
                </button>
                <ul class="dropdown-menu" aria-labelledby="periodDropdown">
                    <li><a class="dropdown-item" href="?period=7">Últimos 7 dias</a></li>
                    <li><a class="dropdown-item" href="?period=30">Últimos 30 dias</a></li>
                    <li><a class="dropdown-item" href="?period=90">Últimos 90 dias</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="?period=all">Todo o período</a></li>
                </ul>
            </div>
            
            <div class="dropdown">
                <button class="btn btn-outline-light dropdown-toggle" type="button" id="productDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <?= $selectedProduct ?? 'Todos os produtos' ?>
                </button>
                <ul class="dropdown-menu" aria-labelledby="productDropdown">
                    <li><a class="dropdown-item" href="?product=all">Todos os produtos</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <?php foreach ($products as $product): ?>
                    <li><a class="dropdown-item" href="?product=<?= $product['id'] ?>"><?= htmlspecialchars($product['title']) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    
    <!-- Analytics Summary Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-card-icon">
                    <i class="fas fa-eye"></i>
                </div>
                <p class="text-muted mb-1">Total de Visitas</p>
                <h3 class="text-light"><?= number_format($stats['total_visits']) ?></h3>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-card-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <p class="text-muted mb-1">Conversões</p>
                <h3 class="text-light"><?= number_format($stats['total_conversions']) ?></h3>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-card-icon">
                    <i class="fas fa-percentage"></i>
                </div>
                <p class="text-muted mb-1">Taxa de Conversão</p>
                <h3 class="text-light"><?= number_format($stats['conversion_rate'] * 100, 1) ?>%</h3>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-card-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <p class="text-muted mb-1">Receita Total</p>
                <h3 class="text-light">R$ <?= number_format($stats['total_revenue'], 2, ',', '.') ?></h3>
            </div>
        </div>
    </div>
    
    <div class="row g-4">
        <!-- UTM Sources Chart -->
        <div class="col-md-6 col-lg-8">
            <div class="dashboard-card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 text-light">Fontes (UTM Source)</h5>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-secondary active" data-chart-view="visits">Visitas</button>
                        <button type="button" class="btn btn-outline-secondary" data-chart-view="conversions">Conversões</button>
                        <button type="button" class="btn btn-outline-secondary" data-chart-view="revenue">Receita</button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="utmSourceChart" height="300"></canvas>
                </div>
            </div>
        </div>
        
        <!-- UTM Campaigns -->
        <div class="col-md-6 col-lg-4">
            <div class="dashboard-card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0 text-light">Campanhas (UTM Campaign)</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Campanha</th>
                                    <th>Visitas</th>
                                    <th>Conv.</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($campaigns as $campaign): ?>
                                <tr>
                                    <td class="text-nowrap text-light"><?= htmlspecialchars($campaign['utm_campaign'] ?: '(no campaign)') ?></td>
                                    <td><?= number_format($campaign['visits']) ?></td>
                                    <td><?= number_format($campaign['conversions']) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <!-- Detailed UTM Data -->
        <div class="col-12">
            <div class="dashboard-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 text-light">Análise Detalhada de UTM</h5>
                    <button class="btn btn-sm btn-outline-light" id="exportUtmData">
                        <i class="fas fa-download me-1"></i> Exportar CSV
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="utmDetailsTable">
                            <thead>
                                <tr>
                                    <th>Fonte (Source)</th>
                                    <th>Meio (Medium)</th>
                                    <th>Campanha</th>
                                    <th>Visitas</th>
                                    <th>Conversões</th>
                                    <th>Taxa Conv.</th>
                                    <th>Receita</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($utmData as $utm): ?>
                                <tr>
                                    <td class="text-light"><?= htmlspecialchars($utm['utm_source'] ?: '(direct)') ?></td>
                                    <td><?= htmlspecialchars($utm['utm_medium'] ?: '-') ?></td>
                                    <td><?= htmlspecialchars($utm['utm_campaign'] ?: '-') ?></td>
                                    <td><?= number_format($utm['visits']) ?></td>
                                    <td><?= number_format($utm['conversions']) ?></td>
                                    <td><?= number_format(($utm['conversions'] / max(1, $utm['visits'])) * 100, 1) ?>%</td>
                                    <td class="text-nowrap">R$ <?= number_format($utm['revenue'], 2, ',', '.') ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // UTM Source Chart
    const utmCtx = document.getElementById('utmSourceChart').getContext('2d');
    
    // Prepare the datasets
    const utmLabels = <?= json_encode(array_column($sourceData, 'utm_source')) ?>;
    const visitsData = <?= json_encode(array_column($sourceData, 'visits')) ?>;
    const conversionsData = <?= json_encode(array_column($sourceData, 'conversions')) ?>;
    const revenueData = <?= json_encode(array_column($sourceData, 'revenue')) ?>;
    
    const utmSourceChart = new Chart(utmCtx, {
        type: 'bar',
        data: {
            labels: utmLabels,
            datasets: [{
                label: 'Visitas',
                data: visitsData,
                backgroundColor: 'rgba(78, 115, 223, 0.6)',
                borderColor: 'rgba(78, 115, 223, 1)',
                borderWidth: 1
            }]
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    
    // Handle chart view switching
    document.querySelectorAll('[data-chart-view]').forEach(button => {
        button.addEventListener('click', function() {
            // Update active button
            document.querySelectorAll('[data-chart-view]').forEach(btn => {
                btn.classList.remove('active');
            });
            this.classList.add('active');
            
            // Update chart data
            const view = this.getAttribute('data-chart-view');
            
            switch(view) {
                case 'visits':
                    utmSourceChart.data.datasets[0].label = 'Visitas';
                    utmSourceChart.data.datasets[0].data = visitsData;
                    break;
                case 'conversions':
                    utmSourceChart.data.datasets[0].label = 'Conversões';
                    utmSourceChart.data.datasets[0].data = conversionsData;
                    break;
                case 'revenue':
                    utmSourceChart.data.datasets[0].label = 'Receita (R$)';
                    utmSourceChart.data.datasets[0].data = revenueData;
                    break;
            }
            
            utmSourceChart.update();
        });
    });
    
    // CSV Export
    document.getElementById('exportUtmData').addEventListener('click', function() {
        // Get table data
        const table = document.getElementById('utmDetailsTable');
        let csvContent = "data:text/csv;charset=utf-8,";
        
        // Get headers
        const headers = [];
        table.querySelectorAll('thead th').forEach(th => {
            headers.push(th.innerText);
        });
        csvContent += headers.join(',') + '\r\n';
        
        // Get rows
        table.querySelectorAll('tbody tr').forEach(row => {
            const rowData = [];
            row.querySelectorAll('td').forEach(cell => {
                rowData.push('"' + cell.innerText.replace(/"/g, '""') + '"');
            });
            csvContent += rowData.join(',') + '\r\n';
        });
        
        // Download CSV
        const encodedUri = encodeURI(csvContent);
        const link = document.createElement('a');
        link.setAttribute('href', encodedUri);
        link.setAttribute('download', `utm_data_${new Date().toISOString().slice(0,10)}.csv`);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });
</script>

<?php
$content = ob_get_clean();
include 'views/layouts/dashboard.php';
?>
