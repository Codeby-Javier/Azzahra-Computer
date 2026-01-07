<!-- Existing template header -->
<?php $this->load->view('Template/header'); ?>

 <div class="page-header">
            <div class="page-header-left">
                <div class="page-title-section">
                    <h1 class="page-title"><i data-feather="home" class="w-10 h-10 inline-block mr-2"></i>Dashboard Overview</h1>
                    <p class="page-subtitle">
                        <i data-feather="calendar"></i>
                        <?= date('l, d F Y'); ?>
                    </p>
                </div>
            </div>
            <div class="page-header-right">
                <div class="header-actions">
                    <div class="export-buttons">
                        <button class="btn btn-outline export-btn" onclick="exportDashboardReport('all')" title="Export All Sections">
                            <i data-feather="download"></i>
                            📊 All
                        </button>
                        <button class="btn btn-outline export-btn" onclick="exportDashboardReport('performance')" title="Export Performance Metrics">
                            <i data-feather="trending-up"></i>
                            📈 Performance
                        </button>
                        <button class="btn btn-outline export-btn" onclick="exportDashboardReport('weekly')" title="Export Weekly Performance">
                            <i data-feather="bar-chart"></i>
                            📊 Weekly
                        </button>
                        <button class="btn btn-outline export-btn" onclick="exportDashboardReport('technicians')" title="Export Top Performers">
                            <i data-feather="users"></i>
                            👷 Technicians
                        </button>
                        <button class="btn btn-outline export-btn" onclick="exportDashboardReport('payment')" title="Export Payment Methods">
                            <i data-feather="credit-card"></i>
                            💳 Payment
                        </button>
                        <button class="btn btn-outline export-btn" onclick="exportDashboardReport('activity')" title="Export Recent Activity">
                            <i data-feather="activity"></i>
                            📋 Activity
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
<!-- Content Area (dari template sidebar Anda) -->
<div class="content-area">        
    <!-- Dashboard Container -->
    <div class="dashboard-container">        
        <!-- Page Header -->
       
        <!-- Quick Stats (KPI Cards) -->
        <div class="stats-grid">
            <!-- Konfirmasi Pending -->
            <div class="stat-card purple">
                <div class="stat-card-header">
                    <div class="stat-icon-wrapper">
                        <i data-feather="bell"></i>
                    </div>
                    <div class="stat-status">Urgent</div>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Konfirmasi Pending</div>
                    <div class="stat-value"><?= $konf->num_rows(); ?></div>
                    <div class="stat-description">Menunggu konfirmasi customer</div>
                </div>
                <div class="stat-footer">
                    <div class="stat-change positive">
                        <i data-feather="trending-up" style="width: 14px; height: 14px;"></i>
                        <span>Active</span>
                    </div>
                    <a href="<?= site_url('Admin/cus_konf')?>" class="stat-link">
                        View Details
                        <i data-feather="arrow-right" style="width: 14px; height: 14px;"></i>
                    </a>
                </div>
            </div>

            <!-- Revenue Today -->
            <div class="stat-card orange">
                <div class="stat-card-header">
                    <div class="stat-icon-wrapper">
                        <i data-feather="dollar-sign"></i>
                    </div>
                    <div class="stat-status">Today</div>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Revenue Today</div>
                    <div class="stat-value">Rp <?= number_format($revenue_today, 0, ',', '.'); ?></div>
                    <div class="stat-description">Total pendapatan hari ini</div>
                </div>
                <div class="stat-footer">
                    <div class="stat-change positive">
                        <i data-feather="trending-up" style="width: 14px; height: 14px;"></i>
                        <span>Revenue</span>
                    </div>
                    <a href="<?= site_url('Admin/laporan')?>" class="stat-link">
                        View Report
                        <i data-feather="arrow-right" style="width: 14px; height: 14px;"></i>
                    </a>
                </div>
            </div>

            <!-- Transfer BCA -->
            <div class="stat-card blue">
                <div class="stat-card-header">
                    <div class="stat-icon-wrapper">
                        <i data-feather="credit-card"></i>
                    </div>
                    <div class="stat-status">BCA</div>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Transfer Bank BCA</div>
                    <div class="stat-value"><?= $bca->num_rows(); ?></div>
                    <div class="stat-description">Total transaksi via BCA</div>
                </div>
                <div class="stat-footer">
                    <div class="stat-change positive">
                        <i data-feather="trending-up" style="width: 14px; height: 14px;"></i>
                        <span>Active</span>
                    </div>
                    <a href="<?= site_url('Admin/cus_konf_bank')?>" class="stat-link">
                        View Details
                        <i data-feather="arrow-right" style="width: 14px; height: 14px;"></i>
                    </a>
                </div>
            </div>

            <!-- Transfer BRI -->
            <div class="stat-card green">
                <div class="stat-card-header">
                    <div class="stat-icon-wrapper">
                        <i data-feather="credit-card"></i>
                    </div>
                    <div class="stat-status">BRI</div>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Transfer Bank BRI</div>
                    <div class="stat-value"><?= $bri->num_rows(); ?></div>
                    <div class="stat-description">Total transaksi via BRI</div>
                </div>
                <div class="stat-footer">
                    <div class="stat-change positive">
                        <i data-feather="trending-up" style="width: 14px; height: 14px;"></i>
                        <span>Active</span>
                    </div>
                    <a href="<?= site_url('Admin/cus_konf_bank')?>" class="stat-link">
                        View Details
                        <i data-feather="arrow-right" style="width: 14px; height: 14px;"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="charts-grid">
            <!-- Weekly Revenue Chart -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">Weekly Performance</h3>
                    <div class="chart-filters">
                        <select id="metricSelector" class="metric-select">
                             <option value="revenue">Revenue (Rp)</option>
                             <option value="transactions">Transactions</option>
                             <option value="customers">New Customers</option>
                             <option value="technician_productivity">Jumlah Service</option>
                         </select>
                         <button class="weekly-btn active" data-period="7D">7D</button>
                         <button class="weekly-btn" data-period="1M">1M</button>
                         <button class="weekly-btn" data-period="3M">3M</button>
                         <button class="weekly-btn" data-period="1Y">1Y</button>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="weeklyChart" width="400" height="300"></canvas>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="activity-card">
                <div class="chart-header">
                    <h3 class="chart-title">Recent Activity</h3>
                </div>
                <div class="activity-list">
                    <div class="activity-item">
                        <div class="activity-icon info">
                            <i data-feather="user-plus"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Total Customers</div>
                            <div class="activity-description"><?= $total_customers; ?> customers terdaftar di sistem</div>
                        </div>
                        <div class="activity-time">All Time</div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon warning">
                            <i data-feather="alert-circle"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Konfirmasi Pending</div>
                            <div class="activity-description"><?= $konf->num_rows(); ?> transaksi menunggu konfirmasi</div>
                        </div>
                        <div class="activity-time">Now</div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon success">
                            <i data-feather="dollar-sign"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Total Tunai</div>
                            <div class="activity-description">Rp <?= number_format($total_tunai, 0, ',', '.'); ?> pembayaran tunai</div>
                        </div>
                        <div class="activity-time">All Time</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Teknisi Productivity Details -->
        <div class="section-header">
            <h2 class="section-title">Top Performers Teknisi</h2>
            <div class="chart-filters">
                <button class="tech-btn active" data-period="7D">7D</button>
                <button class="tech-btn" data-period="1M">1M</button>
                <button class="tech-btn" data-period="1Y">1Y</button>
                <button class="tech-btn" data-period="all">All</button>
            </div>
        </div>

        <div class="technician-productivity-card">
            <div class="technician-chart-container">
                <canvas id="technicianChart" width="400" height="300"></canvas>
            </div>
            <div class="technician-list">
                <h4>Top Performers</h4>
                <div class="technician-items">
                    <?php
                    if (!empty($technician_details)) {
                        // Group technician data by name and sum services_completed (same as chart logic)
                        $techMap = [];
                        foreach ($technician_details as $tech) {
                            $name = $tech['technician_name'] ?? 'Teknisi Tidak Diketahui';
                            if (!isset($techMap[$name])) {
                                $techMap[$name] = 0;
                            }
                            $techMap[$name] += $tech['services_completed'] ?? 0;
                        }
                        arsort($techMap); // Sort by services completed descending

                        // Get top 5 performers
                        $topTechnicians = array_slice($techMap, 0, 5, true);
                        $rank = 1;
                        foreach ($topTechnicians as $techName => $servicesCompleted) {
                            echo '<div class="technician-item">';
                            echo '<div class="technician-rank">' . $rank . '</div>';
                            echo '<div class="technician-name">' . htmlspecialchars($techName) . '</div>';
                            echo '<div class="technician-services">' . $servicesCompleted . ' completed</div>';
                            echo '</div>';
                            $rank++;
                        }
                    } else {
                        echo '<div class="technician-item">No technician data available</div>';
                    }
                    ?>
                </div>
            </div>
        </div>

        <!-- Performance Metrics -->
        <div class="section-header">
            <h2 class="section-title">Performance Metrics</h2>
        </div>

        <div class="metrics-grid">
            <?php $total_amount = $total_bca + $total_mandiri + $total_bri + $total_tunai + $total_voucher; ?>
            <div class="metric-card">
                <div class="metric-icon" style="background: rgba(99, 102, 241, 0.1); color: var(--primary);">
                    <i data-feather="trending-up"></i>
                </div>
                <div class="metric-label">Total Konfirmasi</div>
                <div class="metric-value"><?= $konf->num_rows(); ?></div>
            </div>

            <div class="metric-card">
                <div class="metric-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--success);">
                    <i data-feather="users"></i>
                </div>
                <div class="metric-label">Total Customers</div>
                <div class="metric-value"><?= $total_customers; ?></div>
            </div>

            <div class="metric-card">
                <div class="metric-icon" style="background: rgba(245, 158, 11, 0.1); color: var(--warning);">
                    <i data-feather="check-circle"></i>
                </div>
                <div class="metric-label">Pembayaran Lunas</div>
                <div class="metric-value">
                    <?= $service_completion_rate; ?>
                </div>
            </div>

            <div class="metric-card">
                <div class="metric-icon" style="background: rgba(139, 92, 246, 0.1); color: var(--secondary);">
                    <i data-feather="credit-card"></i>
                </div>
                <div class="metric-label">Pembayaran DP</div>
                <div class="metric-value">
                    <?= $dp_pending; ?>
                </div>
            </div>
        </div>

        <!-- Alerts Section -->
        <div class="section-header">
            <h2 class="section-title">Alerts & Notifications</h2>
        </div>

        <div class="alerts-card">
            <?php
            $urgent_confirmations = $konf->num_rows();
            $high_pending = ($total_bca + $total_mandiri + $total_bri) > 5000000; // Over 5M pending
            ?>
            <?php if ($urgent_confirmations > 0): ?>
            <div class="alert-item warning">
                <div class="alert-icon">
                    <i data-feather="alert-triangle"></i>
                </div>
                <div class="alert-content">
                    <div class="alert-title">Pending Confirmations</div>
                    <div class="alert-description"><?= $urgent_confirmations; ?> services waiting for confirmation</div>
                </div>
                <a href="<?= site_url('Admin/cus_konf') ?>" class="alert-action">Review</a>
            </div>
            <?php endif; ?>

            <?php if ($high_pending): ?>
            <div class="alert-item info">
                <div class="alert-icon">
                    <i data-feather="dollar-sign"></i>
                </div>
                <div class="alert-content">
                    <div class="alert-title">High Pending Deposits</div>
                    <div class="alert-description">Large amount pending bank deposit</div>
                </div>
                <a href="<?= site_url('Admin/cus_konf_bank') ?>" class="alert-action">Deposit</a>
            </div>
            <?php endif; ?>

            <?php if ($service_completion_rate < 80): ?>
            <div class="alert-item danger">
                <div class="alert-icon">
                    <i data-feather="trending-down"></i>
                </div>
                <div class="alert-content">
                    <div class="alert-title">Low Service Completion</div>
                    <div class="alert-description">Service completion rate below 80%</div>
                </div>
                <a href="#" class="alert-action">Monitor</a>
            </div>
            <?php endif; ?>

            <?php if (empty($urgent_confirmations) && !$high_pending && $service_completion_rate >= 80): ?>
            <div class="alert-item success">
                <div class="alert-icon">
                    <i data-feather="check-circle"></i>
                </div>
                <div class="alert-content">
                    <div class="alert-title">All Good</div>
                    <div class="alert-description">No urgent issues to address</div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Payment Methods -->
        <div class="section-header">
            <h2 class="section-title">Payment Methods</h2>
        </div>

        <div class="traffic-card">
            <?php
            // Bank percentages from transaksi_detail
            $bca_pct = isset($bank_percentages['BCA']) ? $bank_percentages['BCA'] : 0;
            $mandiri_pct = isset($bank_percentages['MANDIRI']) ? $bank_percentages['MANDIRI'] : 0;
            $bri_pct = isset($bank_percentages['BRI']) ? $bank_percentages['BRI'] : 0;

            // Debug output
            echo "<!-- DEBUG: BCA: $bca_pct%, Mandiri: $mandiri_pct%, BRI: $bri_pct%, Tunai: $tunai_percentage%, Voucher: $voucher_usage_percentage% -->";
            ?>

            <!-- Bank Transfer Percentages -->
            <?php if (!empty($bank_percentages)): ?>
            <div class="traffic-item">
                <div class="traffic-icon direct">
                    <i data-feather="credit-card"></i>
                </div>
                <div class="traffic-info">
                    <div class="traffic-source">Bank BCA</div>
                    <div class="traffic-progress">
                        <div class="traffic-progress-bar" style="width: <?= $bca_pct; ?>%; background: var(--primary); transition: width 1s ease;"></div>
                    </div>
                </div>
                <div class="traffic-value"><?= $bca_pct; ?>%</div>
            </div>

            <div class="traffic-item">
                <div class="traffic-icon social">
                    <i data-feather="credit-card"></i>
                </div>
                <div class="traffic-info">
                    <div class="traffic-source">Bank Mandiri</div>
                    <div class="traffic-progress">
                        <div class="traffic-progress-bar" style="width: <?= $mandiri_pct; ?>%; background: var(--secondary); transition: width 1s ease;"></div>
                    </div>
                </div>
                <div class="traffic-value"><?= $mandiri_pct; ?>%</div>
            </div>

            <div class="traffic-item">
                <div class="traffic-icon referral">
                    <i data-feather="credit-card"></i>
                </div>
                <div class="traffic-info">
                    <div class="traffic-source">Bank BRI</div>
                    <div class="traffic-progress">
                        <div class="traffic-progress-bar" style="width: <?= $bri_pct; ?>%; background: #10b981; transition: width 1s ease;"></div>
                    </div>
                </div>
                <div class="traffic-value"><?= $bri_pct; ?>%</div>
            </div>
            <?php else: ?>
            <div class="traffic-item">
                <div class="traffic-icon direct">
                    <i data-feather="credit-card"></i>
                </div>
                <div class="traffic-info">
                    <div class="traffic-source">Bank Transfer</div>
                    <div class="traffic-progress">
                        <div class="traffic-progress-bar" style="width: 0%; background: var(--primary); transition: width 1s ease;"></div>
                    </div>
                </div>
                <div class="traffic-value">0%</div>
            </div>
            <?php endif; ?>

            <!-- Payment Pending -->
            <div class="traffic-item">
                <div class="traffic-icon warning">
                    <i data-feather="clock"></i>
                </div>
                <div class="traffic-info">
                    <div class="traffic-source">Payment Pending</div>
                    <div class="traffic-progress">
                        <div class="traffic-progress-bar" style="width: 100%; background: #f59e0b; transition: width 1s ease;"></div>
                    </div>
                </div>
                <div class="traffic-value"><?= $total_pending_transfers; ?></div>
            </div>

            <!-- Tunai vs Transfer -->
            <div class="traffic-item">
                <div class="traffic-icon direct">
                    <i data-feather="dollar-sign"></i>
                </div>
                <div class="traffic-info">
                    <div class="traffic-source">Tunai</div>
                    <div class="traffic-progress">
                        <div class="traffic-progress-bar" style="width: <?= $tunai_percentage; ?>%; background: #f59e0b; transition: width 1s ease;"></div>
                    </div>
                </div>
                <div class="traffic-value"><?= $tunai_percentage; ?>%</div>
            </div>

            <!-- Voucher Usage -->
            <div class="traffic-item">
                <div class="traffic-icon referral">
                    <i data-feather="tag"></i>
                </div>
                <div class="traffic-info">
                    <div class="traffic-source">Voucher Used</div>
                    <div class="traffic-progress">
                        <div class="traffic-progress-bar" style="width: <?= $voucher_usage_percentage; ?>%; background: #8b5cf6; transition: width 1s ease;"></div>
                    </div>
                </div>
                <div class="traffic-value"><?= $voucher_usage_percentage; ?>%</div>
            </div>
        </div>

        <!-- Recent Customers and Users -->
        <div class="section-header" style="margin-top: 3rem;">
            <h2 class="section-title">Recent Activity</h2>
        </div>

        <div class="recent-activity-grid">
            <!-- Recent Customers -->
            <div class="recent-section recent-customers">
                <div class="section-header">
                    <h3 class="section-title">Recent Customers</h3>
                    <span class="section-count"><?= $baru->num_rows(); ?> new</span>
                </div>

                <?php if ($baru->num_rows() > 0): ?>
                    <div class="activity-list">
                        <?php foreach ($baru->result_array() as $row): ?>
                            <div class="activity-item">
                                <div class="activity-icon">
                                    <div class="activity-avatar">
                                        <?= strtoupper(substr($row['cos_nama'], 0, 1)); ?>
                                    </div>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-title"><?= html_escape($row['cos_nama']); ?></div>
                                    <div class="activity-description">Customer baru dikonfirmasi</div>
                                </div>
                                <div class="activity-time"><?= html_escape($row['cos_jam']); ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i data-feather="users" style="width: 48px; height: 48px;"></i>
                        <p>Belum ada customer baru</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Recent Users -->
            <div class="recent-section recent-users">
                <div class="section-header">
                    <h3 class="section-title">Recent Users</h3>
                    <span class="section-count"><?= $users_baru->num_rows(); ?> new</span>
                </div>

                <?php if ($users_baru->num_rows() > 0): ?>
                    <div class="activity-list">
                        <?php foreach ($users_baru->result_array() as $row): ?>
                            <div class="activity-item">
                                <div class="activity-icon">
                                    <div class="activity-avatar">
                                        <?= strtoupper(substr($row['cos_nama'], 0, 1)); ?>
                                    </div>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-title"><?= html_escape($row['cos_nama']); ?></div>
                                    <div class="activity-description">User baru terdaftar</div>
                                </div>
                                <div class="activity-time"><?= html_escape($row['cos_jam']); ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i data-feather="users" style="width: 48px; height: 48px;"></i>
                        <p>Belum ada user baru</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
    
