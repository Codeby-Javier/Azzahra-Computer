<?php $this->load->view('Template/header'); ?>

<?php
  // Normalisasi filter supaya 'waiting' dan 'waitingApproval' dianggap sama
  $isPending = isset($filter) && $filter === 'pending';
  $isWaiting = isset($filter) && ($filter === 'waiting' || $filter === 'waitingApproval');
  $isConfirm = isset($filter) && $filter === 'confirm';
  $isRepairing = isset($filter) && $filter === 'repairing';
  $isCompleted = isset($filter) && $filter === 'completed';
  $isFailed = isset($filter) && $filter === 'failed';
  $isOrderPart = isset($filter) && $filter === 'orderpart';

  // Get flashdata and immediately clear it to prevent re-showing on refresh
  $suksesMsg = $this->session->flashdata('sukses');
  $gagalMsg = $this->session->flashdata('gagal');
  $this->session->set_flashdata('sukses', '');
  $this->session->set_flashdata('gagal', '');
?>
<script>
window.todayOrderCount = <?php echo count($today_orders); ?>;
window.currentUser = '<?php echo addslashes($this->session->userdata('nama')); ?>'; 
</script>  

  <!-- Header -->
        <header class="page-header">
            <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
                <i data-feather="menu"></i>
            </div>
            <div class="header-title">
                <h1>                  
                  <i data-feather="shopping-cart" class="w-6 h-6 inline-block mr-2"></i>
                  Order</h1>                
            </div>
            <div class="header-actions">                
                <div class="notification-container">
                    <div class="header-btn notification-toggle" onclick="toggleNotificationDropdown()">
                        <i data-feather="bell"></i>
                        <?php if (!empty($today_orders)): ?>
                            <span class="badge-count"><?php echo count($today_orders); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="notification-dropdown" id="notificationDropdown">
                        <div class="notification-header">
                            <h4>Notifikasi Order Hari Ini</h4>
                        </div>
                        <div class="notification-list">
                            <?php if (!empty($today_orders)): ?>
                                <?php foreach ($today_orders as $order): ?>
                                    <div class="notification-item">
                                        <div class="notification-content">
                                            <p><strong><?php echo $order['trans_kode']; ?></strong> - <?php echo $order['cos_nama'] ?? 'N/A'; ?></p>
                                            <p>Status: <?php echo $order['trans_status']; ?></p>
                                            <small><?php echo date('H:i', strtotime($order['created_at'])); ?></small>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="notification-empty">Tidak ada order baru hari ini</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>               
            </div>
        </header>

