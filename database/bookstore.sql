-- =====================================================
-- DATABASE: BOOKSTORE (Hệ thống bán sách)
-- Tạo ngày: 2026-03-16
-- Mô tả: Cơ sở dữ liệu cho hệ thống bán sách trực tuyến
--         - Đăng ký bằng email, mật khẩu lưu dạng hash
--         - Không có phần khuyến mãi
-- =====================================================

DROP DATABASE IF EXISTS bookstore;
CREATE DATABASE bookstore
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE bookstore;

-- =====================================================
-- 1. BẢNG NGƯỜI DÙNG (users)
-- Lưu thông tin tài khoản, đăng ký bằng email
-- Mật khẩu lưu dạng hash (VARCHAR 255 cho bcrypt/argon2)
-- =====================================================
CREATE TABLE users (
    user_id       INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email         VARCHAR(255) NOT NULL,
    password_hash VARCHAR(255) NOT NULL COMMENT 'Mật khẩu đã được hash (bcrypt/argon2)',
    full_name     NVARCHAR(150) NOT NULL,
    phone         VARCHAR(20) DEFAULT NULL,
    avatar_url    VARCHAR(500) DEFAULT NULL,
    role          ENUM('customer', 'admin') NOT NULL DEFAULT 'customer',
    is_active     TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1 = hoạt động, 0 = bị khóa',
    reset_token         VARCHAR(64) DEFAULT NULL COMMENT 'Token dùng để đặt lại mật khẩu',
    reset_expires_at    DATETIME DEFAULT NULL COMMENT 'Thời hạn hiệu lực của token',
    created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE INDEX uq_users_email (email),
    INDEX idx_users_role (role)
) ENGINE=InnoDB;

