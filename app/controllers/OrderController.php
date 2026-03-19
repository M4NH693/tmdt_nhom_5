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
}