<div class="content">
  <div class="sukses" data-sukses="<?php echo $suksesMsg; ?>"></div>
  <div class="gagal" data-gagal="<?php echo $gagalMsg; ?>"></div>

  <!-- Header Section -->
  <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-2xl font-bold mr-auto text-gray-800">      
      Order Management
    </h2>
  </div>

  <div class="intro-y grid grid-cols-12 gap-6 mt-5">
    <!-- Sidebar Filter -->
    <div class="col-span-12 lg:col-span-3">
      <div class="box p-5 sticky top-5">
        <h3 class="font-semibold text-base mb-4 text-gray-700">Filter Status</h3>
        <div class="space-y-2">
          <a href="<?= site_url('Order/index/pending') ?>"
             class="filter-link flex items-center justify-between px-4 py-3 rounded-lg transition-all duration-200 <?php echo ($isPending) ? 'bg-yellow-500 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'; ?>">
            <div class="flex items-center">
              <i data-feather="clock" class="w-5 h-5 mr-3"></i>
              <span class="font-medium">Pending</span>
            </div>
            <?php if ($isPending): ?>
              <span class="bg-white text-yellow-500 text-xs font-bold px-2 py-1 rounded-full">
                <?= $orders->num_rows() ?>
              </span>
            <?php endif; ?>
          </a>

          <a href="<?= site_url('Order/index/waiting') ?>"
             class="filter-link flex items-center justify-between px-4 py-3 rounded-lg transition-all duration-200 <?php echo ($isWaiting) ? 'bg-blue-500 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'; ?>">
            <div class="flex items-center">
              <i data-feather="alert-circle" class="w-5 h-5 mr-3"></i>
              <span class="font-medium">Waiting Approval</span>
            </div>
            <?php if ($isWaiting): ?>
              <span class="bg-white text-blue-500 text-xs font-bold px-2 py-1 rounded-full">
                <?= $orders->num_rows() ?>
              </span>
            <?php endif; ?>
          </a>

          <a href="<?= site_url('Order/index/confirm') ?>"
             class="filter-link flex items-center justify-between px-4 py-3 rounded-lg transition-all duration-200 <?php echo ($isConfirm) ? 'bg-purple-500 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'; ?>">
            <div class="flex items-center">
              <i data-feather="check-circle" class="w-5 h-5 mr-3"></i>
              <span class="font-medium">Confirm</span>
            </div>
            <?php if ($isConfirm): ?>
              <span class="bg-white text-purple-500 text-xs font-bold px-2 py-1 rounded-full">
                <?= $orders->num_rows() ?>
              </span>
            <?php endif; ?>
          </a>

          <!-- Service Section Separator -->
          <div class="mt-4 mb-2 px-4">
            <h4 class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Service</h4>
            <hr class="border-gray-300 mt-2">
          </div>

          <a href="<?= site_url('Order/index/repairing') ?>"
             class="filter-link flex items-center justify-between px-4 py-3 rounded-lg transition-all duration-200 <?php echo ($isRepairing) ? 'bg-orange-500 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'; ?>">
            <div class="flex items-center">
              <i data-feather="settings" class="w-5 h-5 mr-3"></i>
              <span class="font-medium">Repairing</span>
            </div>
            <?php if ($isRepairing): ?>
              <span class="bg-white text-orange-500 text-xs font-bold px-2 py-1 rounded-full">
                <?= isset($total_repairing) ? $total_repairing : $orders->num_rows() ?>
              </span>
            <?php endif; ?>
          </a>

          <a href="<?= site_url('Order/index/completed') ?>"
             class="filter-link flex items-center justify-between px-4 py-3 rounded-lg transition-all duration-200 <?php echo ($isCompleted) ? 'bg-green-500 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'; ?>">
            <div class="flex items-center">
              <i data-feather="check-square" class="w-5 h-5 mr-3"></i>
              <span class="font-medium">Completed</span>
            </div>
            <?php if ($isCompleted): ?>
              <span class="bg-white text-green-500 text-xs font-bold px-2 py-1 rounded-full">
                <?= $orders->num_rows() ?>
              </span>
            <?php endif; ?>
          </a>

          <a href="<?= site_url('Order/index/failed') ?>"
             class="filter-link flex items-center justify-between px-4 py-3 rounded-lg transition-all duration-200 <?php echo (isset($filter) && $filter === 'failed') ? 'bg-red-500 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'; ?>">
            <div class="flex items-center">
              <i data-feather="x-circle" class="w-5 h-5 mr-3"></i>
              <span class="font-medium">Failed</span>
            </div>
            <?php if (isset($filter) && $filter === 'failed'): ?>
              <span class="bg-white text-red-500 text-xs font-bold px-2 py-1 rounded-full">
                <?= $orders->num_rows() ?>
              </span>
            <?php endif; ?>
          </a>

          <a href="<?= site_url('Order/index/orderpart') ?>"
             class="filter-link flex items-center justify-between px-4 py-3 rounded-lg transition-all duration-200 <?php echo (isset($filter) && $filter === 'orderpart') ? 'bg-indigo-500 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'; ?>">
            <div class="flex items-center">
              <i data-feather="package" class="w-5 h-5 mr-3"></i>
              <span class="font-medium">Order Part</span>
            </div>
          </a>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="col-span-12 lg:col-span-9">
      <div class="box p-6">
        <!-- Header with Badge -->
        <div class="flex items-center justify-between mb-6">
          <div class="flex items-center">
            <h3 class="text-xl font-bold text-gray-800 mr-3"><?php echo $table_title; ?></h3>
            <span class="px-3 py-1 rounded-full text-sm font-semibold
              <?php
                if ($isPending) echo 'bg-yellow-100 text-yellow-800';
                elseif ($isWaiting) echo 'bg-blue-100 text-blue-800';
                elseif ($isConfirm) echo 'bg-purple-100 text-purple-800';
                elseif ($isRepairing) echo 'bg-orange-100 text-orange-800';
                elseif ($isCompleted) echo 'bg-green-100 text-green-800';
                elseif ($isFailed) echo 'bg-red-100 text-red-800';
                else echo 'bg-gray-100 text-gray-800';
              ?>">
              <?php
                if ($isRepairing && isset($total_repairing)) {
                  echo $total_repairing;
                } elseif ($isOrderPart) {
                  echo isset($orders) && is_object($orders) ? $orders->num_rows() : 0;
                } else {
                  echo $orders->num_rows();
                }
              ?> Orders
            </span>
          </div>
        </div>

        <!-- Pending Orders -->
        <?php if ($isPending): ?>
          <?php if ($orders->num_rows() > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
              <?php foreach ($orders->result_array() as $row) : ?>
                <?php
                  // Gunakan trans_kode saja sebagai unique ID
                  $modalId = 'detailModal_' . str_replace('-', '_', $row['trans_kode']);
                ?>
                <div class="border border-blue-200 rounded-lg p-4 hover:shadow-xl transition-all duration-200 bg-white cursor-pointer hover:border-blue-400"
                     onclick="openModal('<?= $modalId; ?>')">
                  <div class="flex items-center justify-between mb-3">
                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-bold">
                      PENDING
                    </span>
                    <span class="text-xs text-gray-500 font-mono">
                      <?= $row['trans_kode']?>
                    </span>
                  </div>

                  <div class="mb-3">
                    <p class="font-semibold text-gray-900 text-sm mb-1 flex items-center">
                      <i data-feather="user" class="w-4 h-4 mr-2 text-blue-500"></i>
                      <?= $row['cos_nama'] ?? 'N/A' ?>
                    </p>
                  </div>

                  <div class="bg-blue-50 rounded-lg p-3 mb-3">
                    <p class="text-xs text-blue-600 font-semibold mb-1">Tindakan</p>
                    <p class="text-sm font-bold text-blue-900"><?= $row['tdkn_nama'] ?? 'N/A' ?></p>
                  </div>

                  <div class="flex items-center justify-between text-xs text-gray-600">
                    <span class="flex items-center">
                      <i data-feather="hash" class="w-3 h-3 mr-1"></i>
                      Qty: <strong class="ml-1"><?= $row['tdkn_qty'] ?? 0 ?></strong>
                    </span>
                    <span class="text-blue-600 font-semibold flex items-center">
                      Lihat Detail
                      <i data-feather="arrow-right" class="w-3 h-3 ml-1"></i>
                    </span>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <div class="text-center py-12">
              <i data-feather="inbox" class="w-16 h-16 mx-auto text-gray-300 mb-4"></i>
              <p class="text-gray-500">Tidak ada order menunggu approval</p>
            </div>
          <?php endif; ?>

        <!-- Waiting Approval Orders -->
        <?php elseif ($isWaiting): ?>
          <?php if ($orders->num_rows() > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
              <?php foreach ($orders->result_array() as $row) : ?>
                <?php
                  // Gunakan trans_kode saja sebagai unique ID
                  $modalId = 'detailModal_' . str_replace('-', '_', $row['trans_kode']);
                ?>
                <div class="border border-blue-200 rounded-lg p-4 hover:shadow-xl transition-all duration-200 bg-white cursor-pointer hover:border-blue-400"
                     onclick="openModal('<?= $modalId; ?>')">
                  <div class="flex items-center justify-between mb-3">
                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-bold">
                      WAITING
                    </span>
                    <span class="text-xs text-gray-500 font-mono">
                      <?= $row['trans_kode']?>
                    </span>
                  </div>

                  <div class="mb-3">
                    <p class="font-semibold text-gray-900 text-sm mb-1 flex items-center">
                      <i data-feather="user" class="w-4 h-4 mr-2 text-blue-500"></i>
                      <?= $row['cos_nama'] ?? 'N/A' ?>
                    </p>
                  </div>

                  <div class="bg-blue-50 rounded-lg p-3 mb-3">
                    <p class="text-xs text-blue-600 font-semibold mb-1">Tindakan</p>
                    <p class="text-sm font-bold text-blue-900"><?= $row['tdkn_nama'] ?? 'N/A' ?></p>
                  </div>

                  <div class="flex items-center justify-between text-xs text-gray-600">
                    <span class="flex items-center">
                      <i data-feather="hash" class="w-3 h-3 mr-1"></i>
                      Qty: <strong class="ml-1"><?= $row['tdkn_qty'] ?? 0 ?></strong>
                    </span>
                    <span class="text-blue-600 font-semibold flex items-center">
                      Lihat Detail
                      <i data-feather="arrow-right" class="w-3 h-3 ml-1"></i>
                    </span>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <div class="text-center py-12">
              <i data-feather="inbox" class="w-16 h-16 mx-auto text-gray-300 mb-4"></i>
              <p class="text-gray-500">Tidak ada order menunggu approval</p>
            </div>
          <?php endif; ?>

        <!-- Confirmed Orders -->
        <?php elseif ($isConfirm): ?>
          <?php if ($orders->num_rows() > 0): ?>
            <div class="grid grid-cols-1 gap-4">
              <?php foreach ($orders->result_array() as $row) : ?>
                <?php
                  $safeTrans = preg_replace('/[^A-Za-z0-9_-]/', '-', $row['trans_kode']);
                  $confirmModalId = "confirmModal-{$safeTrans}";
                ?>
                <div class="border border-gray-200 rounded-lg p-5 hover:shadow-lg transition-shadow duration-200 bg-white">
                  <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex-1">
                      <div class="flex items-center mb-3">
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-bold mr-3">
                          PENDING
                        </span>
                        <span class="font-mono text-sm font-bold text-gray-700">
                          <?= $row['trans_kode']?>
                        </span>
                      </div>

                      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                          <p class="text-xs text-gray-500 mb-1">Customer</p>
                          <p class="font-semibold text-gray-800 flex items-center">
                            <i data-feather="user" class="w-4 h-4 mr-2 text-gray-400"></i>
                            <?= $row['cos_nama']?>
                          </p>
                        </div>
                        <div>
                          <p class="text-xs text-gray-500 mb-1">Alamat</p>
                          <p class="text-sm text-gray-700 flex items-start">
                            <i data-feather="map-pin" class="w-4 h-4 mr-2 mt-1 text-gray-400 flex-shrink-0"></i>
                            <span class="line-clamp-2"><?= $row['alamat']?></span>
                          </p>
                        </div>
                      </div>
                    </div>

                    <div class="flex items-center gap-2 mt-4 lg:mt-0 lg:ml-6">
                      <button onclick="openModal('<?= $confirmModalId ?>')"
                        class="flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors duration-200 font-medium text-sm">
                        <i data-feather="check-square" class="w-4 h-4 mr-2"></i>
                        Confirm
                      </button>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <div class="text-center py-12">
              <i data-feather="inbox" class="w-16 h-16 mx-auto text-gray-300 mb-4"></i>
              <p class="text-gray-500">Tidak ada order pending</p>
            </div>
          <?php endif; ?>

        <!-- Repairing Orders -->
        <?php elseif ($isRepairing): ?>
          <!-- Search Form -->
          <div class="mb-6">
            <form method="get" action="<?= site_url('Order/index/repairing') ?>" class="flex gap-2" onsubmit="showLoading()">
              <input type="text" name="search" value="<?= htmlspecialchars($search ?? '') ?>" placeholder="Cari cos_kode, nama customer, device, merek..." class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
              <button type="submit" class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition-colors flex items-center">
                <i data-feather="search" class="w-4 h-4 mr-2"></i>Cari
              </button>
            </form>
          </div>

          <?php if ($orders->num_rows() > 0): ?>
            <div class="grid grid-cols-1 gap-4">
              <?php foreach ($orders->result_array() as $row) : ?>
                <?php
                  $safeTrans = preg_replace('/[^A-Za-z0-9_-]/', '-', $row['trans_kode']);
                  $repairingModalId = "repairingModal-{$safeTrans}";
                  $isLegacy = isset($row['source_table']) && $row['source_table'] == 'transaksi';
                ?>
                <div class="border border-gray-200 rounded-lg p-5 hover:shadow-lg transition-shadow duration-200 bg-white">
                  <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex-1">
                      <div class="flex items-center mb-3">
                        <span class="px-3 py-1 bg-orange-100 text-orange-800 rounded-full text-xs font-bold mr-3">
                          REPAIRING
                        </span>
                        <span class="font-mono text-sm font-bold text-gray-700">
                          <?= $row['cos_kode']?>
                        </span>
                      </div>

                      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                          <p class="text-xs text-gray-500 mb-1">Customer</p>
                          <p class="font-semibold text-gray-800 flex items-center">
                            <i data-feather="user" class="w-4 h-4 mr-2 text-gray-400"></i>
                            <?= $row['cos_nama']?>
                          </p>
                        </div>
                        <div>
                          <p class="text-xs text-gray-500 mb-1">Status Pembayaran</p>
                          <p class="text-sm text-gray-700 flex items-center">
                            <i data-feather="credit-card" class="w-4 h-4 mr-2 text-gray-400"></i>
                            <?php
                              $paymentStatus = isset($row['payment_status']) && $row['payment_status'] == 'Lunas' ? 'LUNAS' : 'DP';
                              $statusColor = $paymentStatus == 'LUNAS' ? 'text-green-600' : 'text-blue-600';
                            ?>
                            <span class="font-semibold <?= $statusColor ?>"><?= $paymentStatus ?></span>
                          </p>
                        </div>
                        <div>
                          <p class="text-xs text-gray-500 mb-1">Device</p>
                          <p class="text-sm text-gray-700 flex items-center">
                            <i data-feather="smartphone" class="w-4 h-4 mr-2 text-gray-400"></i>
                            <?= $row['device'] ?? 'N/A' ?>
                          </p>
                        </div>
                        <div>
                          <p class="text-xs text-gray-500 mb-1">Merek</p>
                          <p class="text-sm text-gray-700 flex items-center">
                            <i data-feather="tag" class="w-4 h-4 mr-2 text-gray-400"></i>
                            <?= $row['merek'] ?? 'N/A' ?>
                          </p>
                        </div>
                        <div>
                          <p class="text-xs text-gray-500 mb-1">Keluhan</p>
                          <p class="text-sm text-gray-700 flex items-center">
                            <i data-feather="message-square" class="w-4 h-4 mr-2 text-gray-400"></i>
                            <?= $row['ket_keluhan'] ?? 'N/A' ?>
                          </p>
                        </div>
                      </div>
                    </div>

                    <div class="flex items-center gap-2 mt-4 lg:mt-0 lg:ml-6">
                      <button onclick="openModal('<?= $repairingModalId ?>')"
                        class="flex items-center px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition-colors duration-200 font-medium text-sm">
                        <i data-feather="check-circle" class="w-4 h-4 mr-2"></i>
                        Service 
                      </button>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if (isset($pagination)): ?>
              <div class="mt-6 flex justify-center">
                <?= $pagination ?>
              </div>
            <?php endif; ?>
          <?php else: ?>
            <div class="text-center py-12">
              <i data-feather="settings" class="w-16 h-16 mx-auto text-gray-300 mb-4"></i>
              <p class="text-gray-500">Tidak ada order yang sedang diperbaiki</p>
            </div>
          <?php endif; ?>

        <!-- Completed Orders -->
        <?php elseif ($isCompleted): ?>
          <!-- Search Form -->
          <div class="mb-6">
            <form method="get" action="<?= site_url('Order/index/completed') ?>" class="flex gap-2" onsubmit="showLoading()">
              <input type="text" name="search" value="<?= htmlspecialchars($search ?? '') ?>" placeholder="Cari trans_kode, cos_kode, nama customer, device, merek..." class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
              <button type="submit" class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors flex items-center">
                <i data-feather="search" class="w-4 h-4 mr-2"></i>Cari
              </button>
            </form>
          </div>

          <?php if ($orders->num_rows() > 0): ?>
            <div class="grid grid-cols-1 gap-4">
              <?php foreach ($orders->result_array() as $row) : ?>
                <?php
                  $safeTrans = preg_replace('/[^A-Za-z0-9_-]/', '-', $row['trans_kode']);
                  $completedModalId = "completedModal-{$safeTrans}";
                ?>
                <div class="border border-gray-200 rounded-lg p-5 hover:shadow-lg transition-shadow duration-200 bg-white">
                  <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex-1">
                      <div class="flex items-center mb-3">
                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold mr-3">
                          COMPLETED
                        </span>
                        <span class="font-mono text-sm font-bold text-gray-700">
                          <?= $row['cos_kode']?>
                        </span>
                      </div>

                      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                          <p class="text-xs text-gray-500 mb-1">Customer</p>
                          <p class="font-semibold text-gray-800 flex items-center">
                            <i data-feather="user" class="w-4 h-4 mr-2 text-gray-400"></i>
                            <?= $row['cos_nama']?>
                          </p>
                        </div>
                        <div>
                          <p class="text-xs text-gray-500 mb-1">No. HP</p>
                          <p class="text-sm text-gray-700 flex items-center">
                            <i data-feather="phone" class="w-4 h-4 mr-2 text-gray-400"></i>
                            <?php
                              $hp = $row['cos_hp'];
                              $masked_hp = substr($hp, 0, -4) . 'XXXX';
                              echo $masked_hp;
                            ?>
                          </p>
                        </div>
                        <div>
                          <p class="text-xs text-gray-500 mb-1">Alamat</p>
                          <p class="text-sm text-gray-700 flex items-start">
                            <i data-feather="map-pin" class="w-4 h-4 mr-2 mt-1 text-gray-400 flex-shrink-0"></i>
                            <span class="line-clamp-2"><?= $row['alamat'] ?? 'N/A'; ?></span>
                          </p>
                        </div>
                      </div>
                    </div>

                    <div class="flex items-center gap-2 mt-4 lg:mt-0 lg:ml-6">
                      <button onclick="sendFollowUp('<?= $row['cos_hp'] ?>', '<?= $row['cos_nama'] ?>', '<?= $row['cos_kode'] ?>', '<?= $row['merek'] ?>', '<?= $row['device'] ?>')"
                        class="flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors duration-200 font-medium text-sm">
                        <i data-feather="send" class="w-4 h-4 mr-2"></i>
                        Kirim Follow Up
                      </button>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if (isset($pagination)): ?>
              <div class="mt-6 flex justify-center">
                <?= $pagination ?>
              </div>
            <?php endif; ?>
          <?php else: ?>
            <div class="text-center py-12">
              <i data-feather="check-square" class="w-16 h-16 mx-auto text-gray-300 mb-4"></i>
              <p class="text-gray-500">Tidak ada order yang sudah selesai</p>
            </div>
          <?php endif; ?>

        <!-- Failed Orders -->
        <?php elseif ($isFailed): ?>
          <!-- Search Form -->
          <div class="mb-6">
            <form method="get" action="<?= site_url('Order/index/failed') ?>" class="flex gap-2" onsubmit="showLoading()">
              <input type="text" name="search" value="<?= htmlspecialchars($search ?? '') ?>" placeholder="Cari trans_kode, cos_kode, nama customer, device, merek..." class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
              <button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors flex items-center">
                <i data-feather="search" class="w-4 h-4 mr-2"></i>Cari
              </button>
            </form>
          </div>

          <?php if ($orders->num_rows() > 0): ?>
            <div class="grid grid-cols-1 gap-4">
              <?php foreach ($orders->result_array() as $row) : ?>
                <?php
                  $safeTrans = preg_replace('/[^A-Za-z0-9_-]/', '-', $row['trans_kode']);
                  $failedModalId = "failedModal-{$safeTrans}";
                ?>
                <div class="border border-gray-200 rounded-lg p-5 hover:shadow-lg transition-shadow duration-200 bg-white">
                  <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex-1">
                      <div class="flex items-center mb-3">
                        <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-bold mr-3">
                          FAILED
                        </span>
                        <span class="font-mono text-sm font-bold text-gray-700">
                          <?= $row['cos_kode']?>
                        </span>
                      </div>

                      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                          <p class="text-xs text-gray-500 mb-1">Customer</p>
                          <p class="font-semibold text-gray-800 flex items-center">
                            <i data-feather="user" class="w-4 h-4 mr-2 text-gray-400"></i>
                            <?= $row['cos_nama']?>
                          </p>
                        </div>
                        <div>
                          <p class="text-xs text-gray-500 mb-1">No. HP</p>
                          <p class="text-sm text-gray-700 flex items-center">
                            <i data-feather="phone" class="w-4 h-4 mr-2 text-gray-400"></i>
                            <?php
                              $hp = $row['cos_hp'];
                              $masked_hp = substr($hp, 0, -4) . 'XXXX';
                              echo $masked_hp;
                            ?>
                          </p>
                        </div>
                        <div>
                          <p class="text-xs text-gray-500 mb-1">Alamat</p>
                          <p class="text-sm text-gray-700 flex items-start">
                            <i data-feather="map-pin" class="w-4 h-4 mr-2 mt-1 text-gray-400 flex-shrink-0"></i>
                            <span class="line-clamp-2"><?= $row['alamat'] ?? 'N/A'; ?></span>
                          </p>
                        </div>
                        <div>
                          <p class="text-xs text-gray-500 mb-1">Keluhan</p>
                          <p class="text-sm text-gray-700 flex items-start">
                            <i data-feather="message-square" class="w-4 h-4 mr-2 mt-1 text-gray-400 flex-shrink-0"></i>
                            <span class="line-clamp-2"><?= $row['ket_keluhan'] ?? 'N/A'; ?></span>
                          </p>
                        </div>
                      </div>
                    </div>

                    <div class="flex items-center gap-2 mt-4 lg:mt-0 lg:ml-6">
                      <button onclick="sendFollowUpFailed('<?= $row['cos_hp'] ?>', '<?= $row['cos_nama'] ?>', '<?= $row['cos_kode'] ?>', '<?= $row['merek'] ?>', '<?= $row['device'] ?>')"
                        class="flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors duration-200 font-medium text-sm">
                        <i data-feather="send" class="w-4 h-4 mr-2"></i>
                        Kirim Follow Up
                      </button>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if (isset($pagination)): ?>
              <div class="mt-6 flex justify-center">
                <?= $pagination ?>
              </div>
            <?php endif; ?>
          <?php else: ?>
            <div class="text-center py-12">
              <i data-feather="x-circle" class="w-16 h-16 mx-auto text-gray-300 mb-4"></i>
              <p class="text-gray-500">Tidak ada order yang gagal</p>
            </div>
          <?php endif; ?>

        <!-- Order Part Status -->
        <?php elseif ($isOrderPart): ?>
          <!-- Search Form -->
          <div class="mb-6">
            <form method="get" action="<?= site_url('Order/index/orderpart') ?>" class="flex gap-2">
              <input type="text" name="search" value="<?= htmlspecialchars($search ?? '') ?>" placeholder="Cari by cos_kode, trans_kode, atau nama customer..." class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
              <button type="submit" class="px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded-lg transition-colors flex items-center">
                <i data-feather="search" class="w-4 h-4 mr-2"></i>Cari
              </button>
            </form>
          </div>

          <?php if ($orders && is_object($orders) && $orders->num_rows() > 0): ?>
            <div class="grid grid-cols-1 gap-4">
              <?php foreach ($orders->result_array() as $row) : ?>
                <?php
                  $safeTrans = preg_replace('/[^A-Za-z0-9_-]/', '-', $row['trans_kode']);
                  $modalId = "partModal-{$safeTrans}";
                ?>
                <div class="border border-gray-200 rounded-lg p-5 hover:shadow-lg transition-shadow duration-200 bg-white cursor-pointer" onclick="openModal('<?= $modalId ?>')">
                  <div class="flex items-center mb-3">
                    <span class="px-3 py-1 rounded-full text-xs font-bold mr-3
                      <?php echo ($row['is_ordered'] == 'yes') ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                      <?php echo ($row['is_ordered'] == 'yes') ? 'PART ORDERED' : 'NOT ORDERED'; ?>
                    </span>
                    <span class="font-mono text-sm font-bold text-gray-700">
                      <?= $row['trans_kode']?>
                    </span>
                  </div>

                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                      <p class="text-xs text-gray-500 mb-1">Customer</p>
                      <p class="font-semibold text-gray-800 flex items-center">
                        <i data-feather="user" class="w-4 h-4 mr-2 text-gray-400"></i>
                        <?= $row['cos_nama'] ?? 'N/A'; ?>
                      </p>
                    </div>
                    <div>
                      <p class="text-xs text-gray-500 mb-1">Cos Kode</p>
                      <p class="text-sm text-gray-700 flex items-center">
                        <i data-feather="hash" class="w-4 h-4 mr-2 text-gray-400"></i>
                        <?= $row['cos_kode'] ?? 'N/A'; ?>
                      </p>
                    </div>
                    <div>
                      <p class="text-xs text-gray-500 mb-1">Device</p>
                      <p class="text-sm text-gray-700 flex items-center">
                        <i data-feather="smartphone" class="w-4 h-4 mr-2 text-gray-400"></i>
                        <?= $row['device'] ?? 'N/A'; ?>
                      </p>
                    </div>
                    <div>
                      <p class="text-xs text-gray-500 mb-1">Merek</p>
                      <p class="text-sm text-gray-700 flex items-center">
                        <i data-feather="tag" class="w-4 h-4 mr-2 text-gray-400"></i>
                        <?= $row['merek'] ?? 'N/A'; ?>
                      </p>
                    </div>
                    <div>
                      <p class="text-xs text-gray-500 mb-1">Keluhan</p>
                      <p class="text-sm text-gray-700 flex items-center">
                        <i data-feather="message-square" class="w-4 h-4 mr-2 text-gray-400"></i>
                        <?= $row['ket_keluhan'] ?? 'N/A'; ?>
                      </p>
                    </div>
                  </div>

                  <?php if (!empty($row['rma_number']) || !empty($row['end_warranty_date'])): ?>
                    <div class="mt-3 pt-3 border-t border-gray-200">
                      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php if (!empty($row['rma_number'])): ?>
                          <div>
                            <p class="text-xs text-gray-500 mb-1">RMA Number</p>
                            <p class="text-sm font-semibold text-indigo-600"><?= $row['rma_number']; ?></p>
                          </div>
                        <?php endif; ?>
                        <?php if (!empty($row['end_warranty_date'])): ?>
                          <div>
                            <p class="text-xs text-gray-500 mb-1">End Warranty Date</p>
                            <p class="text-sm font-semibold text-indigo-600"><?= date('d/m/Y', strtotime($row['end_warranty_date'])); ?></p>
                          </div>
                        <?php endif; ?>
                      </div>
                    </div>
                  <?php endif; ?>
                </div>
              <?php endforeach; ?>
            </div>
          <?php elseif (!empty($search)): ?>
            <div class="text-center py-12">
              <i data-feather="search" class="w-16 h-16 mx-auto text-gray-300 mb-4"></i>
              <p class="text-gray-500">Tidak ada hasil untuk "<strong><?= htmlspecialchars($search) ?></strong>"</p>
            </div>
          <?php else: ?>
            <div class="text-center py-12">
              <i data-feather="package" class="w-16 h-16 mx-auto text-gray-300 mb-4"></i>
              <p class="text-gray-500">Tidak ada order part yang sudah dipesan</p>
            </div>
          <?php endif; ?>

          <!-- Modals for Part Marking Update -->
          <?php if (!empty($search) && $orders && is_object($orders) && $orders->num_rows() > 0): ?>
            <?php foreach ($orders->result_array() as $row) : ?>
              <?php
                $safeTrans = preg_replace('/[^A-Za-z0-9_-]/', '-', $row['trans_kode']);
                $modalId = "partModal-{$safeTrans}";
              ?>
              <div class="custom-modal" id="<?= $modalId ?>" style="display: none; background: transparent;">
                <!-- Overlay -->
                <div class="custom-modal-overlay" onclick="closeModal('<?= $modalId ?>')"></div>

                <!-- Modal Content -->
                <div class="custom-modal-content modal__content--md p-0" style="max-height: 95vh;">
                  <div class="bg-white rounded-lg overflow-hidden">

                    <!-- Header -->
                    <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-4 flex items-center justify-between sticky top-0 z-10">
                      <h2 class="font-bold text-lg text-white flex items-center">
                        <i data-feather="package" class="w-5 h-5 mr-2"></i>
                        Update Part Marking
                      </h2>
                      <button type="button" onclick="closeModal('<?= $modalId ?>')" class="text-white hover:text-gray-200">
                        <i data-feather="x" class="w-5 h-5"></i>
                      </button>
                    </div>

                    <!-- Form -->
                    <form method="post" action="<?= site_url('Order/update_part_marking') ?>">
                      <input type="hidden" name="trans_kode" value="<?= $row['trans_kode']; ?>">
                      <input type="hidden" name="is_ordered" value="yes">

                      <!-- Body -->
                      <div class="p-6">
                        <div class="text-center mb-6">
                            <i data-feather="package" class="w-16 h-16 mx-auto text-indigo-500 mb-4"></i>
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">Konfirmasi Update Part Order</h3>
                            <p class="text-gray-600">Apakah Anda yakin ingin mengupdate status part order untuk customer <strong class="font-mono"><?= $row['cos_kode']; ?></strong>?</p>
                          </div>

                        <!-- Order Info -->
                        <div class="bg-gray-50 rounded-lg p-4">
                          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                              <label class="block text-xs font-medium text-gray-500 mb-1">Customer</label>
                              <p class="text-sm font-semibold text-gray-900"><?= $row['cos_nama']; ?></p>
                            </div>
                            <div>
                              <label class="block text-xs font-medium text-gray-500 mb-1">Device</label>
                              <p class="text-sm text-gray-900"><?= $row['device'] ?? 'N/A'; ?> <?= $row['merek'] ?? ''; ?></p>
                            </div>
                            <div>
                              <label class="block text-xs font-medium text-gray-500 mb-1">Keluhan</label>
                              <p class="text-sm text-gray-900"><?= $row['ket_keluhan'] ?? 'N/A'; ?></p>
                            </div>
                            <div>
                              <label class="block text-xs font-medium text-gray-500 mb-1">Current Status</label>
                              <p class="text-sm font-semibold text-gray-900">
                                <?php
                                  $statusColor = $row['is_ordered'] == 'yes' ? 'text-green-600' : 'text-red-600';
                                ?>
                                <span class="<?= $statusColor ?>"><?= $row['is_ordered'] == 'yes' ? 'PART ORDERED' : 'NOT ORDERED'; ?></span>
                              </p>
                            </div>
                          </div>
                        </div>

                        <!-- Input Fields -->
                        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                          <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">RMA Number</label>
                            <input type="text" name="rma_number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent" placeholder="Masukkan RMA Number" value="<?= $row['rma_number'] ?? ''; ?>">
                          </div>
                          <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">End Warranty Date</label>
                            <input type="date" name="end_warranty_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent" value="<?= $row['end_warranty_date'] ?? ''; ?>">
                          </div>
                        </div>
                      </div>

                      <!-- Footer -->
                      <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 border-t border-gray-200">
                        <button type="button" onclick="closeModal('<?= $modalId ?>')"
                                class="px-5 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-100 transition-colors">
                          Batal
                        </button>
                        <button type="submit"
                                class="px-5 py-2 <?php echo ($row['is_ordered'] == 'yes') ? 'bg-gray-500 cursor-not-allowed' : 'bg-indigo-500 hover:bg-indigo-600'; ?> text-white rounded-lg font-medium transition-colors flex items-center"
                                <?php if ($row['is_ordered'] == 'yes') echo 'disabled'; ?>>
                          <i data-feather="check" class="w-4 h-4 mr-2"></i>
                          <?php echo ($row['is_ordered'] == 'yes') ? 'Already Ordered' : 'Update & Mark as Ordered'; ?>
                        </button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Modal Detail Pending -->
  <?php if ($isPending && isset($orders)): ?>
    <?php foreach ($orders->result_array() as $row) : ?>
        <?php
            // Sanitasi trans_kode untuk ID yang aman
            $sanTrans = preg_replace('/[^A-Za-z0-9_]/', '_', $row['trans_kode']);
            $modalId = 'detailModal_' . $sanTrans;
            $formId = 'approvalForm_' . $sanTrans;
            $keteranganId = 'keterangan_section_' . $sanTrans;
            $hasApprovalSection = ($row['tdkn_nama'] == 'Penggantian Sparepart');
        ?>

        <div class="custom-modal" id="<?= $modalId; ?>" style="display: none;">
            <div class="custom-modal-overlay" onclick="closeModal('<?= $modalId; ?>')"></div>
            <div class="custom-modal-content">
                <div class="bg-white rounded-lg overflow-hidden" style="max-height: 90vh; overflow-y: auto;">
                    <!-- Modal Header -->
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 sticky top-0 z-10">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="font-bold text-lg text-gray-800 flex items-center mb-1">
                                    <i data-feather="file-text" class="w-5 h-5 mr-2"></i>
                                    Detail Order - Pending
                                </h2>
                            </div>
                            <button type="button" onclick="closeModal('<?= $modalId; ?>')" class="text-gray-800 hover:text-gray-600 p-2 rounded-full hover:bg-gray-100 transition-colors">
                                <i data-feather="x" class="w-6 h-6"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-6 space-y-6">
                        <!-- Informasi Tindakan -->
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-5 border-2 border-blue-200">
                            <h3 class="font-bold text-gray-800 mb-4 flex items-center text-lg">
                                <i data-feather="clipboard" class="w-5 h-5 mr-2 text-blue-600"></i>
                                Informasi Tindakan
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-white rounded-lg p-4 shadow-sm">
                                    <label class="block text-xs font-semibold text-gray-500 mb-2">Kode Transaksi</label>
                                    <p class="text-sm font-bold text-gray-900 font-mono flex items-center">
                                        <i data-feather="file" class="w-4 h-4 mr-2 text-blue-500"></i>
                                        <?= $row['trans_kode']; ?>
                                    </p>
                                </div>
                                <div class="bg-white rounded-lg p-4 shadow-sm">
                                    <label class="block text-xs font-semibold text-gray-500 mb-2">Nama Tindakan</label>
                                    <p class="text-sm font-bold text-gray-900 flex items-center">
                                        <i data-feather="tool" class="w-4 h-4 mr-2 text-blue-500"></i>
                                        <?= $row['tdkn_nama'] ?? 'N/A'; ?>
                                    </p>
                                </div>
                                <div class="bg-white rounded-lg p-4 shadow-sm">
                                    <label class="block text-xs font-semibold text-gray-500 mb-2">Quantity</label>
                                    <p class="text-sm font-bold text-gray-900 flex items-center">
                                        <i data-feather="package" class="w-4 h-4 mr-2 text-blue-500"></i>
                                        <?= $row['tdkn_qty'] ?? 0; ?> pcs
                                    </p>
                                </div>
                                <div class="bg-white rounded-lg p-4 shadow-sm md:col-span-2">
                                    <label class="block text-xs font-semibold text-gray-500 mb-2">Keterangan</label>
                                    <p class="text-sm text-gray-900"><?= $row['tdkn_ket'] ?? 'Tidak ada keterangan'; ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Customer -->
                        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-5 border-2 border-green-200">
                            <h3 class="font-bold text-gray-800 mb-4 flex items-center text-lg">
                                <i data-feather="user" class="w-5 h-5 mr-2 text-green-600"></i>
                                Informasi Customer
                            </h3>
                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <p class="text-lg font-bold text-gray-900 flex items-center">
                                    <i data-feather="user-check" class="w-5 h-5 mr-2 text-green-500"></i>
                                    <?= $row['cos_nama'] ?? 'N/A'; ?>
                                </p>
                            </div>
                        </div>

                        <!-- Informasi Device -->
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-5 border-2 border-purple-200">
                            <h3 class="font-bold text-gray-800 mb-4 flex items-center text-lg">
                                <i data-feather="smartphone" class="w-5 h-5 mr-2 text-purple-600"></i>
                                Informasi Device
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="bg-white rounded-lg p-4 shadow-sm">
                                    <label class="block text-xs font-semibold text-gray-500 mb-2">Device</label>
                                    <p class="text-sm font-bold text-gray-900 flex items-center">
                                        <i data-feather="tablet" class="w-4 h-4 mr-2 text-purple-500"></i>
                                        <?= $row['device'] ?? 'N/A'; ?>
                                    </p>
                                </div>
                                <div class="bg-white rounded-lg p-4 shadow-sm">
                                    <label class="block text-xs font-semibold text-gray-500 mb-2">Merek</label>
                                    <p class="text-sm font-bold text-gray-900 flex items-center">
                                        <i data-feather="tag" class="w-4 h-4 mr-2 text-purple-500"></i>
                                        <?= $row['merek'] ?? 'N/A'; ?>
                                    </p>
                                </div>
                                <div class="bg-white rounded-lg p-4 shadow-sm">
                                    <label class="block text-xs font-semibold text-gray-500 mb-2">Seri</label>
                                    <p class="text-sm font-bold text-gray-900 flex items-center">
                                        <i data-feather="code" class="w-4 h-4 mr-2 text-purple-500"></i>
                                        <?= $row['seri'] ?? 'N/A'; ?>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Status Garansi -->
                        <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-5 border-2 border-orange-200">
                            <h3 class="font-bold text-gray-800 mb-3 flex items-center text-lg">
                                <i data-feather="shield" class="w-5 h-5 mr-2 text-orange-600"></i>
                                Status Garansi
                            </h3>
                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <span class="px-4 py-2 rounded-full text-sm font-bold inline-flex items-center <?php echo (isset($row['status_garansi']) && ($row['status_garansi'] == 'Aktif' || $row['status_garansi'] == 'Active')) ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                    <i data-feather="<?php echo (isset($row['status_garansi']) && ($row['status_garansi'] == 'Aktif' || $row['status_garansi'] == 'Active')) ? 'check-circle' : 'x-circle'; ?>" class="w-4 h-4 mr-2"></i>
                                    <?php echo $row['status_garansi'] ?? 'Tidak Aktif'; ?>
                                </span>
                            </div>
                        </div>

                        <!-- Informasi Pembayaran -->
                        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-5 border-2 border-green-200">
                            <h3 class="font-bold text-gray-800 mb-4 flex items-center text-lg">
                                <i data-feather="dollar-sign" class="w-5 h-5 mr-2 text-green-600"></i>
                                Informasi Pembayaran
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="bg-white rounded-lg p-4 shadow-sm">
                                    <label class="block text-xs font-semibold text-gray-500 mb-2">Sub Total</label>
                                    <p class="text-sm font-bold text-gray-900">Rp <?= number_format($row['total_subtot'], 0, ',', '.') ?></p>
                                </div>
                                <div class="bg-white rounded-lg p-4 shadow-sm">
                                    <label class="block text-xs font-semibold text-gray-500 mb-2">Telah Dibayarkan</label>
                                    <p class="text-sm font-bold text-gray-900">Rp <?= number_format($row['total_bayar'], 0, ',', '.') ?></p>
                                </div>
                                <div class="bg-white rounded-lg p-4 shadow-sm">
                                    <label class="block text-xs font-semibold text-gray-500 mb-2">Sisa</label>
                                    <p class="text-sm font-bold text-gray-900">Rp <?= number_format($row['total_subtot'] - $row['total_bayar'], 0, ',', '.') ?></p>
                                </div>
                            </div>
                        </div>

                        <form id="<?= $formId; ?>" method="post">
                            <input type="hidden" name="tdkn_kode" value="<?= $row['tdkn_kode'] ?? ''; ?>">
                            <input type="hidden" name="trans_kode" value="<?= $row['trans_kode']; ?>">
                            <input type="hidden" name="has_approval" value="<?php echo $hasApprovalSection ? '1' : '0'; ?>">
                            <input type="hidden" name="sisa" value="<?= $row['total_subtot'] - $row['total_bayar'] ?>">

                        </form>
                    </div>

                    <!-- Modal Footer -->
                    <div class="bg-gray-50 px-6 py-4 flex justify-end items-center border-t border-gray-200 sticky bottom-0">
                        <div class="flex gap-3">
                            <button type="button"
                                    onclick="if(checkDecision('<?= $sanTrans; ?>')) { closeModal('<?= $modalId; ?>'); submitApproval('<?= $sanTrans; ?>', 'submit'); }"
                                    class="px-6 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium transition-colors flex items-center shadow-md">
                                <i data-feather="send" class="w-4 h-4 mr-2"></i>
                                Submit
                            </button>
                        </div>
                    </div>

                    <script>
                    // Enable Enter key to submit the form (except in textarea)
                    document.getElementById('<?= $formId; ?>').addEventListener('keydown', function(e) {
                        if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA') {
                            e.preventDefault();
                            if (checkDecision('<?= $sanTrans; ?>')) {
                                closeModal('<?= $modalId; ?>');
                                submitApproval('<?= $sanTrans; ?>', 'submit');
                            }
                        }
                    });
                    </script>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

  <!-- Modal Detail Waiting Approval -->
  <?php if ($isWaiting && isset($orders)): ?>
    <?php foreach ($orders->result_array() as $row) : ?>
        <?php
            // Sanitasi trans_kode untuk ID yang aman
            $sanTrans = preg_replace('/[^A-Za-z0-9_]/', '_', $row['trans_kode']);
            $modalId = 'detailModal_' . $sanTrans;
            $formId = 'approvalForm_' . $sanTrans;
            $keteranganId = 'keterangan_section_' . $sanTrans;
            $hasApprovalSection = ($row['tdkn_nama'] == 'Penggantian Sparepart');
        ?>

        <div class="custom-modal" id="<?= $modalId; ?>" style="display: none;">
            <div class="custom-modal-overlay" onclick="closeModal('<?= $modalId; ?>')"></div>
            <div class="custom-modal-content">
                <div class="bg-white rounded-lg overflow-hidden" style="max-height: 90vh; overflow-y: auto;">
                    <!-- Modal Header -->
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 sticky top-0 z-10">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="font-bold text-lg text-gray-800 flex items-center mb-1">
                                    <i data-feather="file-text" class="w-5 h-5 mr-2"></i>
                                    Detail Order - Waiting Approval
                                </h2>
                            </div>
                            <button type="button" onclick="closeModal('<?= $modalId; ?>')" class="text-gray-800 hover:text-gray-600 p-2 rounded-full hover:bg-gray-100 transition-colors">
                                <i data-feather="x" class="w-6 h-6"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-6 space-y-6">
                        <!-- Informasi Tindakan -->
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-5 border-2 border-blue-200">
                            <h3 class="font-bold text-gray-800 mb-4 flex items-center text-lg">
                                <i data-feather="clipboard" class="w-5 h-5 mr-2 text-blue-600"></i>
                                Informasi Tindakan
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-white rounded-lg p-4 shadow-sm">
                                    <label class="block text-xs font-semibold text-gray-500 mb-2">Kode Transaksi</label>
                                    <p class="text-sm font-bold text-gray-900 font-mono flex items-center">
                                        <i data-feather="file" class="w-4 h-4 mr-2 text-blue-500"></i>
                                        <?= $row['trans_kode']; ?>
                                    </p>
                                </div>
                                <div class="bg-white rounded-lg p-4 shadow-sm">
                                    <label class="block text-xs font-semibold text-gray-500 mb-2">Nama Tindakan</label>
                                    <p class="text-sm font-bold text-gray-900 flex items-center">
                                        <i data-feather="tool" class="w-4 h-4 mr-2 text-blue-500"></i>
                                        <?= $row['tdkn_nama'] ?? 'N/A'; ?>
                                    </p>
                                </div>
                                <div class="bg-white rounded-lg p-4 shadow-sm">
                                    <label class="block text-xs font-semibold text-gray-500 mb-2">Quantity</label>
                                    <p class="text-sm font-bold text-gray-900 flex items-center">
                                        <i data-feather="package" class="w-4 h-4 mr-2 text-blue-500"></i>
                                        <?= $row['tdkn_qty'] ?? 0; ?> pcs
                                    </p>
                                </div>
                                <div class="bg-white rounded-lg p-4 shadow-sm md:col-span-2">
                                    <label class="block text-xs font-semibold text-gray-500 mb-2">Keterangan</label>
                                    <p class="text-sm text-gray-900"><?= $row['tdkn_ket'] ?? 'Tidak ada keterangan'; ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Customer -->
                        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-5 border-2 border-green-200">
                            <h3 class="font-bold text-gray-800 mb-4 flex items-center text-lg">
                                <i data-feather="user" class="w-5 h-5 mr-2 text-green-600"></i>
                                Informasi Customer
                            </h3>
                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <p class="text-lg font-bold text-gray-900 flex items-center">
                                    <i data-feather="user-check" class="w-5 h-5 mr-2 text-green-500"></i>
                                    <?= $row['cos_nama'] ?? 'N/A'; ?>
                                </p>
                            </div>
                        </div>

                        <!-- Informasi Device -->
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-5 border-2 border-purple-200">
                            <h3 class="font-bold text-gray-800 mb-4 flex items-center text-lg">
                                <i data-feather="smartphone" class="w-5 h-5 mr-2 text-purple-600"></i>
                                Informasi Device
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="bg-white rounded-lg p-4 shadow-sm">
                                    <label class="block text-xs font-semibold text-gray-500 mb-2">Device</label>
                                    <p class="text-sm font-bold text-gray-900 flex items-center">
                                        <i data-feather="tablet" class="w-4 h-4 mr-2 text-purple-500"></i>
                                        <?= $row['device'] ?? 'N/A'; ?>
                                    </p>
                                </div>
                                <div class="bg-white rounded-lg p-4 shadow-sm">
                                    <label class="block text-xs font-semibold text-gray-500 mb-2">Merek</label>
                                    <p class="text-sm font-bold text-gray-900 flex items-center">
                                        <i data-feather="tag" class="w-4 h-4 mr-2 text-purple-500"></i>
                                        <?= $row['merek'] ?? 'N/A'; ?>
                                    </p>
                                </div>
                                <div class="bg-white rounded-lg p-4 shadow-sm">
                                    <label class="block text-xs font-semibold text-gray-500 mb-2">Seri</label>
                                    <p class="text-sm font-bold text-gray-900 flex items-center">
                                        <i data-feather="code" class="w-4 h-4 mr-2 text-purple-500"></i>
                                        <?= $row['seri'] ?? 'N/A'; ?>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Status Garansi -->
                        <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-5 border-2 border-orange-200">
                            <h3 class="font-bold text-gray-800 mb-3 flex items-center text-lg">
                                <i data-feather="shield" class="w-5 h-5 mr-2 text-orange-600"></i>
                                Status Garansi
                            </h3>
                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <span class="px-4 py-2 rounded-full text-sm font-bold inline-flex items-center <?php echo (isset($row['status_garansi']) && ($row['status_garansi'] == 'Aktif' || $row['status_garansi'] == 'Active')) ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                    <i data-feather="<?php echo (isset($row['status_garansi']) && ($row['status_garansi'] == 'Aktif' || $row['status_garansi'] == 'Active')) ? 'check-circle' : 'x-circle'; ?>" class="w-4 h-4 mr-2"></i>
                                    <?php echo $row['status_garansi'] ?? 'Tidak Aktif'; ?>
                                </span>
                            </div>
                        </div>

                        <!-- Part Marking -->
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-5 border-2 border-purple-200">
                            <h3 class="font-bold text-gray-800 mb-4 flex items-center text-lg">
                                <i data-feather="package" class="w-5 h-5 mr-2 text-purple-600"></i>
                                Part Order Marking
                            </h3>
                            <form method="post" action="<?= site_url('Order/update_part_marking') ?>">
                                <input type="hidden" name="trans_kode" value="<?= $row['trans_kode']; ?>">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="bg-white rounded-lg p-4 shadow-sm">
                                        <label class="block text-xs font-semibold text-gray-500 mb-2">RMA Number</label>
                                        <input type="text" name="rma_number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" placeholder="Masukkan RMA Number">
                                    </div>
                                    <div class="bg-white rounded-lg p-4 shadow-sm">
                                        <label class="block text-xs font-semibold text-gray-500 mb-2">End Warranty Date</label>
                                        <input type="date" name="end_warranty_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    </div>
                                    <div class="bg-white rounded-lg p-4 shadow-sm md:col-span-2">
                                        <label class="block text-xs font-semibold text-gray-500 mb-2">Part Ordered</label>
                                        <select name="is_ordered" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                            <option value="">Pilih Status</option>
                                            <option value="yes">Yes - Part sudah diorder</option>
                                            <option value="no">No - Part belum diorder</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mt-4 flex justify-end">
                                    <button type="submit" class="px-6 py-2 bg-purple-500 hover:bg-purple-600 text-white rounded-lg font-medium transition-colors flex items-center">
                                        <i data-feather="save" class="w-4 h-4 mr-2"></i>
                                        Update Marking
                                    </button>
                                </div>
                            </form>
                        </div>

                        <form id="<?= $formId; ?>" method="post">
                            <input type="hidden" name="tdkn_kode" value="<?= $row['tdkn_kode'] ?? ''; ?>">
                            <input type="hidden" name="trans_kode" value="<?= $row['trans_kode']; ?>">
                            <input type="hidden" name="has_approval" value="<?php echo $hasApprovalSection ? '1' : '0'; ?>">
                            <?php if (!$isWaiting): ?>
                            <input type="hidden" name="sisa" value="<?= $row['total_subtot'] - $row['total_bayar'] ?>">
                            <?php endif; ?>

                            <!-- Input Harga Sub Total -->
                            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl p-5 border-2 border-yellow-200">
                                <h3 class="font-bold text-gray-800 mb-4 flex items-center text-lg">
                                    <i data-feather="edit" class="w-5 h-5 mr-2 text-yellow-600"></i>
                                    Input Harga Sub Total
                                </h3>
                                <div class="bg-white rounded-lg p-4 shadow-sm">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Harga Sub Total <span class="text-red-500">*</span></label>
                                    <input type="text" name="subtot" id="subtot_<?= $sanTrans ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent" placeholder="Masukkan harga sub total" oninput="formatRupiah(this)">
                                </div>
                            </div>

                            <!-- Keputusan Approval (hanya untuk Penggantian Sparepart) -->
                            <?php if ($hasApprovalSection): ?>
                            <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-5 border-2 border-red-200">
                                <h3 class="font-bold text-gray-800 mb-4 flex items-center text-lg">
                                    <i data-feather="check-square" class="w-5 h-5 mr-2 text-red-600"></i>
                                    Keputusan Approval
                                </h3>
                                <div class="bg-white rounded-lg p-4 shadow-sm">
                                    <p class="text-sm text-gray-600 mb-3">Pilih keputusan untuk penggantian sparepart:</p>
                                    <div class="space-y-2">
                                        <label class="flex items-center">
                                            <input type="radio" name="barang_tersedia" value="1" class="w-4 h-4 text-red-600 focus:ring-red-500" required>
                                            <span class="ml-2 text-sm font-medium text-gray-700">Barang Tersedia - Lanjutkan Approval</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="barang_tersedia" value="0" class="w-4 h-4 text-red-600 focus:ring-red-500">
                                            <span class="ml-2 text-sm font-medium text-gray-700">Barang Tidak Tersedia - Kirim Pesan Tidak Tersedia</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>

                        </form>
                    </div>

                    <!-- Modal Footer -->
                    <div class="bg-gray-50 px-6 py-4 flex justify-end items-center border-t border-gray-200 sticky bottom-0">
                        <div class="flex gap-3">
                            <button type="button"
                                    onclick="if(checkDecision('<?= $sanTrans; ?>')) { closeModal('<?= $modalId; ?>'); submitApproval('<?= $sanTrans; ?>', 'submit'); }"
                                    class="px-6 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium transition-colors flex items-center shadow-md">
                                <i data-feather="send" class="w-4 h-4 mr-2"></i>
                                Submit
                            </button>
                        </div>
                    </div>

                    <script>
                    // Format Rupiah function
                    function formatRupiah(angka) {
                        var number_string = angka.value.replace(/[^,\d]/g, '').toString(),
                            split = number_string.split(','),
                            sisa = split[0].length % 3,
                            rupiah = split[0].substr(0, sisa),
                            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                        if (ribuan) {
                            separator = sisa ? '.' : '';
                            rupiah += separator + ribuan.join('.');
                        }

                        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                        angka.value = rupiah;
                    }

                    // Enable Enter key to submit the form (except in textarea)
                    document.getElementById('<?= $formId; ?>').addEventListener('keydown', function(e) {
                        if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA') {
                            e.preventDefault();
                            if (checkDecision('<?= $sanTrans; ?>')) {
                                closeModal('<?= $modalId; ?>');
                                submitApproval('<?= $sanTrans; ?>', 'submit');
                            }
                        }
                      });
                      
                      // Loading functions
                      function showLoading() {
                        document.getElementById('loading-overlay').classList.remove('hidden');
                      }
                      
                      // Show loading on pagination clicks
                      document.addEventListener('click', function(e) {
                        if (e.target.closest('button') && e.target.closest('.flex.justify-center.items-center.space-x-2')) {
                          showLoading();
                        }
                      });
                      
                      // Hide loading when page loads
                      window.addEventListener('load', function() {
                        document.getElementById('loading-overlay').classList.add('hidden');
                      });
                      </script>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

  <!-- Modal Konfirmasi Order (Confirmed) -->
