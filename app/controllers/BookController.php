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

        $data = [
            'pageTitle' => $book->title . ' - BookStore',
            'book'      => $book,
            'images'    => $bookModel->getImages($id),
            'reviews'   => $bookModel->getReviews($id),
            'related'   => $bookModel->getRelated($id, $book->category_id, 4),
        ];

        $this->view('books/detail', $data);
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
