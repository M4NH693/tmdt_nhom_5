<div class="page-header">
    <h1><i class="fas fa-users"></i> Quản lý người dùng</h1>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <h3>Danh sách người dùng (<?= count($users) ?>)</h3>
    </div>
    <div class="admin-card-body" style="padding:0;">
        <table class="admin-table">
            <thead><tr><th>ID</th><th>Họ tên</th><th>Email</th><th>Vai trò</th><th>Trạng thái</th><th>Ngày tạo</th><th>Thao tác</th></tr></thead>
            <tbody>
            <?php if (!empty($users)): foreach ($users as $u): ?>
            <tr>
                <td>#<?= $u->user_id ?></td>
                <td>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:34px;height:34px;background:linear-gradient(135deg,#2D6A4F,#52B788);color:white;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.8rem;flex-shrink:0;">
                            <?= strtoupper(mb_substr($u->full_name, 0, 1)) ?>
                        </div>
                        <strong><?= htmlspecialchars($u->full_name) ?></strong>
                    </div>
                </td>
                <td style="color:var(--admin-text-muted);"><?= $u->email ?></td>
                <td><span class="admin-badge <?= $u->role === 'admin' ? 'badge-admin' : 'badge-customer' ?>"><?= $u->role === 'admin' ? 'Admin' : 'Khách hàng' ?></span></td>
                <td><span class="admin-badge <?= $u->is_active ? 'badge-active' : 'badge-inactive' ?>"><?= $u->is_active ? 'Hoạt động' : 'Bị khóa' ?></span></td>
                <td style="color:var(--admin-text-muted);font-size:0.85rem;"><?= date('d/m/Y', strtotime($u->created_at)) ?></td>
                <td>
                    <?php if ($u->role !== 'admin'): ?>
                    <a href="<?= BASE_URL ?>/admin/users/toggle/<?= $u->user_id ?>" 
                       class="admin-btn <?= $u->is_active ? 'admin-btn-warning' : 'admin-btn-success' ?> admin-btn-sm"
                       onclick="return confirm('<?= $u->is_active ? 'Khóa' : 'Mở khóa' ?> người dùng này?')">
                        <i class="fas fa-<?= $u->is_active ? 'lock' : 'unlock' ?>"></i>
                        <?= $u->is_active ? 'Khóa' : 'Mở khóa' ?>
                    </a>
                    <a href="<?= BASE_URL ?>/admin/users/delete/<?= $u->user_id ?>" 
                       class="admin-btn admin-btn-danger admin-btn-sm" style="background-color: #e63946; color: white;"
                       onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này không? Hành động này không thể hoàn tác.')">
                        <i class="fas fa-trash"></i> Xóa
                    </a>
                    <?php else: ?>
                    <span style="color:var(--admin-text-muted);font-size:0.85rem;">—</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; else: ?>
            <tr><td colspan="7" style="text-align:center;padding:40px;color:var(--admin-text-muted);">Chưa có người dùng</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