<?php if ($isConfirm && isset($orders)): ?>
  <?php foreach ($orders->result_array() as $row) : ?>
    <?php
      $safeTrans = preg_replace('/[^A-Za-z0-9_-]/', '-', $row['trans_kode']);
      $confirmModalId = "confirmModal-{$safeTrans}";
    ?>
    <div class="custom-modal" id="<?= $confirmModalId ?>" style="display: none;">
      <!-- Overlay -->
      <div class="custom-modal-overlay" onclick="closeModal('<?= $confirmModalId ?>')"></div>

      <!-- Modal Content -->
      <div class="custom-modal-content modal__content--xl p-0">
        <div class="bg-white rounded-lg overflow-hidden" style="max-height: 90vh; overflow-y: auto;">

          <!-- Header -->
          <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4 flex items-center justify-between sticky top-0 z-10">
            <h2 class="font-bold text-lg text-white flex items-center">
              <i data-feather="check-circle" class="w-5 h-5 mr-2"></i>
              Konfirmasi Order
            </h2>
            <button type="button" onclick="closeModal('<?= $confirmModalId ?>')" class="text-white hover:text-gray-200">
              <i data-feather="x" class="w-5 h-5"></i>
            </button>
          </div>

          <!-- Form -->
          <form method="post" action="<?= site_url('Order/confirm_order') ?>">
            <input type="hidden" name="trans_kode" value="<?= $row['trans_kode']; ?>">

            <!-- Body -->
            <div class="p-6">
              <!-- Order Info -->
              <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <h3 class="font-semibold text-gray-700 mb-3 flex items-center">
                  <i data-feather="info" class="w-4 h-4 mr-2"></i>
                  Informasi Order
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                  <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Transaksi Kode</label>
                    <p class="text-sm font-semibold text-gray-900 font-mono"><?= $row['trans_kode']; ?></p>
                  </div>
                  <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Nama Customer</label>
                    <p class="text-sm font-semibold text-gray-900"><?= $row['cos_nama']; ?></p>
                  </div>
                  <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Alamat</label>
                    <p class="text-sm text-gray-900"><?= $row['alamat']; ?></p>
                  </div>
                </div>
              </div>

              <!-- Detail Barang yang Diservice -->
              <div class="bg-blue-50 rounded-lg p-4 mb-6">
                <h3 class="font-semibold text-gray-700 mb-4 flex items-center">
                  <i data-feather="package" class="w-4 h-4 mr-2"></i>
                  Detail Barang yang Diservice
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <?php
                  $devices = isset($row['device']) ? explode('|||', $row['device']) : [];
                  $mereks = isset($row['merek']) ? explode('|||', $row['merek']) : [];
                  $seris = isset($row['seri']) ? explode('|||', $row['seri']) : [];
                  $status_garansis = isset($row['status_garansi']) ? explode('|||', $row['status_garansi']) : [];
                  $keluhans = isset($row['keluhan']) ? explode('|||', $row['keluhan']) : [];

                  $num_items = max(count($devices), count($mereks), count($seris), count($status_garansis), count($keluhans));

                  $colors = ['blue', 'green', 'purple', 'orange', 'red'];
                  for ($i = 0; $i < $num_items; $i++) {
                      $color = $colors[$i % count($colors)];
                      $device = $devices[$i] ?? 'N/A';
                      $merek = $mereks[$i] ?? 'N/A';
                      $seri = $seris[$i] ?? 'N/A';
                      $status_garansi = $status_garansis[$i] ?? 'Tidak Aktif';
                      $keluhan = $keluhans[$i] ?? 'Tidak ada keterangan';
                  ?>
                  <div class="bg-white rounded-lg p-4 shadow-sm border-l-4 border-<?= $color ?>-500">
                    <h4 class="font-bold text-gray-800 mb-3 flex items-center">
                      <i data-feather="smartphone" class="w-4 h-4 mr-2 text-<?= $color ?>-600"></i>
                      Barang <?= $i + 1 ?>
                    </h4>
                    <div class="space-y-2">
                      <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-600">Device:</span>
                        <span class="text-sm text-gray-900"><?= $device; ?></span>
                      </div>
                      <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-600">Merek:</span>
                        <span class="text-sm text-gray-900"><?= $merek; ?></span>
                      </div>
                      <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-600">Seri:</span>
                        <span class="text-sm text-gray-900"><?= $seri; ?></span>
                      </div>
                      <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-600">Status Garansi:</span>
                        <span class="px-2 py-1 rounded-full text-xs font-bold
                          <?php echo ($status_garansi == 'Aktif' || $status_garansi == 'Active') ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                          <?= $status_garansi; ?>
                        </span>
                      </div>
                      <div class="pt-2 border-t border-gray-200">
                        <span class="text-sm font-medium text-gray-600 block mb-1">Keterangan Keluhan:</span>
                        <span class="text-sm text-gray-900"><?= $keluhan; ?></span>
                      </div>
                    </div>
                  </div>
                  <?php } ?>
                </div>
              </div>

              <!-- Pilih Karyawan -->
              <?php if (isset($karyawan) && $karyawan->num_rows() > 0): ?>
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                    <i data-feather="users" class="w-4 h-4 mr-2"></i>
                    Pilih Karyawan Petugas
                  </label>
                  <p class="text-xs text-gray-600 mb-3 bg-blue-50 border-l-4 border-blue-500 p-3 rounded">
                    <i data-feather="alert-circle" class="w-4 h-4 inline mr-1"></i>
                    Pastikan karyawan belum memiliki tugas sebelum dikonfirmasi.
                  </p>
                  <div class="border border-gray-300 rounded-lg overflow-hidden">
                    <div class="max-h-60 overflow-y-auto">
                      <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100 sticky top-0">
                          <tr>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Pilih</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Kode</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Nama</th>
                          </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                          <?php foreach ($karyawan->result_array() as $kry): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                              <td class="px-4 py-3 whitespace-nowrap">
                                <input type="radio" name="kry_kode" value="<?= $kry['kry_kode']; ?>"
                                       required class="w-4 h-4 text-green-600 focus:ring-green-500">
                              </td>
                              <td class="px-4 py-3 whitespace-nowrap text-sm font-mono font-medium text-gray-900">
                                <?= $kry['kry_kode']; ?>
                              </td>
                              <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">
                                <?= $kry['kry_nama']; ?>
                              </td>
                            </tr>
                          <?php endforeach; ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              <?php else: ?>
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded">
                  <div class="flex items-center">
                    <i data-feather="alert-triangle" class="w-5 h-5 text-red-600 mr-2"></i>
                    <p class="text-red-700 font-medium">Tidak ada karyawan yang tersedia.</p>
                  </div>
                </div>
              <?php endif; ?>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-6 py-4 pb-10 flex justify-end gap-3 border-t border-gray-200">
              <button type="button" onclick="closeModal('<?= $confirmModalId ?>')"
                      class="px-5 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-100 transition-colors">
                Batal
              </button>
              <?php if (isset($karyawan) && $karyawan->num_rows() > 0): ?>
                <button type="submit"
                        class="px-5 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg font-medium transition-colors flex items-center">
                  <i data-feather="check" class="w-4 h-4 mr-2"></i>
                  Konfirmasi Order
                </button>
              <?php endif; ?>
            </div>
          </form>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
