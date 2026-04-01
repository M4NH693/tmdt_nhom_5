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
                <?php 
                if (!empty($featured) && count($featured) >= 4): 
                    $heroBooks = array_slice($featured, 0, 4);
                    foreach ($heroBooks as $book):
                ?>
                <div class="hero-book-card">
                    <div class="hero-book-cover" style="background: none; padding: 0;">
                        <?php if(!empty($book->cover_image)): ?>
                            <img src="<?= BASE_URL . $book->cover_image ?>" alt="<?= htmlspecialchars($book->title) ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">
                        <?php else: ?>
                            <div style="background: var(--primary-color); width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: white; border-radius: 8px; font-weight: bold; text-align: center; padding: 10px; font-size: 14px;">
                                <?= htmlspecialchars($book->title) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="hero-book-title" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100%;"><?= htmlspecialchars($book->title) ?></div>
                    <div class="hero-book-price"><?= number_format($book->price, 0, ',', '.') ?>₫</div>
                </div>
                <?php 
                    endforeach;
                else: 
                ?>
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
                <?php endif; ?>
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
            if (!empty($categories)):
                foreach ($categories as $i => $cat): ?>
                <a href="<?= BASE_URL ?>/category/<?= $cat->category_id ?>" class="category-card" style="display: flex; justify-content: space-between; align-items: center; position: relative; overflow: hidden; padding: 20px;">
                    <div style="z-index: 2; position: relative; max-width: 60%;">
                        <div class="category-name" style="font-size: 18px; font-weight: 600; line-height: 1.4; margin-bottom: 5px;"><?= htmlspecialchars($cat->category_name) ?></div>
                    </div>
                    <?php if (!empty($cat->image)): ?>
                        <div style="position: absolute; right: -10px; bottom: 0; width: 45%; height: 100%; z-index: 1;">
                            <img src="<?= BASE_URL . $cat->image ?>" alt="<?= htmlspecialchars($cat->category_name) ?>" style="width: 100%; height: 100%; object-fit: contain; object-position: right bottom;">
                        </div>
                    <?php endif; ?>
                </a>
            <?php endforeach;
            else: ?>
                <!-- Dummy ones if empty -->
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

<?php if (!empty($knsBooks)): ?>
<!-- SKILLS BOOKS -->
<section class="section" style="background: var(--bg-secondary);">
    <div class="container">
        <div class="section-header">
            <div>
                <h2 class="section-title">Sách <?= htmlspecialchars($knsCategory->category_name) ?></h2>
                <p class="section-subtitle" style="margin-top: 14px;">Bồi dưỡng tâm hồn và phát triển bản thân</p>
            </div>
            <a href="<?= BASE_URL ?>/category/<?= $knsCategory->category_id ?>" class="view-all">Xem thêm <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="books-grid">
            <?php foreach ($knsBooks as $book):
                include APP_PATH . '/views/partials/book_card.php';
            endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

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
