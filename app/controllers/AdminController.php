<?php
/**
 * Admin Controller - Full Admin Panel
 */
class AdminController extends Controller {

    public function __construct() {
        // Check admin access
    }

    private function requireAdmin() {
        if (!$this->isLoggedIn() || ($_SESSION['user_role'] ?? '') !== 'admin') {
            $_SESSION['flash_error'] = 'Bạn không có quyền truy cập trang quản trị.';
            $this->redirect('login');
        }
    }

    private function adminView($view, $data = []) {
        $this->requireAdmin();
        extract($data);
        $viewFile = APP_PATH . "/views/admin/{$view}.php";
        if (file_exists($viewFile)) {
            ob_start();
            require_once $viewFile;
            $content = ob_get_clean();
            require_once APP_PATH . '/views/admin/layouts/main.php';
        }
    }

    // ============ DASHBOARD ============
    public function dashboard() {
        $bookModel = $this->model('Book');
        $userModel = $this->model('User');
        $orderModel = $this->model('Order');
        $categoryModel = $this->model('Category');

        $data = [
            'pageTitle'   => 'Dashboard - Admin',
            'totalBooks'  => $bookModel->count('is_active = 1'),
            'totalUsers'  => $userModel->count(),
            'totalOrders' => $orderModel->count(),
            'totalRevenue'=> $orderModel->queryOne("SELECT COALESCE(SUM(total_amount),0) as total FROM orders WHERE order_status = 'delivered'")->total,
            'recentOrders'=> $orderModel->query("SELECT o.*, u.full_name FROM orders o JOIN users u ON u.user_id = o.user_id ORDER BY o.ordered_at DESC LIMIT 5"),
            'topBooks'    => $bookModel->query("SELECT * FROM books WHERE is_active = 1 ORDER BY total_sold DESC LIMIT 5"),
        ];
        $this->adminView('dashboard', $data);
    }

    // ============ REVENUE DATA API (JSON) ============
    public function revenueData() {
        $this->requireAdmin();
        $orderModel = $this->model('Order');
        $data = $orderModel->query(
            "SELECT YEAR(ordered_at) as y, MONTH(ordered_at) as m,
                    SUM(CASE WHEN order_status='delivered' THEN total_amount ELSE 0 END) as revenue,
                    COUNT(*) as orders
             FROM orders
             WHERE ordered_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
             GROUP BY YEAR(ordered_at), MONTH(ordered_at)
             ORDER BY y ASC, m ASC"
        );
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    // ============ BOOKS ============
    public function books() {
        $bookModel = $this->model('Book');
        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = 15;
        $offset = ($page - 1) * $limit;
        $total = $bookModel->countAll();

        $data = [
            'pageTitle'   => 'Quản lý sách - Admin',
            'books'       => $bookModel->getAll($limit, $offset, 'newest'),
            'currentPage' => $page,
            'totalPages'  => ceil($total / $limit),
            'totalBooks'  => $total,
        ];
        $this->adminView('books/index', $data);
    }

    public function addBook() {
        $categoryModel = $this->model('Category');
        $categories = $categoryModel->getAllActive();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle cover image
            $coverImage = $this->uploadSingleImage('cover_image');

            $bookModel = $this->model('Book');
            $slug = $this->createSlug($_POST['title'] ?? '');
            $bookId = $bookModel->insert([
                'title'       => trim($_POST['title'] ?? ''),
                'slug'        => $slug,
                'isbn'        => trim($_POST['isbn'] ?? '') ?: null,
                'category_id' => (int)($_POST['category_id'] ?? 0) ?: null,
                'price'       => (float)($_POST['price'] ?? 0),
                'stock_quantity' => (int)($_POST['stock_quantity'] ?? 0),
                'description' => trim($_POST['description'] ?? ''),
                'publication_year' => (int)($_POST['publication_year'] ?? 0) ?: null,
                'pages'       => (int)($_POST['pages'] ?? 0) ?: null,
                'language'    => trim($_POST['language'] ?? 'Tiếng Việt'),
                'cover_image' => $coverImage,
            ]);

            // Handle preview images
            $previewImages = $this->uploadMultipleImages('preview_images');
            if (!empty($previewImages)) {
                $db = Database::getInstance()->getConnection();
                foreach ($previewImages as $idx => $imgUrl) {
                    $stmt = $db->prepare("INSERT INTO book_images (book_id, image_url, sort_order) VALUES (?, ?, ?)");
                    $stmt->execute([$bookId, $imgUrl, $idx]);
                }
            }
            $this->setFlash('success', 'Thêm sách thành công!');
            $this->redirect('admin/books');
            return;
        }

        $data = ['pageTitle' => 'Thêm sách - Admin', 'categories' => $categories];
        $this->adminView('books/form', $data);
    }

