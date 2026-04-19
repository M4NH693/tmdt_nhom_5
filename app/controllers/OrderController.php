<?php
class OrderController extends Controller {

    public function checkout() {
        $this->requireLogin();
        $cartModel = $this->model('Cart');
        $cartItems = $cartModel->getByUser($_SESSION['user_id']);

        if (empty($cartItems)) {
            $this->setFlash('error', 'Giỏ hàng trống.');
            $this->redirect('cart');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // --- Stock quantity validation before placing order ---
            $bookModel = $this->model('Book');
            $stockErrors = [];
            foreach ($cartItems as $item) {
                $bookDetail = $bookModel->getDetail($item->book_id);
                if ($bookDetail && $item->quantity > $bookDetail->stock_quantity) {
                    $stockErrors[] = "\"" . $item->title . "\" chỉ còn " . $bookDetail->stock_quantity . " sản phẩm (bạn đặt " . $item->quantity . ")";
                }
            }

            if (!empty($stockErrors)) {
                $errorMsg = "Đơn đặt hàng vượt quá số lượng trong kho, nếu bạn vẫn muốn đặt thì hãy liên hệ với gmail: book4u@gmail.com";
                $this->setFlash('error', $errorMsg);
                $this->redirect('cart');
                return;
            }
            // --- End stock validation ---

            $orderModel = $this->model('Order');
            $subtotal = $cartModel->getCartTotal($_SESSION['user_id']);
            $shippingFee = $subtotal >= 300000 ? 0 : 30000; // Miễn ship cho đơn >= 300k

            $orderData = [
                'user_id'          => $_SESSION['user_id'],
                'receiver_name'    => trim($_POST['receiver_name'] ?? ''),
                'receiver_phone'   => trim($_POST['receiver_phone'] ?? ''),
                'shipping_address' => trim($_POST['shipping_address'] ?? ''),
                'subtotal'         => $subtotal,
                'shipping_fee'     => $shippingFee,
                'total_amount'     => $subtotal + $shippingFee,
                'payment_method'   => $_POST['payment_method'] ?? 'cod',
                'note'             => trim($_POST['note'] ?? ''),
            ];

            $items = [];
            foreach ($cartItems as $item) {
                $items[] = [
                    'book_id'    => $item->book_id,
                    'quantity'   => $item->quantity,
                    'unit_price' => $item->price,
                ];
            }

            $orderId = $orderModel->createOrder($orderData, $items);

            if ($orderId) {
                $cartModel->clearCart($_SESSION['user_id']);
                $this->setFlash('success', 'Đặt hàng thành công! Mã đơn: ORD-' . $orderId);
                $this->redirect('orders');
                return;
            } else {
                $this->setFlash('error', 'Có lỗi xảy ra, vui lòng thử lại.');
            }
        }

        $data = [
            'pageTitle'  => 'Thanh toán - BookStore',
            'cartItems'  => $cartItems,
            'cartTotal'  => $cartModel->getCartTotal($_SESSION['user_id']),
        ];

        $this->view('orders/checkout', $data);
    }

    public function history() {
        $this->requireLogin();
        $orderModel = $this->model('Order');
        $orders = $orderModel->getByUser($_SESSION['user_id']);

        // Load items for each order
        foreach ($orders as $order) {
            $order->items = $orderModel->getOrderItems($order->order_id);
        }

        $data = [
            'pageTitle' => 'Lịch sử đơn hàng - BookStore',
            'orders'    => $orders,
        ];

        $this->view('orders/history', $data);
    }

    public function cancel($id) {
        $this->requireLogin();
        $orderModel = $this->model('Order');
        $order = $orderModel->findById($id);

        // Kiểm tra đơn hàng thuộc về user và đang ở trạng thái pending
        if (!$order || $order->user_id != $_SESSION['user_id'] || $order->order_status !== 'pending') {
            $this->setFlash('error', 'Không thể hủy đơn hàng này.');
            $this->redirect('orders');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderModel->update($id, [
                'order_status' => 'cancelled',
                'cancelled_at' => date('Y-m-d H:i:s'),
            ]);
            $this->setFlash('success', 'Đã hủy đơn hàng ' . $order->order_code . ' thành công.');
        }
        $this->redirect('orders');
    }

    public function updateAddress($id) {
        $this->requireLogin();
        $orderModel = $this->model('Order');
        $order = $orderModel->findById($id);

        // Kiểm tra đơn hàng thuộc về user và đang ở trạng thái pending
        if (!$order || $order->user_id != $_SESSION['user_id'] || $order->order_status !== 'pending') {
            $this->setFlash('error', 'Không thể cập nhật đơn hàng này.');
            $this->redirect('orders');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $receiverName = trim($_POST['receiver_name'] ?? '');
            $receiverPhone = trim($_POST['receiver_phone'] ?? '');
            $shippingAddress = trim($_POST['shipping_address'] ?? '');

            if (empty($receiverName) || empty($receiverPhone) || empty($shippingAddress)) {
                $this->setFlash('error', 'Vui lòng điền đầy đủ thông tin.');
                $this->redirect('orders');
                return;
            }

            $orderModel->update($id, [
                'receiver_name'    => $receiverName,
                'receiver_phone'   => $receiverPhone,
                'shipping_address' => $shippingAddress,
            ]);
            $this->setFlash('success', 'Cập nhật địa chỉ giao hàng thành công!');
        }
        $this->redirect('orders');
    }
}
