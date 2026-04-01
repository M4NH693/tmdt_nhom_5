<?php
class HomeController extends Controller {
    public function index() {
        $bookModel = $this->model('Book');
        $categoryModel = $this->model('Category');

        $knsCategory = $categoryModel->queryOne(
            "SELECT * FROM categories WHERE category_name LIKE '%kỹ năng%' OR category_name LIKE '%phát triển%' LIMIT 1"
        );
        $knsBooks = [];
        if ($knsCategory) {
            $knsBooks = $bookModel->getByCategory($knsCategory->category_id, 4);
        }

        $data = [
            'pageTitle'    => 'BookStore - Hiệu sách trực tuyến',
            'featured'     => $bookModel->getFeatured(8),
            'newArrivals'  => $bookModel->getNewArrivals(8),
            'categories'   => $categoryModel->getWithBookCount(),
            'knsCategory'  => $knsCategory,
            'knsBooks'     => $knsBooks,
        ];

        $this->view('home/index', $data);
    }
}