-- =====================================================
-- 2. BẢNG ĐỊA CHỈ GIAO HÀNG (addresses)
-- Mỗi user có thể có nhiều địa chỉ, 1 địa chỉ mặc định
-- =====================================================
CREATE TABLE addresses (
    address_id    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id       INT UNSIGNED NOT NULL,
    receiver_name NVARCHAR(150) NOT NULL,
    phone         VARCHAR(20) NOT NULL,
    province      NVARCHAR(100) NOT NULL,
    district      NVARCHAR(100) NOT NULL,
    ward          NVARCHAR(100) NOT NULL,
    street_detail NVARCHAR(255) NOT NULL,
    is_default    TINYINT(1) NOT NULL DEFAULT 0,
    created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_addresses_user (user_id),
    CONSTRAINT fk_addresses_user FOREIGN KEY (user_id)
        REFERENCES users(user_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- =====================================================
-- 3. BẢNG THỂ LOẠI SÁCH (categories)
-- Hỗ trợ cây phân cấp (danh mục cha - con)
-- =====================================================
CREATE TABLE categories (
    category_id   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_name NVARCHAR(150) NOT NULL,
    slug          VARCHAR(200) NOT NULL,
    parent_id     INT UNSIGNED DEFAULT NULL COMMENT 'NULL = danh mục gốc',
    description   NVARCHAR(500) DEFAULT NULL,
    is_active     TINYINT(1) NOT NULL DEFAULT 1,
    created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE INDEX uq_categories_slug (slug),
    INDEX idx_categories_parent (parent_id),
    CONSTRAINT fk_categories_parent FOREIGN KEY (parent_id)
        REFERENCES categories(category_id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

-- =====================================================
-- 4. BẢNG TÁC GIẢ (authors)
-- =====================================================
CREATE TABLE authors (
    author_id  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    author_name NVARCHAR(200) NOT NULL,
    bio         TEXT DEFAULT NULL,
    avatar_url  VARCHAR(500) DEFAULT NULL,
    created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_authors_name (author_name)
) ENGINE=InnoDB;

-- =====================================================
-- 5. BẢNG NHÀ XUẤT BẢN (publishers)
-- =====================================================
CREATE TABLE publishers (
    publisher_id   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    publisher_name NVARCHAR(200) NOT NULL,
    address        NVARCHAR(300) DEFAULT NULL,
    phone          VARCHAR(20) DEFAULT NULL,
    email          VARCHAR(255) DEFAULT NULL,
    website        VARCHAR(300) DEFAULT NULL,
    created_at     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE INDEX uq_publishers_name (publisher_name)
) ENGINE=InnoDB;

-- =====================================================
-- 6. BẢNG SÁCH (books)
-- =====================================================
CREATE TABLE books (
    book_id        INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title          NVARCHAR(300) NOT NULL,
    slug           VARCHAR(350) NOT NULL,
    isbn           VARCHAR(20) DEFAULT NULL,
    publisher_id   INT UNSIGNED DEFAULT NULL,
    category_id    INT UNSIGNED DEFAULT NULL,
    publication_year SMALLINT UNSIGNED DEFAULT NULL,
    pages          SMALLINT UNSIGNED DEFAULT NULL,
    language       VARCHAR(50) DEFAULT 'Tiếng Việt',
    weight_grams   SMALLINT UNSIGNED DEFAULT NULL COMMENT 'Trọng lượng (gram)',
    dimensions     VARCHAR(50) DEFAULT NULL COMMENT 'Kích thước VD: 20x15x2 cm',
    price          DECIMAL(12,2) NOT NULL COMMENT 'Giá bán (VNĐ)',
    stock_quantity INT UNSIGNED NOT NULL DEFAULT 0,
    description    TEXT DEFAULT NULL,
    cover_image    VARCHAR(500) DEFAULT NULL,
    is_active      TINYINT(1) NOT NULL DEFAULT 1,
    avg_rating     DECIMAL(2,1) NOT NULL DEFAULT 0.0 COMMENT 'Điểm đánh giá trung bình (0-5)',
    total_sold     INT UNSIGNED NOT NULL DEFAULT 0,
    created_at     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE INDEX uq_books_slug (slug),
    UNIQUE INDEX uq_books_isbn (isbn),
    INDEX idx_books_category (category_id),
    INDEX idx_books_publisher (publisher_id),
    INDEX idx_books_price (price),
    INDEX idx_books_active_sold (is_active, total_sold DESC),

    CONSTRAINT fk_books_publisher FOREIGN KEY (publisher_id)
        REFERENCES publishers(publisher_id) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_books_category FOREIGN KEY (category_id)
        REFERENCES categories(category_id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

-- =====================================================
-- 7. BẢNG HÌNH ẢNH SÁCH (book_images)
-- Mỗi sách có thể có nhiều hình ảnh
-- =====================================================
CREATE TABLE book_images (
    image_id   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    book_id    INT UNSIGNED NOT NULL,
    image_url  VARCHAR(500) NOT NULL,
    sort_order TINYINT UNSIGNED NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_book_images_book (book_id),
    CONSTRAINT fk_book_images_book FOREIGN KEY (book_id)
        REFERENCES books(book_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- =====================================================
-- 8. BẢNG SÁCH - TÁC GIẢ (book_authors)  [N-N]
-- Một sách có thể có nhiều tác giả, 1 tác giả có nhiều sách
-- =====================================================
CREATE TABLE book_authors (
    book_id   INT UNSIGNED NOT NULL,
    author_id INT UNSIGNED NOT NULL,

    PRIMARY KEY (book_id, author_id),
    INDEX idx_book_authors_author (author_id),
    CONSTRAINT fk_ba_book FOREIGN KEY (book_id)
        REFERENCES books(book_id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_ba_author FOREIGN KEY (author_id)
        REFERENCES authors(author_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- =====================================================
-- 9. BẢNG ĐÁNH GIÁ (reviews)
-- Mỗi user chỉ được đánh giá 1 lần / sách
-- =====================================================
CREATE TABLE reviews (
    review_id  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    book_id    INT UNSIGNED NOT NULL,
    user_id    INT UNSIGNED NOT NULL,
    rating     TINYINT UNSIGNED NOT NULL COMMENT '1-5 sao',
    comment    TEXT DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE INDEX uq_reviews_user_book (user_id, book_id),
    INDEX idx_reviews_book (book_id),

    CONSTRAINT fk_reviews_book FOREIGN KEY (book_id)
        REFERENCES books(book_id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_reviews_user FOREIGN KEY (user_id)
        REFERENCES users(user_id) ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT chk_reviews_rating CHECK (rating BETWEEN 1 AND 5)
) ENGINE=InnoDB;

-- =====================================================
-- 10. BẢNG GIỎ HÀNG (cart_items)
-- Giỏ hàng gắn trực tiếp với user (không cần bảng cart riêng)
-- =====================================================
CREATE TABLE cart_items (
    cart_item_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id      INT UNSIGNED NOT NULL,
    book_id      INT UNSIGNED NOT NULL,
    quantity     SMALLINT UNSIGNED NOT NULL DEFAULT 1,
    added_at     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    UNIQUE INDEX uq_cart_user_book (user_id, book_id),
    INDEX idx_cart_book (book_id),

    CONSTRAINT fk_cart_user FOREIGN KEY (user_id)
        REFERENCES users(user_id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_cart_book FOREIGN KEY (book_id)
        REFERENCES books(book_id) ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT chk_cart_quantity CHECK (quantity >= 1)
) ENGINE=InnoDB;

-- =====================================================
-- 11. BẢNG ĐƠN HÀNG (orders)
-- =====================================================
CREATE TABLE orders (
    order_id        INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_code      VARCHAR(30) NOT NULL COMMENT 'Mã đơn hàng hiển thị (VD: ORD-20260316-001)',
    user_id         INT UNSIGNED NOT NULL,
    receiver_name   NVARCHAR(150) NOT NULL,
    receiver_phone  VARCHAR(20) NOT NULL,
    shipping_address NVARCHAR(500) NOT NULL,
    subtotal        DECIMAL(14,2) NOT NULL COMMENT 'Tổng tiền hàng',
    shipping_fee    DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    total_amount    DECIMAL(14,2) NOT NULL COMMENT 'Tổng thanh toán = subtotal + shipping_fee',
    payment_method  ENUM('cod', 'bank_transfer', 'e_wallet', 'credit_card') NOT NULL DEFAULT 'cod',
    payment_status  ENUM('unpaid', 'paid', 'refunded') NOT NULL DEFAULT 'unpaid',
    order_status    ENUM('pending', 'confirmed', 'shipping', 'delivered', 'cancelled', 'returned')
                    NOT NULL DEFAULT 'pending',
    note            NVARCHAR(500) DEFAULT NULL,
    ordered_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    confirmed_at    DATETIME DEFAULT NULL,
    shipped_at      DATETIME DEFAULT NULL,
    delivered_at    DATETIME DEFAULT NULL,
    cancelled_at    DATETIME DEFAULT NULL,
    updated_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE INDEX uq_orders_code (order_code),
    INDEX idx_orders_user (user_id),
    INDEX idx_orders_status (order_status),
    INDEX idx_orders_payment (payment_status),
    INDEX idx_orders_date (ordered_at),

    CONSTRAINT fk_orders_user FOREIGN KEY (user_id)
        REFERENCES users(user_id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

-- =====================================================
-- 12. BẢNG CHI TIẾT ĐƠN HÀNG (order_items)
-- =====================================================
CREATE TABLE order_items (
    order_item_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id      INT UNSIGNED NOT NULL,
    book_id       INT UNSIGNED NOT NULL,
    quantity      SMALLINT UNSIGNED NOT NULL,
    unit_price    DECIMAL(12,2) NOT NULL COMMENT 'Giá tại thời điểm mua',
    total_price   DECIMAL(14,2) NOT NULL COMMENT 'quantity x unit_price',

    INDEX idx_oi_order (order_id),
    INDEX idx_oi_book (book_id),

    CONSTRAINT fk_oi_order FOREIGN KEY (order_id)
        REFERENCES orders(order_id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_oi_book FOREIGN KEY (book_id)
        REFERENCES books(book_id) ON DELETE RESTRICT ON UPDATE CASCADE,

    CONSTRAINT chk_oi_quantity CHECK (quantity >= 1)
) ENGINE=InnoDB;

-- =====================================================
-- 13. BẢNG LỊCH SỬ TRẠNG THÁI ĐƠN HÀNG (order_status_history)
-- Theo dõi lịch sử chuyển trạng thái đơn hàng
-- =====================================================
CREATE TABLE order_status_history (
    history_id  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id    INT UNSIGNED NOT NULL,
    old_status  ENUM('pending', 'confirmed', 'shipping', 'delivered', 'cancelled', 'returned') DEFAULT NULL,
    new_status  ENUM('pending', 'confirmed', 'shipping', 'delivered', 'cancelled', 'returned') NOT NULL,
    changed_by  INT UNSIGNED DEFAULT NULL COMMENT 'user_id của người thay đổi (admin)',
    note        NVARCHAR(300) DEFAULT NULL,
    changed_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_osh_order (order_id),
    CONSTRAINT fk_osh_order FOREIGN KEY (order_id)
        REFERENCES orders(order_id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_osh_user FOREIGN KEY (changed_by)
        REFERENCES users(user_id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

-- =====================================================
-- 14. BẢNG WISHLIST (sách yêu thích)
-- =====================================================
CREATE TABLE wishlists (
    wishlist_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id     INT UNSIGNED NOT NULL,
    book_id     INT UNSIGNED NOT NULL,
    added_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    UNIQUE INDEX uq_wishlist_user_book (user_id, book_id),
    INDEX idx_wishlist_book (book_id),

    CONSTRAINT fk_wishlist_user FOREIGN KEY (user_id)
        REFERENCES users(user_id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_wishlist_book FOREIGN KEY (book_id)
        REFERENCES books(book_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- =====================================================
-- TRIGGER: Cập nhật avg_rating sau khi thêm/sửa/xóa review
-- =====================================================

DELIMITER //

CREATE TRIGGER trg_reviews_after_insert
AFTER INSERT ON reviews
FOR EACH ROW
BEGIN
    UPDATE books
    SET avg_rating = (
        SELECT COALESCE(AVG(rating), 0) FROM reviews WHERE book_id = NEW.book_id
    )
    WHERE book_id = NEW.book_id;
END //

CREATE TRIGGER trg_reviews_after_update
AFTER UPDATE ON reviews
FOR EACH ROW
BEGIN
    UPDATE books
    SET avg_rating = (
        SELECT COALESCE(AVG(rating), 0) FROM reviews WHERE book_id = NEW.book_id
    )
    WHERE book_id = NEW.book_id;
END //

CREATE TRIGGER trg_reviews_after_delete
AFTER DELETE ON reviews
FOR EACH ROW
BEGIN
    UPDATE books
    SET avg_rating = (
        SELECT COALESCE(AVG(rating), 0) FROM reviews WHERE book_id = OLD.book_id
    )
    WHERE book_id = OLD.book_id;
END //

-- =====================================================
-- TRIGGER: Cập nhật total_sold & trừ stock khi đơn hàng chuyển 'delivered'
-- =====================================================
CREATE TRIGGER trg_orders_after_update_status
AFTER UPDATE ON orders
FOR EACH ROW
BEGIN
    -- Khi đơn hàng được giao thành công
    IF OLD.order_status != 'delivered' AND NEW.order_status = 'delivered' THEN
        UPDATE books b
        INNER JOIN order_items oi ON oi.book_id = b.book_id
        SET b.total_sold = b.total_sold + oi.quantity,
            b.stock_quantity = GREATEST(CAST(b.stock_quantity AS SIGNED) - CAST(oi.quantity AS SIGNED), 0)
        WHERE oi.order_id = NEW.order_id;
    END IF;
END //

DELIMITER ;

-- =====================================================
-- VIEW: Thống kê đơn hàng theo tháng
-- =====================================================
CREATE VIEW vw_monthly_revenue AS
SELECT
    YEAR(ordered_at)  AS order_year,
    MONTH(ordered_at) AS order_month,
    COUNT(*)          AS total_orders,
    SUM(CASE WHEN order_status = 'delivered' THEN 1 ELSE 0 END) AS delivered_orders,
    SUM(CASE WHEN order_status = 'cancelled' THEN 1 ELSE 0 END) AS cancelled_orders,
    SUM(CASE WHEN order_status = 'delivered' THEN total_amount ELSE 0 END) AS revenue
FROM orders
GROUP BY YEAR(ordered_at), MONTH(ordered_at);

-- =====================================================
-- VIEW: Thông tin sách kèm tác giả (gom tên tác giả thành 1 chuỗi)
-- =====================================================
CREATE VIEW vw_book_details AS
SELECT
    b.book_id,
    b.title,
    b.slug,
    b.isbn,
    b.price,
    b.stock_quantity,
    b.avg_rating,
    b.total_sold,
    b.cover_image,
    b.is_active,
    c.category_name,
    p.publisher_name,
    GROUP_CONCAT(a.author_name ORDER BY a.author_name SEPARATOR ', ') AS authors
FROM books b
LEFT JOIN categories c ON c.category_id = b.category_id
LEFT JOIN publishers p ON p.publisher_id = b.publisher_id
LEFT JOIN book_authors ba ON ba.book_id = b.book_id
LEFT JOIN authors a ON a.author_id = ba.author_id
GROUP BY b.book_id;