<?php endif; ?>

  <!-- Modal Service Complete (Repairing) -->
<?php if ($isRepairing && isset($orders)): ?>
  <?php foreach ($orders->result_array() as $row) : ?>
    <?php
      $safeTrans = preg_replace('/[^A-Za-z0-9_-]/', '-', $row['trans_kode']);
      $repairingModalId = "repairingModal-{$safeTrans}";
      $isLegacy = isset($row['source_table']) && $row['source_table'] == 'transaksi';
    ?>
    <div class="custom-modal" id="<?= $repairingModalId ?>" style="display: none;">
      <!-- Overlay -->
      <div class="custom-modal-overlay" onclick="closeModal('<?= $repairingModalId ?>')"></div>

      <!-- Modal Content -->
      <div class="custom-modal-content modal__content--md p-0">
        <div class="bg-white rounded-lg overflow-hidden">

          <!-- Header -->
          <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4 flex items-center justify-between">
            <h2 class="font-bold text-lg text-white flex items-center">
              <i data-feather="settings" class="w-5 h-5 mr-2"></i>
              Service Complete
            </h2>
            <button type="button" onclick="closeModal('<?= $repairingModalId ?>')" class="text-white hover:text-gray-200">
              <i data-feather="x" class="w-5 h-5"></i>
            </button>
          </div>

          <!-- Form -->
          <form method="post" action="<?= site_url('Order/service_complete') ?>">
            <input type="hidden" name="trans_kode" value="<?= $row['trans_kode']; ?>">

            <!-- Body -->
            <div class="p-6">
              <div class="text-center mb-6">
                  <i data-feather="settings" class="w-16 h-16 mx-auto text-orange-500 mb-4"></i>
                  <h3 class="text-lg font-semibold text-gray-800 mb-2">Konfirmasi Service Selesai</h3>
                  <p class="text-gray-600">Apakah Anda yakin service untuk customer <strong class="font-mono"><?= $row['cos_kode']; ?></strong> sudah selesai?</p>
                </div>

              <!-- Pilih Status Service -->
              <div class="bg-blue-50 rounded-lg p-4 mb-6">
                <h3 class="font-semibold text-gray-700 mb-4 flex items-center">
                  <i data-feather="check-circle" class="w-4 h-4 mr-2"></i>
                  Pilih Status Service
                </h3>
                <div class="space-y-2">
                  <label class="flex items-center">
                    <input type="radio" name="service_status" value="completed" class="w-4 h-4 text-green-600 focus:ring-green-500" checked required>
                    <span class="ml-2 text-sm font-medium text-gray-700">Service Complete - Service berhasil diselesaikan</span>
                  </label>
                  <label class="flex items-center">
                    <input type="radio" name="service_status" value="failed" class="w-4 h-4 text-red-600 focus:ring-red-500">
                    <span class="ml-2 text-sm font-medium text-gray-700">Service Failed - Service gagal atau tidak dapat diperbaiki</span>
                  </label>
                </div>
              </div>

              <!-- Order Info -->
              <div class="bg-gray-50 rounded-lg p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Customer</label>
                    <p class="text-sm font-semibold text-gray-900"><?= $row['cos_nama']; ?></p>
                  </div>
                  <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Status Pembayaran</label>
                    <p class="text-sm font-semibold text-gray-900">
                      <?php
                        $paymentStatus = isset($row['payment_status']) && $row['payment_status'] == 'Lunas' ? 'LUNAS' : 'DP';
                        $statusColor = $paymentStatus == 'LUNAS' ? 'text-green-600' : 'text-blue-600';
                      ?>
                      <span class="<?= $statusColor ?>"><?= $paymentStatus ?></span>
                    </p>
                  </div>
                  <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Device</label>
                    <p class="text-sm text-gray-900"><?= $row['device'] ?? 'N/A'; ?></p>
                  </div>
                  <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Merek</label>
                    <p class="text-sm text-gray-900"><?= $row['merek'] ?? 'N/A'; ?></p>
                  </div>
                  <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Keluhan</label>
                    <p class="text-sm text-gray-900"><?= $row['ket_keluhan'] ?? 'N/A'; ?></p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 border-t border-gray-200">
              <button type="button" onclick="closeModal('<?= $repairingModalId ?>')"
                      class="px-5 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-100 transition-colors">
                Batal
              </button>
              <button type="submit"
                      class="px-5 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg font-medium transition-colors flex items-center service-submit-btn">
                <i data-feather="check" class="w-4 h-4 mr-2"></i>
                <span class="service-submit-text">Ya, Service Selesai</span>
              </button>
            </div>

            <script>
            // Update form action and button text based on radio selection
            document.querySelectorAll('input[name="service_status"]').forEach(radio => {
              radio.addEventListener('change', function() {
                const form = this.closest('form');
                const submitBtn = form.querySelector('.service-submit-btn');
                const submitText = form.querySelector('.service-submit-text');
                const icon = submitBtn.querySelector('i');

                if (this.value === 'failed') {
                  form.action = '<?= site_url('Order/service_failed') ?>';
                  submitBtn.className = submitBtn.className.replace('bg-orange-500 hover:bg-orange-600', 'bg-red-500 hover:bg-red-600');
                  submitText.textContent = 'Ya, Service Gagal';
                  icon.className = 'w-4 h-4 mr-2 feather feather-x-circle';
                } else {
                  form.action = '<?= site_url('Order/service_complete') ?>';
                  submitBtn.className = submitBtn.className.replace('bg-red-500 hover:bg-red-600', 'bg-orange-500 hover:bg-orange-600');
                  submitText.textContent = 'Ya, Service Selesai';
                  icon.className = 'w-4 h-4 mr-2 feather feather-check';
                }

                // Re-render feather icons
                if (typeof feather !== 'undefined') {
                  feather.replace();
                }
              });
            });
            </script>
          </form>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
