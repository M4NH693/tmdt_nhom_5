<?php
$bookPrice = number_format($book->price, 0, ',', '.');
$stars = '';
for ($s = 1; $s <= 5; $s++) {
    $stars .= $s <= round($book->avg_rating) ? '★' : '☆';
}
?>
<div class="book-detail">
    <div class="container">
        <!-- Breadcrumb -->
        <div class="breadcrumb" style="grid-column: 1 / -1;">
            <a href="<?= BASE_URL ?>/">Trang chủ</a>
            <span class="separator">/</span>
            <a href="<?= BASE_URL ?>/books">Sách</a>
            <?php if (!empty($book->category_name)): ?>
                <span class="separator">/</span>
                <a href="<?= BASE_URL ?>/category/<?= $book->category_id ?>"><?= htmlspecialchars($book->category_name) ?></a>
            <?php endif; ?>
            <span class="separator">/</span>
            <span class="current"><?= htmlspecialchars($book->title) ?></span>
        </div>

        <!-- Image -->
        <div class="book-gallery">
            <div class="book-main-image">
                <?php if (!empty($book->cover_image)): ?>
                    <img src="<?= BASE_URL . (strpos($book->cover_image, '/') === 0 ? $book->cover_image : '/images/books/' . $book->cover_image) ?>" id="mainBookImage" alt="<?= htmlspecialchars($book->title) ?>">
                <?php else: ?>
                    <div class="placeholder">📖</div>
                <?php endif; ?>
            </div>
            <?php if (!empty($images)): ?>
            <div class="book-thumbnails">
                <?php if (!empty($book->cover_image)): ?>
                    <img src="<?= BASE_URL . (strpos($book->cover_image, '/') === 0 ? $book->cover_image : '/images/books/' . $book->cover_image) ?>" class="thumbnail-img active" onclick="changeMainImage(this)" alt="<?= htmlspecialchars($book->title) ?>">
                <?php endif; ?>
                <?php foreach ($images as $img): ?>
                    <img src="<?= BASE_URL . (strpos($img->image_url, '/') === 0 ? $img->image_url : '/images/books/' . $img->image_url) ?>" class="thumbnail-img" onclick="changeMainImage(this)" alt="ảnh">
                <?php endforeach; ?>
            </div>
            
            <script>
            function changeMainImage(element) {
                document.getElementById('mainBookImage').src = element.src;
                document.querySelectorAll('.thumbnail-img').forEach(el => el.classList.remove('active'));
                element.classList.add('active');
            }
            </script>
            <?php endif; ?>
        </div>

        <!-- Info -->
        <div class="book-info">
            <h1><?= htmlspecialchars($book->title) ?></h1>
            <div class="book-meta">
                <?php if (!empty($book->authors)): ?>
                    <span><i class="fas fa-user-edit"></i> <?= htmlspecialchars($book->authors) ?></span>
                <?php endif; ?>
                <span><i class="fas fa-star" style="color:var(--accent)"></i> <?= $book->avg_rating ?>/5 <?= $stars ?></span>
                <span><i class="fas fa-shopping-bag"></i> Đã bán <?= $book->total_sold ?></span>
            </div>

            <div class="book-price-block">
                <span class="book-price-current"><?= $bookPrice ?>₫</span>
                <?php if ($book->stock_quantity > 0): ?>
                    <span class="book-stock stock-in"><i class="fas fa-check"></i> Còn hàng (<?= $book->stock_quantity ?>)</span>
                <?php else: ?>
                    <span class="book-stock stock-out"><i class="fas fa-times"></i> Hết hàng</span>
                <?php endif; ?>
            </div>

            <?php if ($book->stock_quantity > 0): ?>
            <form action="<?= BASE_URL ?>/cart/add" method="POST" class="book-actions">
                <input type="hidden" name="book_id" value="<?= $book->book_id ?>">
                <div class="quantity-selector">
                    <button type="button" onclick="changeQty(-1)">−</button>
                    <input type="number" name="quantity" id="qtyInput" value="1" min="1" max="<?= $book->stock_quantity ?>">
                    <button type="button" onclick="changeQty(1)">+</button>
                </div>
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-cart-plus"></i> Thêm vào giỏ
                </button>
            </form>
            <?php endif; ?>

            <!-- Details Table -->
            <table class="book-details-table">
                <?php if (!empty($book->publisher_name)): ?>
                <tr><td>Nhà xuất bản</td><td><?= htmlspecialchars($book->publisher_name) ?></td></tr>
                <?php endif; ?>
                <?php if (!empty($book->publication_year)): ?>
                <tr><td>Năm xuất bản</td><td><?= $book->publication_year ?></td></tr>
                <?php endif; ?>
                <?php if (!empty($book->pages)): ?>
                <tr><td>Số trang</td><td><?= $book->pages ?></td></tr>
                <?php endif; ?>
                <?php if (!empty($book->language)): ?>
                <tr><td>Ngôn ngữ</td><td><?= htmlspecialchars($book->language) ?></td></tr>
                <?php endif; ?>
                <?php if (!empty($book->isbn)): ?>
                <tr><td>ISBN</td><td><?= htmlspecialchars($book->isbn) ?></td></tr>
                <?php endif; ?>
                <?php if (!empty($book->dimensions)): ?>
                <tr><td>Kích thước</td><td><?= htmlspecialchars($book->dimensions) ?></td></tr>
                <?php endif; ?>
            </table>

            <?php if (!empty($book->description)): ?>
            <div class="book-description">
                <h3>Mô tả sách</h3>
                <p><?= nl2br(htmlspecialchars($book->description)) ?></p>
            </div>
            <?php endif; ?>

            <!-- Reviews -->
            <div class="reviews-section">
                <h3>Đánh giá (<?= count($reviews ?? []) ?>)</h3>

                <?php if (isset($canReview) && $canReview): ?>
                <div class="review-form-container" style="margin-bottom: 25px; padding: 20px; background: var(--bg-secondary); border-radius: 8px;">
                    <h4 style="margin-bottom: 15px;">Viết đánh giá của bạn</h4>
                    <form action="<?= BASE_URL ?>/book/review/<?= $book->book_id ?>" method="POST">
                        <div class="form-group" style="margin-bottom: 15px;">
                            <label style="font-weight: 500;">Đánh giá (Sao):</label>
                            <select name="rating" class="form-control" style="width: 140px; display: inline-block; margin-left: 10px; cursor: pointer;">
                                <option value="5">⭐⭐⭐⭐⭐ 5</option>
                                <option value="4">⭐⭐⭐⭐ 4</option>
                                <option value="3">⭐⭐⭐ 3</option>
                                <option value="2">⭐⭐ 2</option>
                                <option value="1">⭐ 1</option>
                            </select>
                        </div>
                        <div class="form-group" style="margin-bottom: 15px;">
                            <textarea name="comment" class="form-control" rows="4" placeholder="Chia sẻ cảm nhận của bạn về cuốn sách này..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Gửi đánh giá
                        </button>
                    </form>
                </div>
                <?php endif; ?>

                <?php if (!empty($reviews)):
                    foreach ($reviews as $rv):
                        $rvStars = str_repeat('★', $rv->rating) . str_repeat('☆', 5 - $rv->rating);
                ?>
                <div class="review-card">
                    <div class="review-header">
                        <div class="review-avatar"><?= strtoupper(mb_substr($rv->full_name, 0, 1)) ?></div>
                        <div>
                            <div class="review-name"><?= htmlspecialchars($rv->full_name) ?></div>
                            <div class="review-date"><?= date('d/m/Y', strtotime($rv->created_at)) ?></div>
                        </div>
                    </div>
                    <div class="review-stars"><?= $rvStars ?></div>
                    <?php if (!empty($rv->comment)): ?>
                        <div class="review-text"><?= nl2br(htmlspecialchars($rv->comment)) ?></div>
                    <?php endif; ?>
                </div>
                <?php endforeach;
                else: ?>
                <p style="color: var(--text-secondary);">Chưa có đánh giá nào. Hãy là người đầu tiên!</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Related Books -->
<?php if (!empty($related)): ?>
<section class="related-books">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Sách liên quan</h2>
        </div>
        <div class="books-grid">
            <?php foreach ($related as $book):
                include APP_PATH . '/views/partials/book_card.php';
            endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
