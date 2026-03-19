<?php
class BookController extends Controller {

    public function index() {
        $bookModel = $this->model('Book');
        $categoryModel = $this->model('Category');

        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
        $limit = 12;
        $offset = ($page - 1) * $limit;

        $totalBooks = $bookModel->countAll();
        $totalPages = ceil($totalBooks / $limit);

        $data = [
            'pageTitle'   => 'Tất cả sách - BookStore',
            'books'       => $bookModel->getAll($limit, $offset, $sort),
            'categories'  => $categoryModel->getAllActive(),
            'currentPage' => $page,
            'totalPages'  => $totalPages,
            'sort'        => $sort,
        ];

        $this->view('books/index', $data);
    }

    public function detail($id) {
        $bookModel = $this->model('Book');
        $book = $bookModel->getDetail($id);

        if (!$book) {
            $this->redirect('books');
            return;
        }

        $canReview = false;
        if ($this->isLoggedIn()) {
            $orderModel = $this->model('Order');
            $hasPurchased = $orderModel->hasPurchasedBook($_SESSION['user_id'], $id);
            $hasReviewed = $bookModel->hasReviewedBook($_SESSION['user_id'], $id);
            if ($hasPurchased && !$hasReviewed) {
                $canReview = true;
            }
        }

        $data = [
            'pageTitle' => $book->title . ' - BookStore',
            'book'      => $book,
            'images'    => $bookModel->getImages($id),
            'reviews'   => $bookModel->getReviews($id),
            'related'   => $bookModel->getRelated($id, $book->category_id, 4),
            'canReview' => $canReview,
        ];

        $this->view('books/detail', $data);
    }

    public function submitReview($id) {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderModel = $this->model('Order');
            $bookModel = $this->model('Book');

            $hasPurchased = $orderModel->hasPurchasedBook($_SESSION['user_id'], $id);
            $hasReviewed = $bookModel->hasReviewedBook($_SESSION['user_id'], $id);

            if (!$hasPurchased) {
                $this->setFlash('error', 'Bạn phải mua sách này trước khi đánh giá.');
            } elseif ($hasReviewed) {
                $this->setFlash('error', 'Bạn đã đánh giá sách này rồi.');
            } else {
                $rating = max(1, min(5, (int)($_POST['rating'] ?? 5)));
                $comment = trim($_POST['comment'] ?? '');

                $db = Database::getInstance()->getConnection();
                $stmt = $db->prepare("INSERT INTO reviews (user_id, book_id, rating, comment) VALUES (?, ?, ?, ?)");
                $stmt->execute([$_SESSION['user_id'], $id, $rating, $comment]);

                // Update aggregate avg_rating
                $stmt = $db->prepare("UPDATE books SET avg_rating = (SELECT AVG(rating) FROM reviews WHERE book_id = ?) WHERE book_id = ?");
                $stmt->execute([$id, $id]);

                $this->setFlash('success', 'Cảm ơn bạn đã đánh giá sách!');
            }
        }

        $this->redirect('book/' . $id);
    }

    public function category($id) {
        $bookModel = $this->model('Book');
        $categoryModel = $this->model('Category');

        $category = $categoryModel->findById($id);
        if (!$category) {
            $this->redirect('books');
            return;
        }

        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = 12;
        $offset = ($page - 1) * $limit;

        $data = [
            'pageTitle'   => $category->category_name . ' - BookStore',
            'category'    => $category,
            'books'       => $bookModel->getByCategory($id, $limit, $offset),
            'categories'  => $categoryModel->getAllActive(),
            'currentPage' => $page,
        ];

        $this->view('books/index', $data);
    }

    public function search() {
        $bookModel = $this->model('Book');
        $keyword = isset($_GET['q']) ? trim($_GET['q']) : '';
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = 12;
        $offset = ($page - 1) * $limit;

        $totalBooks = $bookModel->countSearch($keyword);
        $totalPages = ceil($totalBooks / $limit);

        $data = [
            'pageTitle'   => "Tìm kiếm: {$keyword} - BookStore",
            'books'       => $bookModel->search($keyword, $limit, $offset),
            'keyword'     => $keyword,
            'totalBooks'  => $totalBooks,
            'currentPage' => $page,
            'totalPages'  => $totalPages,
        ];

        $this->view('books/search', $data);
    }

    public function ajaxSearch() {
        header('Content-Type: application/json');
        $keyword = isset($_GET['q']) ? trim($_GET['q']) : '';
        
        if (empty($keyword)) {
            echo json_encode([]);
            return;
        }

        $bookModel = $this->model('Book');
        $books = $bookModel->search($keyword, 5, 0); // Limit to 5 suggestions
        
        echo json_encode($books);
        exit;
    }
}
