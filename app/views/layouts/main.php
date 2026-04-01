<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="BookStore - Hiệu sách trực tuyến hàng đầu Việt Nam">
    <title><?= $pageTitle ?? 'BookStore' ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/components.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/pages.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="header" id="header">
        <div class="container">
            <a href="<?= BASE_URL ?>/" class="logo">
                <div class="logo-icon" style="background: transparent; border-radius: 10px;"><img src="<?= BASE_URL ?>/icon3.png" alt="Logo" style="width: 100%; height: 100%; object-fit: cover; border-radius: 10px;"></div>
                Book4u
            </a>

            <form class="search-bar" action="<?= BASE_URL ?>/search" method="GET" style="position:relative;">
                <i class="fas fa-search search-icon"></i>
                <input type="text" name="q" id="searchInput" placeholder="Tìm kiếm sách, tác giả..." 
                       value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" autocomplete="off">
                <i class="fas fa-times search-clear-btn" id="searchClearBtn" style="<?= empty($_GET['q']) ? 'display:none;' : 'display:block;' ?>"></i>
                <button type="submit"><i class="fas fa-arrow-right"></i></button>
                <div id="searchSuggestions" class="search-suggestions-dropdown"></div>
            </form>

            <nav class="nav-links" id="navLinks">
                <a href="<?= BASE_URL ?>/" class="<?= (!isset($_GET['url']) || $_GET['url'] == '') ? 'active' : '' ?>">
                    <i class="fas fa-home"></i> Trang chủ
                </a>
                <a href="<?= BASE_URL ?>/books" class="<?= (isset($_GET['url']) && strpos($_GET['url'], 'book') === 0) ? 'active' : '' ?>">
                    <i class="fas fa-book"></i> Sách
                </a>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php
                    require_once APP_PATH . '/models/Cart.php';
                    $cartModel = new Cart();
                    $cartItemCount = $cartModel->getCartCount($_SESSION['user_id']);
                    ?>
                    <a href="<?= BASE_URL ?>/cart" class="cart-link">
                        <i class="fas fa-shopping-cart"></i> Giỏ hàng
                        <span class="cart-badge" id="cartBadge"><?= $cartItemCount ?></span>
                    </a>
                    <div class="user-menu">
                        <div class="user-avatar">
                            <?php if (!empty($_SESSION['user_avatar'])): ?>
                                <img src="<?= BASE_URL . $_SESSION['user_avatar'] ?>" alt="Avatar" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
                            <?php else: ?>
                                <?= strtoupper(mb_substr($_SESSION['user_name'], 0, 1)) ?>
                            <?php endif; ?>
                        </div>
                        <div class="user-dropdown">
                            <a href="<?= BASE_URL ?>/account"><i class="fas fa-user-circle"></i> Tài khoản</a>
                            <a href="<?= BASE_URL ?>/orders"><i class="fas fa-box"></i> Đơn hàng</a>
                            <div class="divider"></div>
                            <a href="<?= BASE_URL ?>/logout"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/login"><i class="fas fa-sign-in-alt"></i> Đăng nhập</a>
                    <a href="<?= BASE_URL ?>/register" class="btn btn-primary btn-sm">Đăng ký</a>
                <?php endif; ?>
            </nav>

            <button class="mobile-toggle" id="mobileToggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </header>

    <!-- Flash Messages -->
    <?php if (isset($_SESSION['flash_success']) || isset($_SESSION['flash_error'])): ?>
    <div class="flash-container">
        <?php if (isset($_SESSION['flash_success'])): ?>
            <div class="flash-message alert-success">
                <i class="fas fa-check-circle"></i> <?= $_SESSION['flash_success'] ?>
            </div>
            <?php unset($_SESSION['flash_success']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['flash_error'])): ?>
            <div class="flash-message alert-error">
                <i class="fas fa-exclamation-circle"></i> <?= $_SESSION['flash_error'] ?>
            </div>
            <?php unset($_SESSION['flash_error']); ?>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Main Content -->
    <main>
        <?= $content ?>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <h3>📚 BookStore</h3>
                    <p>Hiệu sách trực tuyến hàng đầu Việt Nam. Hàng ngàn đầu sách chất lượng với giá tốt nhất.</p>
                    <div class="footer-social">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                        <a href="#"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>
                <div class="footer-col">
                    <h4>Về chúng tôi</h4>
                    <a href="#">Giới thiệu</a>
                    <a href="#">Tuyển dụng</a>
                    <a href="#">Liên hệ</a>
                    <a href="#">Điều khoản sử dụng</a>
                </div>
                <div class="footer-col">
                    <h4>Hỗ trợ</h4>
                    <a href="#">Hướng dẫn mua hàng</a>
                    <a href="#">Chính sách đổi trả</a>
                    <a href="#">Phương thức thanh toán</a>
                    <a href="#">Vận chuyển</a>
                </div>
                <div class="footer-col">
                    <h4>Liên hệ</h4>
                    <a href="#"><i class="fas fa-phone"></i> 1900 6868</a>
                    <a href="#"><i class="fas fa-envelope"></i> vmanhsaber@gmail.com</a>
                    <a href="#"><i class="fas fa-map-marker-alt"></i> Hà Nội, Việt Nam</a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                &copy; 2026 BookStore. All rights reserved. Made with ❤️ by Nhóm 5.
            </div>
        </div>
    </footer>

    <script type="module" src="<?= BASE_URL ?>/js/main.js"></script>
</body>
</html>