<?php endif; ?>
</div>

<!-- Loading Overlay -->
<div id="loading-overlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
  <div class="bg-white rounded-lg p-6 flex items-center space-x-4 shadow-xl">
    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
    <span class="text-gray-700 font-medium">Loading...</span>
  </div>
</div>

<style>
  .line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }

  /* Smooth scrolling for modal */
  .modal__content {
    max-height: 90vh;
  }

  /* Fallback modal style (jika Midone JS tidak aktif) */
  .modal {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 9999;
    background: rgba(0,0,0,.5);
    padding: 1rem;
    align-items: center;
    justify-content: center;
  }
  .modal.open { display: flex !important; }

  /* Custom modal styles */
  .custom-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1001;
    display: none;
    align-items: center;
    justify-content: center;
  }

  .custom-modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
  }

  .custom-modal-content {
    position: relative;
    background: white;
    border-radius: 8px;
    max-width: 85%;
    max-height: 85%;
    overflow: hidden;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
  }

 .modal__content--xl {
   max-width: 1000px;
 }

  /* Custom scrollbar */
  .overflow-y-auto::-webkit-scrollbar {
    width: 8px;
  }
  .overflow-y-auto::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
  }
  .overflow-y-auto::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 10px;
  }
  .overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #555;
  }

  /* Ensure SweetAlert popup is on top */
  .swal2-container {
    z-index: 10000 !important;
  }

  /* Notification Dropdown */
  .notification-container {
    position: relative;
  }

  .notification-dropdown {
    position: absolute;
    top: 100%;
    right: 0;
    width: 350px;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    z-index: 9999;
    max-height: 400px;
    overflow: hidden;
    display: none;
  }

  .notification-dropdown.active {
    display: block;
  }

  .notification-header {
    padding: 16px;
    border-bottom: 1px solid #e5e7eb;
    background: #f9fafb;
  }

  .notification-header h4 {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
    color: #374151;
  }

  .notification-list {
    max-height: 300px;
    overflow-y: auto;
  }

  .notification-item {
    padding: 12px 16px;
    border-bottom: 1px solid #f3f4f6;
    transition: background-color 0.2s;
  }

  .notification-item:hover {
    background: #f9fafb;
  }

  .notification-item:last-child {
    border-bottom: none;
  }

  .notification-content p {
    margin: 0 0 4px 0;
    font-size: 14px;
    color: #374151;
  }

  .notification-content small {
    color: #6b7280;
    font-size: 12px;
  }

  .notification-empty {
    padding: 20px;
    text-align: center;
    color: #6b7280;
    font-style: italic;
  }

  /* Override to allow dropdown to show */
  .page-header {
    overflow: visible;
  }

  .badge-count {
    position: absolute;
    top: -2px;
    right: -2px;
    width: 16px;
    height: 16px;
    background: #ef4444;
    color: white;
    border-radius: 50%;
    display: none;
    align-items: center;
    justify-content: center;
    font-size: 9px;
    font-weight: 600;
    border: 1px solid white;
  }