    public function editBook($id) {
        $bookModel = $this->model('Book');
        $categoryModel = $this->model('Category');
        $book = $bookModel->findById($id);
        if (!$book) { $this->redirect('admin/books'); return; }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $slug = $this->createSlug($_POST['title'] ?? '');
            $updateData = [
                'title'       => trim($_POST['title'] ?? ''),
                'slug'        => $slug,
                'isbn'        => trim($_POST['isbn'] ?? '') ?: null,
                'category_id' => (int)($_POST['category_id'] ?? 0) ?: null,
                'price'       => (float)($_POST['price'] ?? 0),
                'stock_quantity' => (int)($_POST['stock_quantity'] ?? 0),
                'description' => trim($_POST['description'] ?? ''),
                'publication_year' => (int)($_POST['publication_year'] ?? 0) ?: null,
                'pages'       => (int)($_POST['pages'] ?? 0) ?: null,
                'language'    => trim($_POST['language'] ?? 'Tiếng Việt'),
            ];

            $coverImage = $this->uploadSingleImage('cover_image');
            if ($coverImage) {
                $updateData['cover_image'] = $coverImage;
            }

            $bookModel->update($id, $updateData);

            $previewImages = $this->uploadMultipleImages('preview_images');
            if (!empty($previewImages)) {
                $db = Database::getInstance()->getConnection();
                $stmt = $db->prepare("SELECT MAX(sort_order) as mx FROM book_images WHERE book_id = ?");
                $stmt->execute([$id]);
                $maxSort = (int)$stmt->fetch()->mx;

                foreach ($previewImages as $idx => $imgUrl) {
                    $stmt = $db->prepare("INSERT INTO book_images (book_id, image_url, sort_order) VALUES (?, ?, ?)");
                    $stmt->execute([$id, $imgUrl, $maxSort + 1 + $idx]);
                }
            }
            $this->setFlash('success', 'Cập nhật sách thành công!');
            $this->redirect('admin/books');
            return;
        }

