<!-- HERO SECTION -->
<section class="hero">
    <div class="container">
        <div class="hero-content">
            <div class="hero-badge">✨ Chào mừng đến với BookStore</div>
            <h1>Khám phá thế giới <span>tri thức</span> qua từng trang sách</h1>
            <p>Hàng ngàn đầu sách hay, đa dạng thể loại, giao hàng nhanh chóng. Đọc sách là đầu tư cho tương lai!</p>
            <div class="hero-actions">
                <a href="<?= BASE_URL ?>/books" class="btn btn-primary btn-lg">
                    <i class="fas fa-book-open"></i> Khám phá ngay
                </a>
                <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="<?= BASE_URL ?>/register" class="btn btn-secondary btn-lg" style="border-color: rgba(255,255,255,0.3); color: white;">
                    <i class="fas fa-user-plus"></i> Đăng ký
                </a>
                <?php endif; ?>
            </div>
        </div>
        <div class="hero-image">
            <div class="hero-books">
                <div class="hero-book-card">
                    <div class="hero-book-cover">📖</div>
                    <div class="hero-book-title">Đắc Nhân Tâm</div>
                    <div class="hero-book-price">89.000₫</div>
                </div>
                <div class="hero-book-card">
                    <div class="hero-book-cover">📚</div>
                    <div class="hero-book-title">Nhà Giả Kim</div>
                    <div class="hero-book-price">75.000₫</div>
                </div>
                <div class="hero-book-card">
                    <div class="hero-book-cover">📕</div>
                    <div class="hero-book-title">Tư Duy Nhanh Chậm</div>
                    <div class="hero-book-price">159.000₫</div>
                </div>
                <div class="hero-book-card">
                    <div class="hero-book-cover">📗</div>
                    <div class="hero-book-title">Sapiens</div>
                    <div class="hero-book-price">199.000₫</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CATEGORIES -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <div>
                <h2 class="section-title">Thể loại sách</h2>
                <p class="section-subtitle" style="margin-top: 14px;">Khám phá sách theo thể loại yêu thích</p>
            </div>
            <a href="<?= BASE_URL ?>/books" class="view-all">Xem tất cả <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="categories-grid">
            <?php
            $icons = ['📖','📚','📕','📗','📘','📙','📓','📔'];
            if (!empty($categories)):
                foreach ($categories as $i => $cat): ?>
                <a href="<?= BASE_URL ?>/category/<?= $cat->category_id ?>" class="category-card">
                    <div class="category-icon"><?= $icons[$i % count($icons)] ?></div>
                    <div class="category-name"><?= htmlspecialchars($cat->category_name) ?></div>
                    <div class="category-count"><?= $cat->book_count ?> cuốn sách</div>
                </a>
            <?php endforeach;
            else: ?>
                <div class="category-card"><div class="category-icon">📖</div><div class="category-name">Văn học</div><div class="category-count">120 cuốn sách</div></div>
                <div class="category-card"><div class="category-icon">📚</div><div class="category-name">Kinh tế</div><div class="category-count">95 cuốn sách</div></div>
                <div class="category-card"><div class="category-icon">📕</div><div class="category-name">Khoa học</div><div class="category-count">78 cuốn sách</div></div>
                <div class="category-card"><div class="category-icon">📗</div><div class="category-name">Kỹ năng sống</div><div class="category-count">64 cuốn sách</div></div>
                <div class="category-card"><div class="category-icon">📘</div><div class="category-name">Thiếu nhi</div><div class="category-count">150 cuốn sách</div></div>
                <div class="category-card"><div class="category-icon">📙</div><div class="category-name">Ngoại ngữ</div><div class="category-count">45 cuốn sách</div></div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- FEATURED BOOKS -->
<section class="section" style="background: var(--bg-secondary);">
    <div class="container">
        <div class="section-header">
            <div>
                <h2 class="section-title">Sách bán chạy</h2>
                <p class="section-subtitle" style="margin-top: 14px;">Những cuốn sách được yêu thích nhất</p>
            </div>
            <a href="<?= BASE_URL ?>/books?sort=popular" class="view-all">Xem thêm <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="books-grid">
            <?php if (!empty($featured)):
                foreach ($featured as $book):
                    include APP_PATH . '/views/partials/book_card.php';
                endforeach;
            else:
                for ($i = 0; $i < 4; $i++): ?>
                <div class="book-card">
                    <div class="book-card-image">
                        <div class="book-card-placeholder">📚<span>Sách mẫu</span></div>
                        <span class="book-card-badge badge-hot">HOT</span>
                    </div>
                    <div class="book-card-body">
                        <div class="book-card-category">Văn học</div>
                        <h3 class="book-card-title"><a href="#">Đắc Nhân Tâm - Dale Carnegie</a></h3>
                        <div class="book-card-author">Dale Carnegie</div>
                        <div class="book-card-footer">
                            <span class="book-card-price">89.000₫</span>
                            <div class="book-card-rating">★★★★★</div>
                        </div>
                    </div>
                </div>
            <?php endfor; endif; ?>
        </div>
    </div>
</section>

<!-- NEW ARRIVALS -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <div>
                <h2 class="section-title">Sách mới</h2>
                <p class="section-subtitle" style="margin-top: 14px;">Cập nhật những đầu sách mới nhất</p>
            </div>
            <a href="<?= BASE_URL ?>/books?sort=newest" class="view-all">Xem thêm <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="books-grid">
            <?php if (!empty($newArrivals)):
                foreach ($newArrivals as $book):
                    include APP_PATH . '/views/partials/book_card.php';
                endforeach;
            else:
                for ($i = 0; $i < 4; $i++): ?>
                <div class="book-card">
                    <div class="book-card-image">
                        <div class="book-card-placeholder">📗<span>Sách mới</span></div>
                        <span class="book-card-badge badge-new">NEW</span>
                    </div>
                    <div class="book-card-body">
                        <div class="book-card-category">Kinh tế</div>
                        <h3 class="book-card-title"><a href="#">Nhà Giả Kim - Paulo Coelho</a></h3>
                        <div class="book-card-author">Paulo Coelho</div>
                        <div class="book-card-footer">
                            <span class="book-card-price">75.000₫</span>
                            <div class="book-card-rating">★★★★☆</div>
                        </div>
                    </div>
                </div>
            <?php endfor; endif; ?>
        </div>
    </div>
</section>

<!-- FEATURES -->
<section class="section features">
    <div class="container">
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-shipping-fast"></i></div>
                <div class="feature-title">Giao hàng nhanh</div>
                <div class="feature-desc">Miễn phí giao hàng cho đơn từ 300.000₫</div>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-shield-alt"></i></div>
                <div class="feature-title">Sách chính hãng</div>
                <div class="feature-desc">100% sách chính hãng, cam kết chất lượng</div>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-undo"></i></div>
                <div class="feature-title">Đổi trả dễ dàng</div>
                <div class="feature-desc">Đổi trả miễn phí trong 30 ngày</div>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-headset"></i></div>
                <div class="feature-title">Hỗ trợ 24/7</div>
                <div class="feature-desc">Luôn sẵn sàng hỗ trợ bạn mọi lúc</div>
            </div>
        </div>
    </div>
</section>