</style>

<script>
// Buka modal custom (global)
window.openModal = function(id) {
  const modal = document.getElementById(id);
  if (!modal) {
    console.warn('Modal not found:', id);
    return;
  }
  modal.style.display = 'flex'; // tampilkan
  document.body.classList.add('overflow-hidden');

  // Render ulang feather icon
  if (typeof feather !== 'undefined') {
    setTimeout(() => feather.replace(), 0);
  }
};

// Tutup modal custom (global)
window.closeModal = function(id) {
  const modal = document.getElementById(id);
  if (!modal) return;
  modal.style.display = 'none';
  document.body.classList.remove('overflow-hidden');
};

// Toggling sections (pakai id: keterangan_section_<transIdSanitized> and harga_section_<transIdSanitized>)
window.toggleApprovalAction = function(radio, transIdSanitized) {
  const keteranganSection = document.getElementById('keterangan_section_' + transIdSanitized);
  const hargaSection = document.getElementById('harga_section_' + transIdSanitized);

  // Harga section selalu ditampilkan karena wajib diisi
  if (hargaSection) {
    hargaSection.style.display = 'block';
  }
  // Keterangan section hanya tampil jika barang tidak tersedia
  if (keteranganSection) {
    keteranganSection.style.display = (radio.value === '0') ? 'block' : 'none';
  }
};

