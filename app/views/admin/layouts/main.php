<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Admin - BookStore' ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/admin.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</head>

<body class="admin-body">
    <!-- Sidebar -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-header">
            <a href="<?= BASE_URL ?>/admin" class="sidebar-logo">
                <div class="logo-icon" style="background: transparent; border-radius: 10px;"><img
                        src="<?= BASE_URL ?>/icon3.png" alt="Logo"
                        style="width: 100%; height: 100%; object-fit: cover; border-radius: 10px;"></div>
                Book4U
            </a>
            <span class="sidebar-badge">ADMIN</span>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">Menu chính</div>
            <?php
            $currentUrl = $_GET['url'] ?? 'admin';
            $menuItems = [
                ['url' => 'admin/dashboard', 'icon' => 'fas fa-chart-pie', 'label' => 'Dashboard', 'match' => 'admin/dashboard,admin'],
                ['url' => 'admin/books', 'icon' => 'fas fa-book', 'label' => 'Quản lý sách', 'match' => 'admin/books'],
                ['url' => 'admin/categories', 'icon' => 'fas fa-folder', 'label' => 'Danh mục', 'match' => 'admin/categories'],
                ['url' => 'admin/orders', 'icon' => 'fas fa-shopping-bag', 'label' => 'Đơn hàng', 'match' => 'admin/orders'],
                ['url' => 'admin/users', 'icon' => 'fas fa-users', 'label' => 'Người dùng', 'match' => 'admin/users'],
            ];
            foreach ($menuItems as $item):
                $matches = explode(',', $item['match']);
                $isActive = false;
                foreach ($matches as $m) {
                    $m = trim($m);
                    if ($m === 'admin') {
                        if (rtrim($currentUrl, '/') === 'admin')
                            $isActive = true;
                    } elseif (strpos($currentUrl, $m) === 0) {
                        $isActive = true;
                    }
                }
                ?>
                <a href="<?= BASE_URL ?>/<?= $item['url'] ?>" class="nav-item <?= $isActive ? 'active' : '' ?>">
                    <i class="<?= $item['icon'] ?>"></i>
                    <span><?= $item['label'] ?></span>
                </a>
            <?php endforeach; ?>

            <div class="nav-section" style="margin-top: 24px;">Khác</div>
            <a href="<?= BASE_URL ?>/" class="nav-item">
                <i class="fas fa-store"></i><span>Về cửa hàng</span>
            </a>
            <a href="<?= BASE_URL ?>/logout" class="nav-item">
                <i class="fas fa-sign-out-alt"></i><span>Đăng xuất</span>
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="admin-main">
        <!-- Top Bar -->
        <header class="admin-topbar">
            <button class="sidebar-toggle" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <div class="topbar-right">
                <div class="topbar-user">
                    <div class="user-avatar-sm"
                        style="display:flex;align-items:center;justify-content:center;overflow:hidden;">
                        <?php if (!empty($_SESSION['user_avatar'])): ?>
                            <img src="<?= BASE_URL . $_SESSION['user_avatar'] ?>" alt="Avatar"
                                style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
                        <?php else: ?>
                            <?= strtoupper(mb_substr($_SESSION['user_name'] ?? 'A', 0, 1)) ?>
                        <?php endif; ?>
                    </div>
                    <span><?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?></span>
                </div>
            </div>
        </header>

        <!-- Flash Messages -->
        <?php if (isset($_SESSION['flash_success'])): ?>
            <div class="admin-alert admin-alert-success">
                <i class="fas fa-check-circle"></i> <?= $_SESSION['flash_success'] ?>
                <?php unset($_SESSION['flash_success']); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['flash_error'])): ?>
            <div class="admin-alert admin-alert-error">
                <i class="fas fa-exclamation-circle"></i> <?= $_SESSION['flash_error'] ?>
                <?php unset($_SESSION['flash_error']); ?>
            </div>
        <?php endif; ?>

        <!-- Page Content -->
        <div class="admin-content">
            <?= $content ?>
        </div>
    </div>

    <script>
        // Sidebar toggle
        document.getElementById('sidebarToggle').addEventListener('click', function () {
            document.querySelector('.admin-sidebar').classList.toggle('collapsed');
            document.querySelector('.admin-main').classList.toggle('expanded');
        });
        // Auto-hide alerts
        document.querySelectorAll('.admin-alert').forEach(a => setTimeout(() => a.style.display = 'none', 4000));
    </script>
</body>

</html>