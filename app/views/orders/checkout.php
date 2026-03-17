<div class="checkout-page">
    <div class="container">
        <div class="breadcrumb">
            <a href="<?= BASE_URL ?>/">Trang chủ</a>
            <span class="separator">/</span>
            <a href="<?= BASE_URL ?>/cart">Giỏ hàng</a>
            <span class="separator">/</span>
            <span class="current">Thanh toán</span>
        </div>

        <h1>💳 Thanh toán</h1>

        <form method="POST" action="<?= BASE_URL ?>/checkout">
            <div class="checkout-layout">
                <div class="checkout-form">
                    <h2>📍 Thông tin giao hàng</h2>

                    <div class="form-group">
                        <label for="receiver_name">Họ tên người nhận</label>
                        <input type="text" name="receiver_name" id="receiver_name" class="form-control"
                               value="<?= htmlspecialchars($_SESSION['user_name'] ?? '') ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="receiver_phone">Số điện thoại</label>
                        <input type="tel" name="receiver_phone" id="receiver_phone" class="form-control"
                               placeholder="0901234567" required>
                    </div>

                    <div class="form-group">
                        <label for="shipping_address">Địa chỉ giao hàng</label>
                        <textarea name="shipping_address" id="shipping_address" class="form-control"
                                  rows="3" placeholder="Số nhà, đường, phường/xã, quận/huyện, tỉnh/thành phố" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="note">Ghi chú (tùy chọn)</label>
                        <textarea name="note" id="note" class="form-control"
                                  rows="2" placeholder="Ghi chú cho đơn hàng..."></textarea>
                    </div>

                    <h2 style="margin-top:30px;">💰 Phương thức thanh toán</h2>
                    <div class="payment-options">
                        <label class="payment-option selected flex-1">
                            <input type="radio" name="payment_method" value="cod" checked> 
                            <i class="fas fa-money-bill-wave"></i> COD (Thanh toán khi nhận hàng)
                        </label>
                    </div>
                </div>

                <div class="cart-summary">
                    <h3>Đơn hàng (<?= count($cartItems) ?> sản phẩm)</h3>
                    <?php foreach ($cartItems as $item): ?>
                    <div style="display:flex;gap:10px;padding:10px 0;border-bottom:1px solid var(--border-light);align-items:center;">
                        <div style="width:50px;height:65px;border-radius:4px;overflow:hidden;background:var(--bg-secondary);flex-shrink:0;">
                            <?php if (!empty($item->cover_image)): ?>
                                <img src="<?= BASE_URL . (strpos($item->cover_image, '/') === 0 ? $item->cover_image : '/images/books/' . $item->cover_image) ?>" style="width:100%;height:100%;object-fit:cover;">
                            <?php else: ?>
                                <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">📚</div>
                            <?php endif; ?>
                        </div>
                        <div style="flex:1; min-width:0;">
                            <div style="font-size:0.85rem;font-weight:500;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                <?= htmlspecialchars($item->title) ?>
                            </div>
                            <div style="font-size:0.8rem;color:var(--text-secondary);">x<?= $item->quantity ?></div>
                        </div>
                        <div style="font-weight:600;font-size:0.9rem;color:var(--error);">
                            <?= number_format($item->price * $item->quantity, 0, ',', '.') ?>₫
                        </div>
                    </div>
                    <?php endforeach; ?>

                    <div class="cart-summary-row" style="margin-top:16px;">
                        <span>Tạm tính</span>
                        <span><?= number_format($cartTotal, 0, ',', '.') ?>₫</span>
                    </div>
                    <div class="cart-summary-row">
                        <span>Phí vận chuyển</span>
                        <span>30.000₫</span>
                    </div>
                    <div class="cart-summary-row total">
                        <span>Tổng cộng</span>
                        <span class="text-error"><?= number_format($cartTotal + 30000, 0, ',', '.') ?>₫</span>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-check"></i> Đặt hàng
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