// Check if approval decision is chosen
window.checkDecision = function(transIdSanitized) {
  const form = document.getElementById('approvalForm_' + transIdSanitized);
  const hasSisa = form.querySelector('input[name="sisa"]');
  if (hasSisa) {
    return true; // No check needed for sisa
  }
  const hasApproval = form.querySelector('input[name="has_approval"]').value === '1';
  if (hasApproval) {
    const barangTersedia = form.querySelector('input[name="barang_tersedia"]:checked');
    if (!barangTersedia) {
      if (typeof Swal !== 'undefined') {
        Swal.fire({
          icon: 'warning',
          title: 'Peringatan',
          text: 'Silakan pilih keputusan approval terlebih dahulu!',
          confirmButtonColor: '#3B82F6'
        });
      } else {
        alert('Silakan pilih keputusan approval terlebih dahulu!');
      }
      return false;
    }
  }
  return true;
};

// Submit approval (pakai id form: approvalForm_<transIdSanitized>)
window.submitApproval = function(transIdSanitized, action) {
  const formId = 'approvalForm_' + transIdSanitized;
  const form = document.getElementById(formId);
  if (!form) {
    console.error('Form not found:', formId);
    return;
  }

  const formData = new FormData(form);
  const hasApproval = formData.get('has_approval');
  const barangTersedia = formData.get('barang_tersedia');

  let actionUrl = '';

  if (formData.has('subtot')) {
    if (hasApproval === '1' && barangTersedia === '0') {
      actionUrl = '<?= site_url('Order/inform_unavailable') ?>'; // Kirim pesan tidak tersedia
    } else {
      actionUrl = '<?= site_url('Order/approve_order') ?>'; // Update subtot dan status ke approved
    }
  } else if (formData.has('sisa')) {
    actionUrl = '<?= site_url('Order/update_trans_total') ?>'; // Update trans_total with sisa
  }

  // Submit langsung tanpa konfirmasi
  const submitForm = document.createElement('form');
  submitForm.method = 'POST';
  submitForm.action = actionUrl;

  for (const [k, v] of formData.entries()) {
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = k;
    input.value = v;
    submitForm.appendChild(input);
  }
  document.body.appendChild(submitForm);
  submitForm.submit();
};

