<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BillBook - Premium Billing</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>">
</head>

<body>

    <!-- Mobile Overlay -->
    <div class="mobile-overlay" id="mobileOverlay" onclick="toggleMobileMenu()"></div>

    <div class="sidebar" id="sidebar">
        <div class="brand">
            <i class="fas fa-bolt" style="margin-right: 10px;"></i> BillBook
        </div>
        <div class="nav-links">
            <a href="<?php echo site_url('dashboard'); ?>"
                class="nav-item <?php echo ($this->uri->segment(1) == 'dashboard' || $this->uri->segment(1) == '') ? 'active' : ''; ?>">
                <div class="nav-icon"><i class="fas fa-home"></i></div> Dashboard
            </a>
            <a href="<?php echo site_url('customers'); ?>"
                class="nav-item <?php echo ($this->uri->segment(1) == 'customers') ? 'active' : ''; ?>">
                <div class="nav-icon"><i class="fas fa-users"></i></div> Customers
            </a>
            <a href="<?php echo site_url('items'); ?>"
                class="nav-item <?php echo ($this->uri->segment(1) == 'items') ? 'active' : ''; ?>">
                <div class="nav-icon"><i class="fas fa-box"></i></div> Items
            </a>
            <!-- Inventory Management Dropdown -->
            <?php
            $inventory_segments = ['suppliers', 'purchases', 'inventory_reports'];
            $is_inventory_active = in_array($this->uri->segment(1), $inventory_segments);
            ?>
            <div class="nav-item nav-item-parent <?php echo $is_inventory_active ? 'active' : ''; ?>"
                onclick="toggleSubmenu('inventorySubmenu')">
                <div style="display: flex; align-items: center;">
                    <div class="nav-icon"><i class="fas fa-warehouse"></i></div> Inventory
                </div>
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="nav-submenu <?php echo $is_inventory_active ? 'show' : ''; ?>" id="inventorySubmenu">
                <a href="<?php echo site_url('suppliers'); ?>"
                    class="nav-item <?php echo ($this->uri->segment(1) == 'suppliers') ? 'active' : ''; ?>">
                    Suppliers
                </a>
                <a href="<?php echo site_url('purchases'); ?>"
                    class="nav-item <?php echo ($this->uri->segment(1) == 'purchases') ? 'active' : ''; ?>">
                    Purchases
                </a>
                <a href="<?php echo site_url('inventory_reports/stock'); ?>"
                    class="nav-item <?php echo ($this->uri->segment(1) == 'inventory_reports') ? 'active' : ''; ?>">
                    All Inventory
                </a>
            </div>
            <a href="<?php echo site_url('invoices'); ?>"
                class="nav-item <?php echo ($this->uri->segment(1) == 'invoices') ? 'active' : ''; ?>">
                <div class="nav-icon"><i class="fas fa-file-invoice-dollar"></i></div> Invoices
            </a>
            <a href="<?php echo site_url('payments'); ?>"
                class="nav-item <?php echo ($this->uri->segment(1) == 'payments') ? 'active' : ''; ?>">
                <div class="nav-icon"><i class="fas fa-money-bill-wave"></i></div> Payments
            </a>
            <a href="<?php echo site_url('reports'); ?>"
                class="nav-item <?php echo ($this->uri->segment(1) == 'reports') ? 'active' : ''; ?>">
                <div class="nav-icon"><i class="fas fa-chart-bar"></i></div> Reports
            </a>
            <!-- Expenses Dropdown -->
            <?php
            $expense_segments = ['expenses', 'reports/profit_loss'];
            $is_expense_active = in_array($this->uri->segment(1), ['expenses']) || ($this->uri->segment(1) == 'reports' && $this->uri->segment(2) == 'profit_loss');
            ?>
            <div class="nav-item nav-item-parent <?php echo $is_expense_active ? 'active' : ''; ?>"
                onclick="toggleSubmenu('expenseSubmenu')">
                <div style="display: flex; align-items: center;">
                    <div class="nav-icon"><i class="fas fa-wallet"></i></div> Purchase & Expenses
                </div>
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="nav-submenu <?php echo $is_expense_active ? 'show' : ''; ?>" id="expenseSubmenu">
                <a href="<?php echo site_url('expenses/create'); ?>"
                    class="nav-item <?php echo ($this->uri->segment(1) == 'expenses' && $this->uri->segment(2) == 'create') ? 'active' : ''; ?>">
                    Record Expense
                </a>
                <a href="<?php echo site_url('expenses'); ?>"
                    class="nav-item <?php echo ($this->uri->segment(1) == 'expenses' && $this->uri->segment(2) == '') ? 'active' : ''; ?>">
                    All Expenses
                </a>
                <a href="<?php echo site_url('reports/profit_loss'); ?>"
                    class="nav-item <?php echo ($this->uri->segment(1) == 'reports' && $this->uri->segment(2) == 'profit_loss') ? 'active' : ''; ?>">
                    Profit & Loss
                </a>
            </div>

            <a href="<?php echo site_url('settings'); ?>"
                class="nav-item <?php echo ($this->uri->segment(1) == 'settings') ? 'active' : ''; ?>">
                <div class="nav-icon"><i class="fas fa-cog"></i></div> Settings
            </a>
        </div>
    </div>

    <div class="main-content">
        <div class="header">
            <div style="display: flex; align-items: center; gap: 16px;">
                <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="page-title"><?php echo isset($title) ? $title : 'Dashboard'; ?></div>
            </div>
            <div class="user-dropdown">
                <button class="user-button" onclick="toggleDropdown()">
                    <?php if ($this->session->userdata('user_photo')): ?>
                        <img src="<?php echo $this->session->userdata('user_photo'); ?>" alt="Profile"
                            style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover;">
                    <?php else: ?>
                        <div class="user-avatar">
                            <?php echo strtoupper(substr($this->session->userdata('user_name'), 0, 1)); ?>
                        </div>
                    <?php endif; ?>
                    <span><?php echo $this->session->userdata('user_name'); ?></span>
                    <i class="fas fa-chevron-down" style="font-size: 0.8rem;"></i>
                </button>
                <div class="dropdown-menu" id="userDropdown">
                    <a href="<?php echo site_url('profile'); ?>" class="dropdown-item">
                        <i class="fas fa-user"></i> My Profile
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="<?php echo site_url('auth/logout'); ?>" class="dropdown-item">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
        </div>
        <script>
            // Toggle mobile menu
            function toggleMobileMenu() {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('mobileOverlay');
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
            }

            // Toggle user dropdown
            function toggleDropdown() {
                document.getElementById('userDropdown').classList.toggle('show');
            }

            // Close dropdown when clicking outside
            window.onclick = function (event) {
                if (!event.target.matches('.user-button') && !event.target.closest('.user-button')) {
                    var dropdowns = document.getElementsByClassName("dropdown-menu");
                    for (var i = 0; i < dropdowns.length; i++) {
                        dropdowns[i].classList.remove('show');
                    }
                }
            }

            // Close mobile menu when clicking nav item
            document.querySelectorAll('.nav-item').forEach(item => {
                if (!item.classList.contains('nav-item-parent')) {
                    item.addEventListener('click', function () {
                        if (window.innerWidth <= 768) {
                            toggleMobileMenu();
                        }
                    });
                }
            });

            function toggleSubmenu(id) {
                const submenu = document.getElementById(id);
                const parent = submenu.previousElementSibling;
                submenu.classList.toggle('show');
                parent.classList.toggle('active');
            }
        </script>
        <div class="content">