<?php
/** Book Card Partial - reusable component */
$bookPrice = number_format($book->price, 0, ',', '.');
$stars = '';
for ($s = 1; $s <= 5; $s++) {
    $stars .= $s <= round($book->avg_rating) ? '★' : '☆';
}
?>
<div class="book-card">
    <div class="book-card-image">
        <?php if (!empty($book->cover_image)): ?>
            <img src="<?= BASE_URL . (strpos($book->cover_image, '/') === 0 ? $book->cover_image : '/images/books/' . $book->cover_image) ?>" alt="<?= htmlspecialchars($book->title) ?>">
        <?php else: ?>
            <div class="book-card-placeholder">📚<span><?= htmlspecialchars(mb_substr($book->title, 0, 30)) ?></span></div>
        <?php endif; ?>
        
        <?php if (isset($book->total_sold) && $book->total_sold > 100): ?>
            <span class="book-card-badge badge-hot">HOT</span>
        <?php endif; ?>

        <div class="book-card-overlay">
            <form action="<?= BASE_URL ?>/cart/add" method="POST" style="display:inline;">
                <input type="hidden" name="book_id" value="<?= $book->book_id ?>">
                <input type="hidden" name="quantity" value="1">
                <button type="submit" class="btn">🛒 Thêm giỏ</button>
            </form>
            <a href="<?= BASE_URL ?>/book/<?= $book->book_id ?>" class="btn">👁 Chi tiết</a>
        </div>
    </div>
    <div class="book-card-body">
        <?php if (isset($book->category_name)): ?>
            <div class="book-card-category"><?= htmlspecialchars($book->category_name) ?></div>
        <?php endif; ?>
        <h3 class="book-card-title">
            <a href="<?= BASE_URL ?>/book/<?= $book->book_id ?>"><?= htmlspecialchars($book->title) ?></a>
        </h3>
        <div class="book-card-author"><?= htmlspecialchars($book->authors ?? 'Chưa cập nhật') ?></div>
        <div class="book-card-footer">
            <span class="book-card-price"><?= $bookPrice ?>₫</span>
            <div class="book-card-rating"><?= $stars ?></div>
        </div>
    </div>
</div>
