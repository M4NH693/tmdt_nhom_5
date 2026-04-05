<?php
$statusMap = [
    'pending'   => ['label' => 'Chờ xác nhận', 'class' => 'status-pending'],
    'confirmed' => ['label' => 'Đã xác nhận',  'class' => 'status-confirmed'],
    'shipping'  => ['label' => 'Đang giao',     'class' => 'status-shipping'],
    'delivered' => ['label' => 'Đã giao',       'class' => 'status-delivered'],
    'cancelled' => ['label' => 'Đã hủy',        'class' => 'status-cancelled'],
    'returned'  => ['label' => 'Đã trả hàng',   'class' => 'status-cancelled'],
];
?>
<div class="orders-page">
    <div class="container">
        <div class="breadcrumb">
            <a href="<?= BASE_URL ?>/">Trang chủ</a>
            <span class="separator">/</span>
            <span class="current">Đơn hàng của tôi</span>
        </div>

        <h1>📦 Lịch sử đơn hàng</h1>

        <?php if (!empty($orders)): ?>
            <?php foreach ($orders as $order):
                $st = $statusMap[$order->order_status] ?? ['label' => $order->order_status, 'class' => ''];
            ?>
            <div class="order-card">
                <div class="order-header">
                    <div>
                        <span class="order-code"><?= $order->order_code ?></span>
                        <span class="order-date" style="margin-left:16px;"><?= date('d/m/Y H:i', strtotime($order->ordered_at)) ?></span>
                    </div>
                    <span class="order-status-badge <?= $st['class'] ?>"><?= $st['label'] ?></span>
                </div>
                <div class="order-items-list">
                    <?php if (!empty($order->items)):
                        foreach ($order->items as $item): ?>
                        <div class="order-item-row">
                            <div class="order-item-img">
                                <?php if (!empty($item->cover_image)): ?>
                                    <img src="<?= BASE_URL . (strpos($item->cover_image, '/') === 0 ? $item->cover_image : '/images/books/' . $item->cover_image) ?>" alt="">
                                <?php else: ?>
                                    <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:1.5rem;">📚</div>
                                <?php endif; ?>
                            </div>
                            <div class="order-item-info">
                                <h4><a href="<?= BASE_URL ?>/book/<?= $item->book_id ?>" style="text-decoration:none; color:inherit;"><?= htmlspecialchars($item->title) ?></a></h4>
                                <span>SL: <?= $item->quantity ?> × <?= number_format($item->unit_price, 0, ',', '.') ?>₫</span>
                                <?php if ($order->order_status === 'delivered'): ?>
                                    <div style="margin-top: 8px;">
                                        <a href="<?= BASE_URL ?>/book/<?= $item->book_id ?>#reviews" class="btn btn-outline btn-sm" style="padding: 4px 10px; font-size: 0.8rem; border-color: #ffc107; color: #ff9800;">
                                            <i class="fas fa-star" style="color: #ffc107; margin-right: 4px;"></i> Đánh giá
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div style="font-weight:600;color:var(--error);">
                                <?= number_format($item->total_price, 0, ',', '.') ?>₫
                            </div>
                        </div>
                    <?php endforeach; endif; ?>
                </div>
                <div class="order-footer">
                    <div class="order-footer-actions">
                        <?php if ($order->order_status === 'pending'): ?>
                            <button type="button" class="btn btn-outline btn-sm order-edit-addr-btn" 
                                    onclick="toggleAddressForm(<?= $order->order_id ?>)">
                                <i class="fas fa-edit"></i> Cập nhật địa chỉ
                            </button>
                            <form method="POST" action="<?= BASE_URL ?>/orders/cancel/<?= $order->order_id ?>" 
                                  onsubmit="return confirm('Bạn có chắc muốn hủy đơn hàng <?= $order->order_code ?>?');" style="display:inline;">
                                <button type="submit" class="btn btn-danger-outline btn-sm">
                                    <i class="fas fa-times-circle"></i> Hủy đơn
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                    <span>Tổng cộng: <span class="order-total"><?= number_format($order->total_amount, 0, ',', '.') ?>₫</span></span>
                </div>

                <?php if ($order->order_status === 'pending'): ?>
                <div class="order-address-form" id="address-form-<?= $order->order_id ?>" style="display:none;">
                    <form method="POST" action="<?= BASE_URL ?>/orders/update-address/<?= $order->order_id ?>">
                        <div class="address-form-header">
                            <h4><i class="fas fa-map-marker-alt"></i> Cập nhật địa chỉ giao hàng</h4>
                        </div>
                        <div class="address-form-body">
                            <div class="address-form-row">
                                <div class="address-form-group">
                                    <label>Người nhận</label>
                                    <input type="text" name="receiver_name" class="form-control"
                                           value="<?= htmlspecialchars($order->receiver_name ?? '') ?>" required>
                                </div>
                                <div class="address-form-group">
                                    <label>Số điện thoại</label>
                                    <input type="text" name="receiver_phone" class="form-control"
                                           value="<?= htmlspecialchars($order->receiver_phone ?? '') ?>" required>
                                </div>
                            </div>
                            <div class="address-form-group">
                                <label>Địa chỉ giao hàng</label>
                                <input type="text" name="shipping_address" class="form-control"
                                       value="<?= htmlspecialchars($order->shipping_address ?? '') ?>" required>
                            </div>
                            <div class="address-form-actions">
                                <button type="button" class="btn btn-outline btn-sm" onclick="toggleAddressForm(<?= $order->order_id ?>)">Hủy bỏ</button>
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save"></i> Lưu thay đổi</button>
                            </div>
                        </div>
                    </form>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-state-icon">📦</div>
                <h3>Chưa có đơn hàng nào</h3>
                <p>Bạn chưa đặt đơn hàng nào. Hãy bắt đầu mua sắm!</p>
                <a href="<?= BASE_URL ?>/books" class="btn btn-primary btn-lg">Mua sắm ngay</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function toggleAddressForm(orderId) {
    const form = document.getElementById('address-form-' + orderId);
    if (form) {
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    }
}
</script>