// Confirm reject
window.confirmReject = function(transKode) {
  const confirmText = 'Apakah Anda yakin ingin menolak order ini?';
  const doReject = () => {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?= site_url('Order/reject_order/') ?>' + transKode;
    document.body.appendChild(form);
    form.submit();
  };

  if (typeof Swal !== 'undefined') {
    Swal.fire({
      title: 'Konfirmasi Penolakan',
      text: confirmText,
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#EF4444',
      cancelButtonColor: '#6B7280',
      confirmButtonText: 'Ya, Tolak!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) doReject();
    });
  } else {
    if (confirm(confirmText)) doReject();
  }
};

// Send follow up for completed orders
window.sendFollowUp = function(hp, nama, cos_kode, merek, device) {
  // Format phone number
  if (hp.startsWith('0')) {
    hp = '62' + hp.substring(1);
  }
  hp = hp.replace(/\D/g, '');

  let message = `SALAM SATU HATI,\n\nHALO ${nama},\n\nService unit ${device} ${merek} Anda dengan kode ${cos_kode} sudah SELESAI diperbaiki.\n\nSilakan ambil unit Anda di Azzahra Computer - Authorized Service Center.\n\nJika ada pertanyaan, hubungi kami di 085942001720.\n\nTerima Kasih atas kepercayaan Anda! 😊`;

  const waUrl = `https://wa.me/${hp}?text=${encodeURIComponent(message)}`;
  window.open(waUrl, '_blank');

  // Update follow up count (optional, bisa ditambahkan jika perlu tracking)
  // window.location.href = '<?= site_url('Order/mark_followup_sent/') ?>' + trans_kode;
};

// Send follow up for failed orders
window.sendFollowUpFailed = function(hp, nama, cos_kode, merek, device) {
  // Format phone number
  if (hp.startsWith('0')) {
    hp = '62' + hp.substring(1);
  }
  hp = hp.replace(/\D/g, '');

  let message = `SALAM SATU HATI,\n\nHALO ${nama},\n\nTerdapat kendala dalam melakukan service untuk unit ${device} ${merek} Anda dengan kode ${cos_kode}.\n\nMohon maaf atas ketidaknyamanan ini. Silakan Ambil unit anda  di Azzahra Computer - Authorized Service Center.\n\nJika ada pertanyaan, hubungi kami di 085942001720.\n\nTerima Kasih atas kepercayaan Anda! 😊`;

  const waUrl = `https://wa.me/${hp}?text=${encodeURIComponent(message)}`;
  window.open(waUrl, '_blank');

  // Update follow up count (optional, bisa ditambahkan jika perlu tracking)
  // window.location.href = '<?= site_url('Order/mark_followup_sent/') ?>' + trans_kode;
};

// ESC untuk menutup modal yang sedang terbuka
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    const opened = document.querySelector('.custom-modal[style*="display: flex"]');
    if (opened) window.closeModal(opened.id);
  }
});

// Open standard modal (for pending confirm modals)
window.openStandardModal = function(id) {
  const modal = document.getElementById(id);
  if (!modal) {
    console.warn('Modal not found:', id);
    return;
  }
  modal.classList.add('open');
  document.body.classList.add('overflow-hidden');

  // Render ulang feather icon
  if (typeof feather !== 'undefined') {
    setTimeout(() => feather.replace(), 0);
  }
};

// Close standard modal (for pending confirm modals)
window.closeStandardModal = function(id) {
  const modal = document.getElementById(id);
  if (!modal) return;
  modal.classList.remove('open');
  document.body.classList.remove('overflow-hidden');
};

// Toggle notification dropdown
function toggleNotificationDropdown() {
  const dropdown = document.getElementById('notificationDropdown');
  const isActive = dropdown.classList.toggle('active');
  if (isActive) {
    // Mark as read
    const today = new Date().toISOString().split('T')[0];
    const user = window.currentUser || 'default';
    localStorage.setItem('notification_read_date_' + user, today);
    localStorage.setItem('notification_read_count_' + user, window.todayOrderCount || 0);
    const badge = document.querySelector('.badge-count');
    if (badge) badge.style.display = 'none';
  }
}

// Close dropdown when clicking outside
document.addEventListener('click', function(e) {
  const container = document.querySelector('.notification-container');
  const dropdown = document.getElementById('notificationDropdown');
  if (!container.contains(e.target)) {
    dropdown.classList.remove('active');
  }
});

// Inisialisasi feather
document.addEventListener('DOMContentLoaded', function() {
  if (typeof feather !== 'undefined') feather.replace();

  // Handle notification badge
  const today = new Date().toISOString().split('T')[0];
  const user = window.currentUser || 'default';
  const badge = document.querySelector('.badge-count');
  const lastReadDate = localStorage.getItem('notification_read_date_' + user);
  const lastReadCount = parseInt(localStorage.getItem('notification_read_count_' + user)) || 0;
  const currentCount = window.todayOrderCount || 0;

  if (badge && currentCount > 0) {
    if (!(lastReadDate === today && lastReadCount >= currentCount)) {
      badge.style.display = 'flex';
    }
  }

  // Handle flashdata alerts
  var suksesMsg = document.querySelector('.sukses')?.getAttribute('data-sukses') || '';
  var gagalMsg = document.querySelector('.gagal')?.getAttribute('data-gagal') || '';

  if (typeof Swal !== 'undefined') {
    if (gagalMsg) {
      Swal.fire({
        icon: 'error',
        title: 'Gagal',
        text: gagalMsg,
        confirmButtonColor: '#EF4444'
      });
      // Clear the data attribute to prevent re-showing on refresh
      document.querySelector('.gagal').setAttribute('data-gagal', '');
    } else if (suksesMsg) {
      Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: suksesMsg,
        timer: 1600,
        showConfirmButton: false
      });
      // Clear the data attribute to prevent re-showing on refresh
      document.querySelector('.sukses').setAttribute('data-sukses', '');
    }
  } else {
    if (gagalMsg) {
      alert(gagalMsg);
      // Clear the data attribute to prevent re-showing on refresh
      document.querySelector('.gagal').setAttribute('data-gagal', '');
    } else if (suksesMsg) {
      alert(suksesMsg);
      // Clear the data attribute to prevent re-showing on refresh
      document.querySelector('.sukses').setAttribute('data-sukses', '');
    }
  }
});
</script>

<?php $this->load->view('Template/footer'); ?>