        $data = [
            'pageTitle'  => 'Sửa sách - Admin',
            'book'       => $book,
            'categories' => $categoryModel->getAllActive(),
            'preview_images' => $bookModel->getImages($id),
        ];
        $this->adminView('books/form', $data);
    }

    public function deleteBook($id) {
        $this->requireAdmin();
        $bookModel = $this->model('Book');
        $bookModel->update($id, ['is_active' => 0]);
        $this->setFlash('success', 'Đã ẩn sách thành công.');
        $this->redirect('admin/books');
    }

    // ============ CATEGORIES ============
    public function categories() {
        $categoryModel = $this->model('Category');
        $data = [
            'pageTitle'  => 'Quản lý danh mục - Admin',
            'categories' => $categoryModel->getWithBookCount(),
        ];
        $this->adminView('categories/index', $data);
    }

    public function addCategory() {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $categoryModel = $this->model('Category');
            $name = trim($_POST['category_name'] ?? '');
            $categoryModel->insert([
                'category_name' => $name,
                'slug'          => $this->createSlug($name),
                'description'   => trim($_POST['description'] ?? ''),
            ]);
            $this->setFlash('success', 'Thêm danh mục thành công!');
            $this->redirect('admin/categories');
            return;
        }
        $this->adminView('categories/form', ['pageTitle' => 'Thêm danh mục - Admin']);
    }

    public function editCategory($id) {
        $categoryModel = $this->model('Category');
        $category = $categoryModel->findById($id);
        if (!$category) { $this->redirect('admin/categories'); return; }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['category_name'] ?? '');
            $categoryModel->update($id, [
                'category_name' => $name,
                'slug'          => $this->createSlug($name),
                'description'   => trim($_POST['description'] ?? ''),
            ]);
            $this->setFlash('success', 'Cập nhật danh mục thành công!');
            $this->redirect('admin/categories');
            return;
        }

        $data = ['pageTitle' => 'Sửa danh mục - Admin', 'category' => $category];
        $this->adminView('categories/form', $data);
    }

    public function deleteCategory($id) {
        $this->requireAdmin();
        $categoryModel = $this->model('Category');
        $categoryModel->delete($id);
        $this->setFlash('success', 'Xóa danh mục thành công.');
        $this->redirect('admin/categories');
    }

    // ============ ORDERS ============
    public function orders() {
        $orderModel = $this->model('Order');
        $status = $_GET['status'] ?? '';
        $cond = $status ? "order_status = ?" : '';
        $params = $status ? [$status] : [];

        $data = [
            'pageTitle' => 'Quản lý đơn hàng - Admin',
            'orders'    => $orderModel->query(
                "SELECT o.*, u.full_name, u.email FROM orders o JOIN users u ON u.user_id = o.user_id"
                . ($cond ? " WHERE $cond" : '') . " ORDER BY o.ordered_at DESC", $params
            ),
            'currentStatus' => $status,
        ];
        $this->adminView('orders/index', $data);
    }

    public function orderDetail($id) {
        $orderModel = $this->model('Order');
        $order = $orderModel->queryOne(
            "SELECT o.*, u.full_name, u.email, u.phone as user_phone
             FROM orders o JOIN users u ON u.user_id = o.user_id WHERE o.order_id = ?", [$id]
        );
        if (!$order) { $this->redirect('admin/orders'); return; }

        $data = [
            'pageTitle' => 'Chi tiết đơn #' . $order->order_code . ' - Admin',
            'order'     => $order,
            'items'     => $orderModel->getOrderItems($id),
        ];
        $this->adminView('orders/detail', $data);
    }

    public function updateOrderStatus($id) {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderModel = $this->model('Order');
            $newStatus = $_POST['order_status'] ?? '';
            $orderModel->update($id, [
                'order_status' => $newStatus,
                'confirmed_at' => $newStatus === 'confirmed' ? date('Y-m-d H:i:s') : null,
                'shipped_at'   => $newStatus === 'shipping' ? date('Y-m-d H:i:s') : null,
                'delivered_at' => $newStatus === 'delivered' ? date('Y-m-d H:i:s') : null,
                'cancelled_at' => $newStatus === 'cancelled' ? date('Y-m-d H:i:s') : null,
            ]);
            $this->setFlash('success', 'Cập nhật trạng thái đơn hàng thành công!');
        }
        $this->redirect('admin/orders/detail/' . $id);
    }

    // ============ USERS ============
    public function users() {
        $userModel = $this->model('User');
        $data = [
            'pageTitle' => 'Quản lý người dùng - Admin',
            'users'     => $userModel->findAll('', [], 'created_at DESC'),
        ];
        $this->adminView('users/index', $data);
    }

    public function toggleUser($id) {
        $this->requireAdmin();
        $userModel = $this->model('User');
        $user = $userModel->findById($id);
        if ($user) {
            $userModel->update($id, ['is_active' => $user->is_active ? 0 : 1]);
            $this->setFlash('success', 'Cập nhật trạng thái người dùng thành công!');
        }
        $this->redirect('admin/users');
    }

    public function deleteUser($id) {
        $this->requireAdmin();
        $userModel = $this->model('User');
        $user = $userModel->findById($id);
        if ($user) {
            try {
                $db = Database::getInstance()->getConnection();
                $stmt = $db->prepare("DELETE FROM users WHERE user_id = ?");
                $stmt->execute([$id]);
                $this->setFlash('success', 'Xóa người dùng thành công!');
            } catch (\PDOException $e) {
                $this->setFlash('error', 'Không thể xóa người dùng này vì họ đã có dữ liệu liên quan (đơn hàng, v.v.).');
            }
        }
        $this->redirect('admin/users');
    }

    // ============ HELPER ============
    private function createSlug($str) {
        $str = mb_strtolower($str, 'UTF-8');
        $str = preg_replace('/[àáạảãâầấậẩẫăằắặẳẵ]/u', 'a', $str);
        $str = preg_replace('/[èéẹẻẽêềếệểễ]/u', 'e', $str);
        $str = preg_replace('/[ìíịỉĩ]/u', 'i', $str);
        $str = preg_replace('/[òóọỏõôồốộổỗơờớợởỡ]/u', 'o', $str);
        $str = preg_replace('/[ùúụủũưừứựửữ]/u', 'u', $str);
        $str = preg_replace('/[ỳýỵỷỹ]/u', 'y', $str);
        $str = preg_replace('/đ/u', 'd', $str);
        $str = preg_replace('/[^a-z0-9\s-]/', '', $str);
        $str = preg_replace('/[\s-]+/', '-', $str);
        return trim($str, '-') . '-' . time();
    }

    private function uploadSingleImage($fileKey, $destinationDir = '/images/books/') {
        if (!isset($_FILES[$fileKey]) || $_FILES[$fileKey]['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $file = $_FILES[$fileKey];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        
        if (!in_array($file['type'], $allowedTypes)) return null;
        if ($file['size'] > 5 * 1024 * 1024) return null;

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = uniqid() . '_' . time() . '.' . $extension;
        $uploadDir = BASE_PATH . '/public' . $destinationDir;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        if (move_uploaded_file($file['tmp_name'], $uploadDir . $fileName)) {
            return $destinationDir . $fileName;
        }
        return null;
    }

    private function uploadMultipleImages($fileKey, $destinationDir = '/images/books/') {
        $uploadedUrls = [];
        if (!isset($_FILES[$fileKey]) || empty($_FILES[$fileKey]['name'][0])) {
            return $uploadedUrls;
        }

        $files = $_FILES[$fileKey];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        $uploadDir = BASE_PATH . '/public' . $destinationDir;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        for ($i = 0; $i < count($files['name']); $i++) {
            if ($files['error'][$i] !== UPLOAD_ERR_OK) continue;
            if (!in_array($files['type'][$i], $allowedTypes)) continue;
            if ($files['size'][$i] > 5 * 1024 * 1024) continue;

            $extension = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
            $fileName = uniqid() . '_' . time() . '_' . $i . '.' . $extension;

            if (move_uploaded_file($files['tmp_name'][$i], $uploadDir . $fileName)) {
                $uploadedUrls[] = $destinationDir . $fileName;
            }
        }
        return $uploadedUrls;
    }
}
