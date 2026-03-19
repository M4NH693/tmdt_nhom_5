<?php
class Book extends Model {
    protected $table = 'books';

    protected function getPrimaryKey() {
        return 'book_id';
    }

    public function getFeatured($limit = 8) {
        return $this->query(
            "SELECT b.*, GROUP_CONCAT(a.author_name SEPARATOR ', ') as authors
             FROM books b
             LEFT JOIN book_authors ba ON ba.book_id = b.book_id
             LEFT JOIN authors a ON a.author_id = ba.author_id
             WHERE b.is_active = 1
             GROUP BY b.book_id
             ORDER BY b.total_sold DESC
             LIMIT ?", [$limit]
        );
    }

    public function getNewArrivals($limit = 8) {
        return $this->query(
            "SELECT b.*, GROUP_CONCAT(a.author_name SEPARATOR ', ') as authors
             FROM books b
             LEFT JOIN book_authors ba ON ba.book_id = b.book_id
             LEFT JOIN authors a ON a.author_id = ba.author_id
             WHERE b.is_active = 1
             GROUP BY b.book_id
             ORDER BY b.created_at DESC
             LIMIT ?", [$limit]
        );
    }

    public function getByCategory($categoryId, $limit = 12, $offset = 0) {
        return $this->query(
            "SELECT b.*, GROUP_CONCAT(a.author_name SEPARATOR ', ') as authors
             FROM books b
             LEFT JOIN book_authors ba ON ba.book_id = b.book_id
             LEFT JOIN authors a ON a.author_id = ba.author_id
             WHERE b.is_active = 1 AND b.category_id = ?
             GROUP BY b.book_id
             ORDER BY b.created_at DESC
             LIMIT ? OFFSET ?", [$categoryId, $limit, $offset]
        );
    }

    public function getDetail($id) {
        return $this->queryOne(
            "SELECT b.*, p.publisher_name, c.category_name,
                    GROUP_CONCAT(a.author_name SEPARATOR ', ') as authors
             FROM books b
             LEFT JOIN publishers p ON p.publisher_id = b.publisher_id
             LEFT JOIN categories c ON c.category_id = b.category_id
             LEFT JOIN book_authors ba ON ba.book_id = b.book_id
             LEFT JOIN authors a ON a.author_id = ba.author_id
             WHERE b.book_id = ?
             GROUP BY b.book_id", [$id]
        );
    }

    public function search($keyword, $limit = 12, $offset = 0) {
        $kw = "%{$keyword}%";
        return $this->query(
            "SELECT b.*, GROUP_CONCAT(a.author_name SEPARATOR ', ') as authors
             FROM books b
             LEFT JOIN book_authors ba ON ba.book_id = b.book_id
             LEFT JOIN authors a ON a.author_id = ba.author_id
             WHERE b.is_active = 1 AND (b.title LIKE ? OR a.author_name LIKE ?)
             GROUP BY b.book_id
             ORDER BY b.total_sold DESC
             LIMIT ? OFFSET ?", [$kw, $kw, $limit, $offset]
        );
    }

    public function countSearch($keyword) {
        $kw = "%{$keyword}%";
        return $this->queryOne(
            "SELECT COUNT(DISTINCT b.book_id) as total
             FROM books b
             LEFT JOIN book_authors ba ON ba.book_id = b.book_id
             LEFT JOIN authors a ON a.author_id = ba.author_id
             WHERE b.is_active = 1 AND (b.title LIKE ? OR a.author_name LIKE ?)", [$kw, $kw]
        )->total;
    }

    public function getAll($limit = 12, $offset = 0, $sort = 'newest') {
        $orderBy = match($sort) {
            'price_asc'  => 'b.price ASC',
            'price_desc' => 'b.price DESC',
            'popular'    => 'b.total_sold DESC',
            'rating'     => 'b.avg_rating DESC',
            default      => 'b.created_at DESC',
        };

        return $this->query(
            "SELECT b.*, GROUP_CONCAT(a.author_name SEPARATOR ', ') as authors
             FROM books b
             LEFT JOIN book_authors ba ON ba.book_id = b.book_id
             LEFT JOIN authors a ON a.author_id = ba.author_id
             WHERE b.is_active = 1
             GROUP BY b.book_id
             ORDER BY {$orderBy}
             LIMIT ? OFFSET ?", [$limit, $offset]
        );
    }

    public function countAll() {
        return $this->count('is_active = 1');
    }

    public function getRelated($bookId, $categoryId, $limit = 4) {
        return $this->query(
            "SELECT b.*, GROUP_CONCAT(a.author_name SEPARATOR ', ') as authors
             FROM books b
             LEFT JOIN book_authors ba ON ba.book_id = b.book_id
             LEFT JOIN authors a ON a.author_id = ba.author_id
             WHERE b.is_active = 1 AND b.category_id = ? AND b.book_id != ?
             GROUP BY b.book_id
             ORDER BY RAND()
             LIMIT ?", [$categoryId, $bookId, $limit]
        );
    }

    public function getImages($bookId) {
        return $this->query(
            "SELECT * FROM book_images WHERE book_id = ? ORDER BY sort_order", [$bookId]
        );
    }

    public function getReviews($bookId) {
        return $this->query(
            "SELECT r.*, u.full_name, u.avatar_url
             FROM reviews r
             JOIN users u ON u.user_id = r.user_id
             WHERE r.book_id = ?
             ORDER BY r.created_at DESC", [$bookId]
        );
    }

    public function hasReviewedBook($userId, $bookId) {
        $result = $this->queryOne(
            "SELECT COUNT(*) as count FROM reviews WHERE user_id = ? AND book_id = ?",
            [$userId, $bookId]
        );
        return $result && $result->count > 0;
    }
}
