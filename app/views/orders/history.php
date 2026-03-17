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
                                <h4><?= htmlspecialchars($item->title) ?></h4>
                                <span>SL: <?= $item->quantity ?> × <?= number_format($item->unit_price, 0, ',', '.') ?>₫</span>
                            </div>
                            <div style="font-weight:600;color:var(--error);">
                                <?= number_format($item->total_price, 0, ',', '.') ?>₫
                            </div>
                        </div>
                    <?php endforeach; endif; ?>
                </div>
                <div class="order-footer">
                    <span>Tổng cộng: <span class="order-total"><?= number_format($order->total_amount, 0, ',', '.') ?>₫</span></span>
                </div>
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
