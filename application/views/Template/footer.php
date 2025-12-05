
            <!-- END: Content -->
            <!-- BEGIN: JS Assets-->
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>
            <script src="<?php echo base_url(); ?>assets/template/beck/dist/js/app.js"></script>
            <!-- SweetAlert -->
            <script src="<?php echo base_url();?>assets/file/alert/alertscript.js"></script>
            <!-- myjs -->
            <script src="<?php echo base_url();?>assets/file/js/rupiah.js"></script>
            <script src="<?php echo base_url();?>assets/file/js/modal.js"></script>
            <!-- Set sessionStorage flag for login popup -->
            <?php if($this->session->userdata('just_logged_in')): ?>
            <script>
                sessionStorage.setItem('just_logged_in', 'true');
                <?php $this->session->unset_userdata('just_logged_in'); // Clear the session flag ?>
            </script>
            <?php endif; ?>

            <!-- Feather Icons -->
            <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
            <script>
                // Init Feather Icons
                if (typeof feather !== 'undefined') {
                    feather.replace();
                    
                    // Re-init setiap 1 detik untuk icon yang dimuat via AJAX
                    setInterval(function() {
                        feather.replace();
                    }, 1000);
                }
            </script>
            
            <script>
                // Initialize Feather Icons
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
            </script>
            <!-- END: JS Assets-->
             

            <!-- Dashboard Scripts -->
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize Feather Icons
                feather.replace();
                
                // Initialize Weekly Chart (only if not already initialized by page-specific script)
                const ctx = document.getElementById('weeklyChart');
                if (ctx && !ctx.chart) {
                    <?php
                    // Prepare weekly data
                    $weekly_labels = [];
                    $weekly_data = [];
                    if (isset($weekly) && $weekly->num_rows() > 0) {
                        foreach ($weekly->result_array() as $w) {
                            $weekly_labels[] = "'" . $w['day'] . "'";
                            $weekly_data[] = $w['total'];
                        }
                    } else {
                        // Default data jika kosong
                        $weekly_labels = ["'Mon'", "'Tue'", "'Wed'", "'Thu'", "'Fri'", "'Sat'", "'Sun'"];
                        $weekly_data = [12, 19, 15, 25, 22, 30, 28];
                    }
                    ?>

                    ctx.chart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: [<?= implode(',', $weekly_labels); ?>],                            
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
                                    bodyFont: { size: 13 }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: 'rgba(0, 0, 0, 0.05)'
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
                
                // Animate traffic progress bars
                setTimeout(() => {
                    document.querySelectorAll('.traffic-progress-bar').forEach(bar => {
                        const width = bar.style.width;
                        bar.style.width = '0';
                        setTimeout(() => bar.style.width = width, 100);
                    });
                }, 500);
                
                // Filter buttons functionality
                document.querySelectorAll('.filter-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                        this.classList.add('active');
                    });
                });
            });
            </script>

            
    <!-- Script for Sidebar and User Dropdown -->
    <script>
        // Toggle User Dropdown
        function toggleUserDropdown() {
            const userProfile = document.querySelector('.user-profile');
            const dropdownMenu = document.getElementById('userDropdownMenu');

            userProfile.classList.toggle('active');
            dropdownMenu.classList.toggle('active');

            // Refresh feather icons
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        }

        // Toggle Laporan Dropdown
        function toggleLaporanDropdown() {
            const laporanToggle = document.querySelector('.laporan-toggle');
            const dropdownMenu = document.getElementById('laporanDropdownMenu');

            laporanToggle.classList.toggle('active');
            dropdownMenu.classList.toggle('active');

            // Refresh feather icons
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const sidebarFooter = document.querySelector('.sidebar-footer');
            const userProfile = document.querySelector('.user-profile');
            const userDropdownMenu = document.getElementById('userDropdownMenu');

            if (!sidebarFooter.contains(event.target)) {
                userProfile.classList.remove('active');
                userDropdownMenu.classList.remove('active');
            }
        });

        // Close laporan dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const navMenu = document.querySelector('.nav-menu');
            const laporanToggle = document.querySelector('.laporan-toggle');
            const laporanDropdownMenu = document.getElementById('laporanDropdownMenu');

            if (!navMenu.contains(event.target)) {
                laporanToggle.classList.remove('active');
                laporanDropdownMenu.classList.remove('active');
            }
        });

        // Toggle Sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const userProfile = document.querySelector('.user-profile');
            const userDropdownMenu = document.getElementById('userDropdownMenu');
            const laporanToggle = document.querySelector('.laporan-toggle');
            const laporanDropdownMenu = document.getElementById('laporanDropdownMenu');

            sidebar.classList.toggle('collapsed');

            // Close dropdown when collapsing sidebar
            if (sidebar.classList.contains('collapsed')) {
                userProfile.classList.remove('active');
                userDropdownMenu.classList.remove('active');
                laporanToggle.classList.remove('active');
                laporanDropdownMenu.classList.remove('active');
            }

            localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
        }

        // Toggle Mobile Sidebar
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('mobile-active');
            overlay.classList.toggle('active');
        }

        // Remember sidebar state
        window.addEventListener('DOMContentLoaded', () => {
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (isCollapsed && window.innerWidth > 1024) {
                document.getElementById('sidebar').classList.add('collapsed');
            }

            // Initialize feather icons
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        });
    </script>

        </body>
    </html>