</div>
<!-- End Content Area -->

<!-- Existing template footer -->
<?php $this->load->view('Template/footer'); ?>

<style>
.content-area {
    margin-top: 4rem;
}

.weekly-btn.active,
.tech-btn.active {
    background-color: #6366f1;
    color: white;
    border-color: #6366f1;
}
.weekly-btn,
.tech-btn {
    background-color: #f3f4f6;
    color: #374151;
    border: 1px solid #d1d5db;
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    cursor: pointer;
    transition: all 0.2s;
}
.weekly-btn:hover,
.tech-btn:hover {
    background-color: #e5e7eb;
}
.weekly-btn.active:hover,
.tech-btn.active:hover {
    background-color: #4f46e5;
}
.recent-activity-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
}
.recent-section .section-header {
    margin-bottom: 1rem;
}
.recent-section .section-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #374151;
}
.recent-customers {
    background: rgba(16, 185, 129, 0.05);
    border-radius: 8px;
    padding: 1rem;
}
.recent-users {
    background: rgba(245, 158, 11, 0.05);
    border-radius: 8px;
    padding: 1rem;
}
.activity-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}
.activity-item {
    display: flex;
    align-items: center;
    padding: 0.75rem;
    background: white;
    border-radius: 6px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}
.activity-icon {
    margin-right: 0.75rem;
}
.activity-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #6366f1;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 14px;
}
.activity-content {
    flex: 1;
}
.activity-title {
    font-weight: 500;
    color: #374151;
    margin-bottom: 0.25rem;
}
.activity-description {
    font-size: 0.875rem;
    color: #6b7280;
}
.activity-time {
    font-size: 0.75rem;
    color: #9ca3af;
}
.metric-progress {
    width: 100%;
    height: 4px;
    background: rgba(0, 0, 0, 0.1);
    border-radius: 2px;
    margin-top: 8px;
    overflow: hidden;
}
.metric-progress-bar {
    height: 100%;
    border-radius: 2px;
    transition: width 0.3s ease;
}
.alerts-card {
    background: white;
    border-radius: 8px;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    margin-bottom: 2rem;
}
.alert-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    border-radius: 6px;
    margin-bottom: 0.75rem;
    border-left: 4px solid;
}
.alert-item.warning {
    background: rgba(245, 158, 11, 0.05);
    border-left-color: #f59e0b;
}
.alert-item.info {
    background: rgba(59, 130, 246, 0.05);
    border-left-color: #3b82f6;
}
.alert-item.danger {
    background: rgba(239, 68, 68, 0.05);
    border-left-color: #ef4444;
}
.alert-item.success {
    background: rgba(16, 185, 129, 0.05);
    border-left-color: #10b981;
}
.alert-icon {
    margin-right: 1rem;
    color: inherit;
}
.alert-content {
    flex: 1;
}
.alert-title {
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.25rem;
}
.alert-description {
    font-size: 0.875rem;
    color: #6b7280;
}
.alert-action {
    padding: 0.5rem 1rem;
    background: var(--primary);
    color: white;
    text-decoration: none;
    border-radius: 4px;
    font-size: 0.875rem;
    font-weight: 500;
    transition: background 0.2s;
}
.alert-action:hover {
    background: var(--primary-dark, #4f46e5);
}
.traffic-icon.warning {
    background: rgba(245, 158, 11, 0.1);
    color: #f59e0b;
}

/* Export Buttons Styles */
.export-buttons {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.export-btn {
    display: flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
    white-space: nowrap;
    transition: all 0.2s ease;
}

.export-btn:hover {
    background-color: #f3f4f6;
    border-color: #d1d5db;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.export-btn i {
    width: 16px;
    height: 16px;
}

/* Responsive design for smaller screens */
@media (max-width: 768px) {
    .export-buttons {
        flex-direction: column;
        align-items: stretch;
    }

    .export-btn {
        justify-content: center;
        padding: 0.75rem;
    }
}
</style>

<!-- Dashboard Scripts -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Feather Icons
    feather.replace();
    
    // Real data from PHP
    const realData = {
        revenue: {
            labels: [<?php
                $labels = [];
                if (!empty($weekly_revenue)) {
                    foreach ($weekly_revenue as $row) {
                        $labels[] = "'" . date('D', strtotime($row['day'])) . "'";
                    }
                } else {
                    $labels = ["'Mon'", "'Tue'", "'Wed'", "'Thu'", "'Fri'", "'Sat'", "'Sun'"];
                }
                echo implode(',', $labels);
            ?>],
            data: [<?php
                $data = [];
                if (!empty($weekly_revenue)) {
                    foreach ($weekly_revenue as $row) {
                        $data[] = $row['total'];
                    }
                } else {
                    $data = [0, 0, 0, 0, 0, 0, 0];
                }
                echo implode(',', $data);
            ?>],
            label: 'Revenue (Rp)',
            color: '#6366f1'
        },
        transactions: {
            labels: [<?php
                $labels = [];
                if (!empty($weekly_transactions)) {
                    foreach ($weekly_transactions as $row) {
                        $labels[] = "'" . date('D', strtotime($row['day'])) . "'";
                    }
                } else {
                    $labels = ["'Mon'", "'Tue'", "'Wed'", "'Thu'", "'Fri'", "'Sat'", "'Sun'"];
                }
                echo implode(',', $labels);
            ?>],
            data: [<?php
                $data = [];
                if (!empty($weekly_transactions)) {
                    foreach ($weekly_transactions as $row) {
                        $data[] = $row['total'];
                    }
                } else {
                    $data = [0, 0, 0, 0, 0, 0, 0];
                }
                echo implode(',', $data);
            ?>],
            label: 'Transaksi yang terjadi',
            color: '#10b981'
        },
        customers: {
            labels: [<?php
                $labels = [];
                if (!empty($weekly_customers)) {
                    foreach ($weekly_customers as $row) {
                        $labels[] = "'" . date('D', strtotime($row['day'])) . "'";
                    }
                } else {
                    $labels = ["'Mon'", "'Tue'", "'Wed'", "'Thu'", "'Fri'", "'Sat'", "'Sun'"];
                }
                echo implode(',', $labels);
            ?>],
            data: [<?php
                $data = [];
                if (!empty($weekly_customers)) {
                    foreach ($weekly_customers as $row) {
                        $data[] = $row['total'];
                    }
                } else {
                    $data = [0, 0, 0, 0, 0, 0, 0];
                }
                echo implode(',', $data);
            ?>],
            label: 'Customer Baru',
            color: '#f59e0b'
        },
        technician_productivity: {
            labels: [<?php
                $labels = [];
                if (!empty($weekly_productivity)) {
                    foreach ($weekly_productivity as $row) {
                        $labels[] = "'" . date('D', strtotime($row['day'])) . "'";
                    }
                } else {
                    $labels = ["'Mon'", "'Tue'", "'Wed'", "'Thu'", "'Fri'", "'Sat'", "'Sun'"];
                }
                echo implode(',', $labels);
            ?>],
            data: [<?php
                $data = [];
                if (!empty($weekly_productivity)) {
                    foreach ($weekly_productivity as $row) {
                        $data[] = $row['total'];
                    }
                } else {
                    $data = [0, 0, 0, 0, 0, 0, 0];
                }
                echo implode(',', $data);
            ?>],
            label: 'Jumlah Service',
            color: '#8b5cf6'
        },
        transactions: {
            labels: [<?php
                $labels = [];
                if (!empty($weekly_transactions)) {
                    foreach ($weekly_transactions as $row) {
                        $labels[] = "'" . date('D', strtotime($row['day'])) . "'";
                    }
                } else {
                    $labels = ["'Mon'", "'Tue'", "'Wed'", "'Thu'", "'Fri'", "'Sat'", "'Sun'"];
                }
                echo implode(',', $labels);
            ?>],
            data: [<?php
                $data = [];
                if (!empty($weekly_transactions)) {
                    foreach ($weekly_transactions as $row) {
                        $data[] = $row['total'];
                    }
                } else {
                    $data = [0, 0, 0, 0, 0, 0, 0];
                }
                echo implode(',', $data);
            ?>],
            label: 'Transactions',
            color: '#10b981'
        },
        customers: {
            labels: [<?php
                $labels = [];
                if (!empty($weekly_customers)) {
                    foreach ($weekly_customers as $row) {
                        $labels[] = "'" . date('D', strtotime($row['day'])) . "'";
                    }
                } else {
                    $labels = ["'Mon'", "'Tue'", "'Wed'", "'Thu'", "'Fri'", "'Sat'", "'Sun'"];
                }
                echo implode(',', $labels);
            ?>],
            data: [<?php
                $data = [];
                if (!empty($weekly_customers)) {
                    foreach ($weekly_customers as $row) {
                        $data[] = $row['total'];
                    }
                } else {
                    $data = [0, 0, 0, 0, 0, 0, 0];
                }
                echo implode(',', $data);
            ?>],
            label: 'New Customers',
            color: '#f59e0b'
        },
        technician_productivity: {
            labels: [<?php
                $labels = [];
                if (!empty($weekly_productivity)) {
                    foreach ($weekly_productivity as $row) {
                        $labels[] = "'" . date('D', strtotime($row['day'])) . "'";
                    }
                } else {
                    $labels = ["'Mon'", "'Tue'", "'Wed'", "'Thu'", "'Fri'", "'Sat'", "'Sun'"];
                }
                echo implode(',', $labels);
            ?>],
            data: [<?php
                $data = [];
                if (!empty($weekly_productivity)) {
                    foreach ($weekly_productivity as $row) {
                        $data[] = $row['total'];
                    }
                } else {
                    $data = [0, 0, 0, 0, 0, 0, 0];
                }
                echo implode(',', $data);
            ?>],
            label: 'Jumlah Service',
            color: '#8b5cf6'
        },
    };

    // Initialize Weekly Chart
    const ctx = document.getElementById('weeklyChart');
    let weeklyChart;

    function createWeeklyChart(metric = 'revenue') {
        const data = realData[metric];

        if (weeklyChart) {
            weeklyChart.destroy();
        }

        ctx.chart = weeklyChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [{
                            label: data.label,
                            data: data.data,
                            borderColor: data.color,
                            backgroundColor: data.color + '20', // Add 20 for 12% opacity
                            tension: 0.4,
                            fill: true,
                            borderWidth: 2
                        }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        borderRadius: 8,
                        titleFont: { size: 14, weight: 'bold' },
                        bodyFont: { size: 13 },
                        callbacks: {
                            label: function(context) {
                                let value = context.parsed.y;
                                if (metric === 'revenue') {
                                    value = 'Rp ' + value.toLocaleString('id-ID');
                                } else if (metric === 'service_completion') {
                                    value = value + '%';
                                }
                                return data.label + ': ' + value;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            callback: function(value) {
                                if (metric === 'revenue') {
                                    return 'Rp ' + Math.round(value).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                                } else if (metric === 'service_completion') {
                                    return value + '%';
                                }
                                return value;
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    if (ctx) {
        if (typeof Chart !== 'undefined') {
            try {
                createWeeklyChart();
                console.log('Chart created successfully');
            } catch (error) {
                console.error('Chart creation failed:', error);
                ctx.style.background = '#f0f0f0';
                ctx.style.display = 'flex';
                ctx.style.alignItems = 'center';
                ctx.style.justifyContent = 'center';
                ctx.style.fontSize = '16px';
                ctx.style.color = '#666';
                ctx.textContent = 'Chart creation failed: ' + error.message;
            }
        } else {
            console.error('Chart.js is not loaded');
            ctx.style.background = '#ffebee';
            ctx.style.display = 'flex';
            ctx.style.alignItems = 'center';
            ctx.style.justifyContent = 'center';
            ctx.style.fontSize = '16px';
            ctx.style.color = '#c62828';
            ctx.textContent = 'Chart.js not loaded - check CDN';
        }
    } else {
        console.error('Canvas element not found');
    }

    // Metric selector event listener
    document.getElementById('metricSelector').addEventListener('change', function() {
        const selectedMetric = this.value;
        createWeeklyChart(selectedMetric);
    });

    // Initialize Technician Productivity Chart
    const technicianCtx = document.getElementById('technicianChart');
    if (technicianCtx) {
        const technicianData = {
            labels: [<?php
                $techLabels = [];
                $techData = [];
                if (!empty($technician_details)) {
                    $techMap = [];
                    foreach ($technician_details as $tech) {
                        $name = $tech['technician_name'] ?? 'Tidak Diketahui';
                        if (!isset($techMap[$name])) {
                            $techMap[$name] = 0;
                        }
                        $techMap[$name] += $tech['services_completed'] ?? 0;
                    }
                    arsort($techMap); // Sort by services completed descending
                    $count = 0;
                    foreach ($techMap as $name => $total) {
                        if ($count >= 7) break;
                        $techLabels[] = "'" . addslashes($name) . "'";
                        $techData[] = $total;
                        $count++;
                    }
                } else {
                    $techLabels = ["'No Data'"];
                    $techData = [0];
                }
                echo implode(',', $techLabels);
            ?>],
            datasets: [{
                label: 'Services Completed',
                data: [<?php echo implode(',', $techData); ?>],
                backgroundColor: [
                    '#6366f1', '#10b981', '#f59e0b', '#8b5cf6',
                    '#ef4444', '#06b6d4', '#84cc16'
                ],
                borderWidth: 1
            }]
        };

        new Chart(technicianCtx, {
            type: 'doughnut',
            data: technicianData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.parsed + ' aksi';
                            }
                        }
                    }
                }
            }
        });
    }

    
    // Weekly filter buttons
    document.querySelectorAll('.weekly-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.weekly-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const period = this.getAttribute('data-period') || this.textContent.trim();
            loadChartData(period);
        });
    });

    // Technician filter buttons
    document.querySelectorAll('.tech-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.tech-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const period = this.getAttribute('data-period') || this.textContent.trim();
            loadTechnicianData(period);
        });
    });

    function loadChartData(period) {
        fetch(`<?= site_url('Admin/chart_data') ?>?period=${period}`)
            .then(response => response.json())
            .then(data => {
                // Update realData with new data
                updateRealData(data, period);
                // Recreate chart with current metric
                const currentMetric = document.getElementById('metricSelector').value;
                createWeeklyChart(currentMetric);
            })
            .catch(error => console.error('Error loading chart data:', error));
    }

    function loadTechnicianData(period) {
        fetch(`<?= site_url('Admin/chart_technician_data') ?>?period=${period}`)
            .then(response => response.json())
            .then(data => {
                // Update technician chart
                updateTechnicianChart(data.technician_details);
                // Update technician list display
                updateTechnicianList(data.technician_details);
            })
            .catch(error => console.error('Error loading technician data:', error));
    }

    function updateTechnicianList(techData) {
        // Group technician data by name and sum services_completed
        const techMap = {};
        techData.forEach(tech => {
            const name = tech.technician_name || 'Teknisi Tidak Diketahui';
            if (!techMap[name]) {
                techMap[name] = 0;
            }
            techMap[name] += parseInt(tech.services_completed) || 0;
        });

        // Sort by services completed descending
        const sortedTechs = Object.entries(techMap)
            .sort(([,a], [,b]) => b - a)
            .slice(0, 5); // Top 5

        // Update the technician list HTML
        const technicianItems = document.querySelector('.technician-items');
        if (technicianItems) {
            technicianItems.innerHTML = sortedTechs.map(([name, services], index) => `
                <div class="technician-item">
                    <div class="technician-rank">${index + 1}</div>
                    <div class="technician-name">${name}</div>
                    <div class="technician-services">${parseInt(services)} completed</div>
                </div>
            `).join('');
        }
    }

    function updateRealData(data, period) {
        // Process data for each metric
        realData.revenue = processData(data.revenue, 'revenue');
        realData.transactions = processData(data.transactions, 'transactions');
        realData.customers = processData(data.customers, 'customers');
        realData.technician_productivity = processData(data.technician_productivity, 'technician_productivity');
    }

    function processData(rawData, metric) {
        let labels = [];
        let dataPoints = [];
        rawData.forEach(item => {
            labels.push(new Date(item.day).toLocaleDateString('en-US', {month: 'short', day: 'numeric'}));
            dataPoints.push(item.total);
        });
        return {
            labels: labels,
            data: dataPoints,
            label: getLabel(metric),
            color: getColor(metric)
        };
    }

    function getLabel(metric) {
        const labels = {
            'revenue': 'Revenue (Rp)',
            'transactions': 'Transaksi yang terjadi',
            'customers': 'Customer Baru',
            'technician_productivity': 'Jumlah Service'
        };
        return labels[metric] || 'Data';
    }

    function getColor(metric) {
        const colors = {
            'revenue': '#6366f1',
            'transactions': '#10b981',
            'customers': '#f59e0b',
            'technician_productivity': '#8b5cf6'
        };
        return colors[metric] || '#6366f1';
    }

    function updateTechnicianChart(techData) {
        // Update technician chart with new data
        const technicianCtx = document.getElementById('technicianChart');
        if (technicianCtx && technicianCtx.chart) {
            technicianCtx.chart.destroy();
        }
        // Recreate chart with new data
        const technicianData = {
            labels: techData.map(tech => tech.technician_name),
            datasets: [{
                label: 'Services Completed',
                data: techData.map(tech => tech.services_completed),
                backgroundColor: [
                    '#6366f1', '#10b981', '#f59e0b', '#8b5cf6',
                    '#ef4444', '#06b6d4', '#84cc16'
                ],
                borderWidth: 1
            }]
        };

        new Chart(technicianCtx, {
            type: 'doughnut',
            data: technicianData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.parsed + ' aksi';
                            }
                        }
                    }
                }
            }
        });
    }
});


// Export dashboard report - defined globally
function exportDashboardReport(section = 'all') {
    console.log('Export button clicked for section:', section);

    // Get current filter states
    const weeklyPeriod = document.querySelector('.weekly-btn.active')?.getAttribute('data-period') || document.querySelector('.weekly-btn.active')?.textContent?.trim() || '7D';
    const technicianPeriod = document.querySelector('.tech-btn.active')?.getAttribute('data-period') || document.querySelector('.tech-btn.active')?.textContent?.trim() || '7D';

    console.log('Current filters - Weekly:', weeklyPeriod, 'Technician:', technicianPeriod);

    // Open report in new window with filter parameters
    const reportUrl = '<?= site_url("Admin/export_dashboard") ?>?weekly_period=' + encodeURIComponent(weeklyPeriod) + '&technician_period=' + encodeURIComponent(technicianPeriod) + '&section=' + encodeURIComponent(section);
    const reportWindow = window.open(reportUrl, '_blank');
    if (reportWindow) {
        reportWindow.focus();
    } else {
        alert('Please allow popups for this website to view the report.');
    }
}
</script>