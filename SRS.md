# Tài Liệu Đặc Tả Yêu Cầu Phần Mềm (SRS)
## Hệ Thống Web Bán Sách Trực Tuyến — **Book4u**


## Mục Lục

1. [Giới Thiệu](#1-giới-thiệu)
2. [Mô Tả Tổng Quan Hệ Thống](#2-mô-tả-tổng-quan-hệ-thống)
3. [Yêu Cầu Chức Năng](#3-yêu-cầu-chức-năng)
4. [Yêu Cầu Phi Chức Năng](#4-yêu-cầu-phi-chức-năng)
5. [Thiết Kế Cơ Sở Dữ Liệu](#5-thiết-kế-cơ-sở-dữ-liệu)
6. [Kiến Trúc Hệ Thống](#6-kiến-trúc-hệ-thống)
7. [Giao Diện Người Dùng](#7-giao-diện-người-dùng)
8. [Ràng Buộc & Giả Định](#8-ràng-buộc--giả-định)

---

## 1. Giới Thiệu

### 1.1 Mục Đích

Tài liệu này mô tả đầy đủ các yêu cầu chức năng và phi chức năng của hệ thống website bán sách trực tuyến **Book4u**. Đây là tài liệu tham chiếu chính thức cho nhóm phát triển, kiểm thử, và các bên liên quan trong suốt vòng đời dự án.

### 1.2 Phạm Vi

Hệ thống Book4u là một nền tảng thương mại điện tử chuyên biệt cho sách, cung cấp:
- **Giao diện khách hàng**: duyệt, tìm kiếm, mua sách và theo dõi đơn hàng.
- **Giao diện quản trị (Admin Panel)**: quản lý sách, danh mục, đơn hàng, người dùng và thống kê doanh thu.

### 1.3 Đối Tượng Sử Dụng Tài Liệu

| Đối tượng             | Mục đích sử dụng                              |
|-----------------------|-----------------------------------------------|
| Nhóm phát triển       | Triển khai đúng theo đặc tả                   |
| Kiểm thử viên         | Xây dựng test case dựa trên yêu cầu           |
| Giảng viên hướng dẫn  | Đánh giá phạm vi và chất lượng dự án          |

### 1.4 Định Nghĩa & Viết Tắt

| Ký hiệu    | Ý nghĩa                                   |
|------------|-------------------------------------------|
| SRS        | Software Requirements Specification       |
| MVC        | Model – View – Controller                 |
| CRUD       | Create – Read – Update – Delete           |
| COD        | Cash On Delivery (thanh toán khi nhận hàng) |
| NXB        | Nhà Xuất Bản                              |
| Admin      | Người quản trị hệ thống                   |
| Customer   | Khách hàng / người dùng cuối              |
| ISBN       | International Standard Book Number        |

---

## 2. Mô Tả Tổng Quan Hệ Thống

### 2.1 Tổng Quan Sản Phẩm

Book4u là website thương mại điện tử bán sách xây dựng theo mô hình **MVC tùy chỉnh** (PHP thuần). Hệ thống phục vụ hai nhóm người dùng chính: **khách hàng** (customer) và **quản trị viên** (admin).

### 2.2 Các Nhóm Người Dùng

#### 2.2.1 Khách Vãng Lai (Guest)
- Duyệt trang chủ, danh sách sách, chi tiết sách.
- Tìm kiếm sách theo từ khóa.
- Lọc sách theo danh mục.
- **Không thể** thêm vào giỏ hàng hay đặt hàng.

#### 2.2.2 Khách Hàng Đã Đăng Ký (Customer)
- Tất cả quyền của Guest.
- Thêm sách vào giỏ hàng, đặt hàng.
- Theo dõi lịch sử và trạng thái đơn hàng.
- Viết đánh giá sách (chỉ khi đã mua).
- Quản lý thông tin hồ sơ cá nhân.

#### 2.2.3 Quản Trị Viên (Admin)
- Toàn quyền quản lý sách, danh mục, đơn hàng, người dùng.
- Xem thống kê doanh thu theo tháng.
- Tải lên ảnh bìa và ảnh xem trước sách.
- Quản lý trạng thái đơn hàng.

### 2.3 Môi Trường Hoạt Động

| Thành phần       | Công nghệ / Phiên bản  |
|------------------|------------------------|
| Ngôn ngữ backend | PHP 8.3                |
| Cơ sở dữ liệu    | MySQL 8.4              |
| Web server       | Apache (Laragon)       |
| Frontend         | HTML5, CSS3, JavaScript (ES6+) |
| Thư viện JS      | Chart.js, Font Awesome 6 |
| Phông chữ        | Google Fonts (Inter, Outfit) |

---

## 3. Yêu Cầu Chức Năng

### 3.1 Module Xác Thực (Authentication)

#### FR-AUTH-01: Đăng Ký Tài Khoản
- **Mô tả**: Người dùng chưa có tài khoản có thể đăng ký với email, họ tên và mật khẩu.
- **Đầu vào**: `full_name`, `email`, `password`, `confirm_password`.
- **Xử lý**:
  - Kiểm tra email không trùng lặp trong hệ thống.
  - Mật khẩu phải được xác nhận khớp nhau.
  - Lưu mật khẩu dưới dạng **bcrypt hash**.
- **Đầu ra**: Tài khoản được tạo với `role = 'customer'`, chuyển hướng về trang chủ.
- **Điều kiện ngoại lệ**: Hiển thị thông báo lỗi nếu email đã tồn tại.

#### FR-AUTH-02: Đăng Nhập
- **Mô tả**: Người dùng đăng nhập bằng email và mật khẩu.
- **Đầu vào**: `email`, `password`.
- **Xử lý**:
  - Tra cứu email trong CSDL.
  - Xác thực mật khẩu với hàm `password_verify()`.
  - Kiểm tra `is_active = 1` (tài khoản không bị khóa).
  - Lưu thông tin vào `$_SESSION`.
- **Đầu ra**: Chuyển hướng về trang trước hoặc trang chủ.
- **Điều kiện ngoại lệ**: Thông báo lỗi nếu sai thông tin hoặc tài khoản bị khóa.

#### FR-AUTH-03: Đăng Xuất
- **Mô tả**: Hủy phiên làm việc và chuyển về trang chủ.

---

### 3.2 Module Trang Chủ & Duyệt Sách

#### FR-HOME-01: Trang Chủ
- **Mô tả**: Hiển thị banner hero, danh mục nổi bật, sách bán chạy, sách mới nhất.
- **Nội dung hiển thị**:
  - **Hero Section**: Tiêu đề, nút "Khám phá ngay", nút "Đăng ký" (ẩn nếu đã đăng nhập), ảnh bìa 4 sách nổi bật.
  - **Danh mục**: Grid hiển thị tất cả danh mục kèm hình minh họa.
  - **Sách bán chạy**: Tối đa 8 sách sắp xếp theo `total_sold DESC`.
  - **Sách mới**: Tối đa 8 sách sắp xếp theo `created_at DESC`.
  - **Sách kỹ năng sống**: Hiển thị riêng nếu danh mục tồn tại.
  - **Dịch vụ nổi bật**: Giao hàng nhanh, sách chính hãng, đổi trả 30 ngày, hỗ trợ 24/7.

#### FR-HOME-02: Danh Sách Sách
- **Mô tả**: Hiển thị toàn bộ sách với bộ lọc và sắp xếp.
- **Chức năng lọc**:
  - Lọc theo danh mục (`?category_id=X`).
  - Lọc theo khoảng giá (`?min_price=X&max_price=Y`).
- **Chức năng sắp xếp** (`?sort=`):
  - `popular` — Bán chạy nhất.
  - `newest` — Mới nhất.
  - `price_asc` / `price_desc` — Giá tăng dần / giảm dần.
  - `rating` — Đánh giá cao nhất.
- **Phân trang**: Hiển thị 12 sách mỗi trang.

#### FR-HOME-03: Chi Tiết Sách
- **Mô tả**: Trang hiển thị đầy đủ thông tin một cuốn sách.
- **Nội dung**:
  - Ảnh bìa chính + gallery ảnh xem trước (thumbnail slider).
  - Tên sách, tác giả, đánh giá sao, số lượng đã bán.
  - Giá bán, trạng thái tồn kho (còn hàng / hết hàng).
  - Thông tin chi tiết: NXB, năm XB, số trang, ngôn ngữ, ISBN, kích thước.
  - Mô tả sách.
  - Bộ chọn số lượng + nút "Thêm vào giỏ".
  - Phần đánh giá của khách hàng.
  - Sách liên quan (cùng danh mục).

#### FR-HOME-04: Tìm Kiếm Sách
- **Mô tả**: Cho phép tìm kiếm theo từ khóa.
- **Hoạt động**:
  - Ô tìm kiếm trên thanh điều hướng với gợi ý real-time (AJAX).
  - Hiển thị lịch sử tìm kiếm nhanh bên dưới input.
  - Trang kết quả hiển thị danh sách sách khớp với từ khóa (so khớp `title`, `description`).

#### FR-HOME-05: Lọc Theo Danh Mục
- **Mô tả**: Nhấn vào danh mục ở trang chủ hoặc menu để lọc sách.
- **URL**: `/category/{category_id}`.

---

### 3.3 Module Giỏ Hàng (Cart)

#### FR-CART-01: Thêm Vào Giỏ Hàng
- **Mô tả**: Thêm sách với số lượng chỉ định vào giỏ hàng.
- **Điều kiện**: Người dùng phải đăng nhập. Số lượng không vượt quá `stock_quantity`.
- **Xử lý**: Nếu sách đã có trong giỏ, cộng thêm số lượng. Lưu vào bảng `cart_items`.

#### FR-CART-02: Xem Giỏ Hàng
- **Mô tả**: Hiển thị danh sách sách trong giỏ, số lượng, đơn giá, thành tiền và tổng cộng.
- **Chức năng**: Cập nhật số lượng, xóa sản phẩm khỏi giỏ, nút "Tiếp tục mua sắm" và "Đặt hàng".

#### FR-CART-03: Cập Nhật / Xóa Sản Phẩm
- **Mô tả**: Thay đổi số lượng hoặc xóa line item trong giỏ hàng.

---

### 3.4 Module Đặt Hàng & Thanh Toán

#### FR-ORDER-01: Thanh Toán (Checkout)
- **Mô tả**: Người dùng điền thông tin giao hàng và chọn phương thức thanh toán.
- **Đầu vào**:
  - Họ tên người nhận, số điện thoại.
  - Địa chỉ giao hàng (tỉnh/thành, quận/huyện, phường/xã, số nhà).
  - Ghi chú đơn hàng (tùy chọn).
  - Phương thức thanh toán: COD, chuyển khoản ngân hàng, ví điện tử, thẻ tín dụng.
- **Xử lý**:
  - Tính phí vận chuyển: miễn phí nếu subtotal >= 300.000đ, ngược lại 30.000đ.
  - Tạo `order_code` dạng `ORD-YYYYMMDD-XXX`.
  - Lưu vào bảng `orders` và `order_items`.
  - Xóa các sách đã mua khỏi giỏ hàng.
- **Đầu ra**: Chuyển hướng về trang lịch sử đơn hàng + thông báo thành công.

#### FR-ORDER-02: Lịch Sử Đơn Hàng
- **Mô tả**: Hiển thị danh sách tất cả đơn hàng của người dùng đang đăng nhập.
- **Thông tin hiển thị**: Mã đơn, ngày đặt, tổng tiền, trạng thái thanh toán, trạng thái đơn hàng.

#### FR-ORDER-03: Tracking Trạng Thái Đơn Hàng
- **Các trạng thái theo thứ tự tiến trình**:
  1. `pending` — Chờ xác nhận
  2. `confirmed` — Đã xác nhận
  3. `shipping` — Đang giao hàng
  4. `delivered` — Đã giao hàng thành công
  5. `cancelled` — Đã hủy
  6. `returned` — Đã trả hàng

---

### 3.5 Module Đánh Giá Sách (Reviews)

#### FR-REVIEW-01: Viết Đánh Giá
- **Mô tả**: Người dùng đã mua và nhận sách có thể để lại đánh giá.
- **Điều kiện**:
  - Người dùng đã đăng nhập.
  - Đã có đơn hàng trạng thái `delivered` chứa cuốn sách đó.
  - Chưa từng đánh giá cuốn sách này (1 đánh giá / người / sách).
- **Đầu vào**: Số sao (1–5), nội dung bình luận.
- **Xử lý**: Lưu vào bảng `reviews`. Trigger tự động cập nhật `avg_rating` trong bảng `books`.

#### FR-REVIEW-02: Hiển Thị Đánh Giá
- **Mô tả**: Trang chi tiết sách hiển thị danh sách tất cả đánh giá với avatar, tên người dùng, số sao, ngày và nội dung bình luận.

---

### 3.6 Module Tài Khoản Người Dùng

#### FR-ACCOUNT-01: Dashboard Cá Nhân
- **Mô tả**: Người dùng xem và chỉnh sửa thông tin hồ sơ: họ tên, số điện thoại, ảnh đại diện.
- **Chức năng tải ảnh đại diện**: Hỗ trợ JPG, PNG, WebP tối đa 5MB.

---

### 3.7 Module Quản Trị — Sách (Admin Books)

#### FR-ADMIN-BOOK-01: Danh Sách Sách
- **Mô tả**: Hiển thị toàn bộ sách với phân trang (15 sách/trang).
- **Thông tin**: Tên sách, danh mục, giá, tồn kho, trạng thái, hành động (sửa/ẩn).

#### FR-ADMIN-BOOK-02: Thêm Sách
- **Mô tả**: Form thêm sách mới với đầy đủ thông tin.
- **Đầu vào**:
  - Tên sách *(bắt buộc)*, ISBN, danh mục, năm xuất bản, số trang, ngôn ngữ.
  - Giá bán *(bắt buộc)*, số lượng tồn kho.
  - Mô tả, tác giả (nhập tay, phân cách bằng dấu phẩy).
  - Ảnh bìa (upload file đơn).
  - Ảnh xem trước (upload nhiều file).
- **Xử lý**: Tự động tạo `slug` từ tên sách, liên kết tác giả qua bảng `book_authors`.

#### FR-ADMIN-BOOK-03: Sửa Sách
- **Mô tả**: Chỉnh sửa thông tin sách đã có. Thêm ảnh xem trước mới. Cập nhật tác giả.

#### FR-ADMIN-BOOK-04: Ẩn Sách
- **Mô tả**: Đánh dấu `is_active = 0` thay vì xóa hoàn toàn (soft delete).

---

### 3.8 Module Quản Trị — Danh Mục (Admin Categories)

#### FR-ADMIN-CAT-01: Quản Lý Danh Mục
- **Mô tả**: CRUD đầy đủ cho danh mục sách.
- **Đầu vào**: Tên danh mục, mô tả, hình ảnh minh họa (upload).
- **Hiển thị**: Danh sách danh mục kèm số lượng sách thuộc danh mục đó.

---

### 3.9 Module Quản Trị — Đơn Hàng (Admin Orders)

#### FR-ADMIN-ORDER-01: Danh Sách Đơn Hàng
- **Mô tả**: Hiển thị tất cả đơn hàng, hỗ trợ lọc theo trạng thái.
- **Thông tin**: Mã đơn, khách hàng, tổng tiền, phương thức thanh toán, trạng thái, ngày đặt.

#### FR-ADMIN-ORDER-02: Chi Tiết Đơn Hàng
- **Mô tả**: Xem đầy đủ thông tin đơn hàng bao gồm danh sách sản phẩm, thông tin giao hàng.

#### FR-ADMIN-ORDER-03: Cập Nhật Trạng Thái
- **Mô tả**: Admin thay đổi `order_status` của đơn hàng.
- **Xử lý khi giao thành công (`delivered`)**:
  - Kiểm tra tồn kho đủ không.
  - Trigger MySQL tự động trừ `stock_quantity` và cộng `total_sold`.
- **Điều kiện ngoại lệ**: Hiển thị lỗi nếu tồn kho không đủ.

---

### 3.10 Module Quản Trị — Người Dùng (Admin Users)

#### FR-ADMIN-USER-01: Danh Sách Người Dùng
- **Mô tả**: Hiển thị toàn bộ người dùng kèm email, vai trò, trạng thái tài khoản.

#### FR-ADMIN-USER-02: Khóa / Mở Khóa Tài Khoản
- **Mô tả**: Toggle `is_active` giữa 0 và 1 để vô hiệu hóa hoặc tái kích hoạt tài khoản.

#### FR-ADMIN-USER-03: Xóa Người Dùng
- **Mô tả**: Xóa người dùng khỏi CSDL.
- **Ràng buộc**: Không thể xóa nếu người dùng đã có đơn hàng (ràng buộc khóa ngoại `RESTRICT`).

---

### 3.11 Module Quản Trị — Dashboard & Thống Kê

#### FR-ADMIN-DASH-01: Dashboard Tổng Quan
- **Mô tả**: Hiển thị 4 chỉ số KPI chính:
  - Tổng số sách đang bán.
  - Tổng số người dùng.
  - Tổng số đơn hàng.
  - Tổng doanh thu (từ đơn `delivered`).
- **Nội dung bổ sung**: 5 đơn hàng gần nhất, top 5 sách bán chạy.

#### FR-ADMIN-DASH-02: Biểu Đồ Doanh Thu
- **Mô tả**: Biểu đồ đường (Chart.js) hiển thị doanh thu theo tháng trong 12 tháng gần nhất.
- **Nguồn dữ liệu**: API endpoint `/admin/revenue-data` trả về JSON.

---

## 4. Yêu Cầu Phi Chức Năng

### 4.1 Hiệu Năng (Performance)

| Tiêu chí                         | Mục tiêu                     |
|----------------------------------|------------------------------|
| Thời gian tải trang chủ          | < 3 giây (kết nối localhost) |
| Thời gian phản hồi AJAX tìm kiếm | < 500ms                      |
| Xử lý đồng thời                  | Hỗ trợ tối thiểu 50 user     |

### 4.2 Bảo Mật (Security)

- **Mật khẩu**: Hash bằng `password_hash()` thuật toán bcrypt.
- **SQL Injection**: Sử dụng PDO Prepared Statements cho mọi truy vấn.
- **XSS**: Sử dụng `htmlspecialchars()` khi hiển thị dữ liệu từ người dùng.
- **Phân quyền**: Kiểm tra `$_SESSION['user_role'] === 'admin'` trước mọi tác vụ admin.
- **Upload File**: Giới hạn định dạng (JPG, PNG, WebP, GIF) và kích thước (<= 5MB).

### 4.3 Khả Dụng (Usability)

- Giao diện responsive, hiển thị tốt trên desktop và tablet (>= 768px).
- Flash message thông báo cho các hành động thành công / thất bại.
- Breadcrumb navigation trên trang chi tiết sách.
- Trang 404 tùy chỉnh cho đường dẫn không tồn tại.

### 4.4 Khả Năng Bảo Trì (Maintainability)

- Kiến trúc MVC rõ ràng, tách biệt logic xử lý và giao diện.
- Mỗi module có Controller, Model và View riêng biệt.
- File JavaScript tách thành các module chuyên biệt (`cart.js`, `search.js`, `auth.js`, ...).
- CSS tổ chức thành file theo vai trò: `style.css`, `components.css`, `pages.css`, `admin.css`.

### 4.5 Tương Thích (Compatibility)

- Hỗ trợ trình duyệt: Chrome, Firefox, Edge (phiên bản mới nhất).
- Môi trường phát triển: Laragon với PHP 8.3, MySQL 8.4.

---

## 5. Thiết Kế Cơ Sở Dữ Liệu

### 5.1 Sơ Đồ Quan Hệ (ERD)

```
users ──< cart_items >── books
users ──< orders >──< order_items >── books
users ──< reviews >── books
books >── book_authors >── authors
books >── book_images
books ──> categories
books ──> publishers
orders ──< order_status_history
users ──< addresses
users ──< wishlists >── books
```

### 5.2 Mô Tả Các Bảng Chính

#### Bảng `users` — Người Dùng

| Cột             | Kiểu dữ liệu          | Mô tả                              |
|-----------------|-----------------------|------------------------------------|
| `user_id`       | INT UNSIGNED PK AI    | Khóa chính                         |
| `email`         | VARCHAR(255) UNIQUE   | Email đăng nhập                    |
| `password_hash` | VARCHAR(255)          | Mật khẩu đã hash (bcrypt)          |
| `full_name`     | VARCHAR(150)          | Họ và tên                          |
| `phone`         | VARCHAR(20)           | Số điện thoại                      |
| `avatar_url`    | VARCHAR(500)          | Đường dẫn ảnh đại diện             |
| `role`          | ENUM('customer','admin') | Vai trò người dùng             |
| `is_active`     | TINYINT(1)            | 1 = hoạt động, 0 = bị khóa        |
| `created_at`    | DATETIME              | Thời điểm tạo                      |

#### Bảng `books` — Sách

| Cột                 | Kiểu dữ liệu        | Mô tả                           |
|---------------------|---------------------|---------------------------------|
| `book_id`           | INT UNSIGNED PK AI  | Khóa chính                      |
| `title`             | VARCHAR(300)        | Tên sách                        |
| `slug`              | VARCHAR(350) UNIQUE | Đường dẫn thân thiện URL        |
| `isbn`              | VARCHAR(20) UNIQUE  | Mã ISBN                         |
| `publisher_id`      | INT UNSIGNED FK     | Nhà xuất bản                    |
| `category_id`       | INT UNSIGNED FK     | Danh mục                        |
| `publication_year`  | SMALLINT            | Năm xuất bản                    |
| `pages`             | SMALLINT            | Số trang                        |
| `language`          | VARCHAR(50)         | Ngôn ngữ (mặc định: Tiếng Việt) |
| `price`             | DECIMAL(12,2)       | Giá bán (VND)                   |
| `stock_quantity`    | INT UNSIGNED        | Số lượng tồn kho                |
| `description`       | TEXT                | Mô tả sách                      |
| `cover_image`       | VARCHAR(500)        | Đường dẫn ảnh bìa               |
| `is_active`         | TINYINT(1)          | Hiển thị / ẩn sách              |
| `avg_rating`        | DECIMAL(2,1)        | Điểm đánh giá trung bình (0–5) |
| `total_sold`        | INT UNSIGNED        | Tổng số bản đã bán              |

#### Bảng `orders` — Đơn Hàng

| Cột               | Kiểu dữ liệu | Mô tả                                                 |
|-------------------|--------------|-------------------------------------------------------|
| `order_id`        | INT PK AI    | Khóa chính                                            |
| `order_code`      | VARCHAR(30)  | Mã đơn hiển thị (VD: ORD-20260316-001)               |
| `user_id`         | INT FK       | Khách đặt hàng                                        |
| `receiver_name`   | VARCHAR(150) | Tên người nhận                                        |
| `receiver_phone`  | VARCHAR(20)  | Số điện thoại người nhận                              |
| `shipping_address`| VARCHAR(500) | Địa chỉ giao hàng                                     |
| `subtotal`        | DECIMAL      | Tổng tiền hàng                                        |
| `shipping_fee`    | DECIMAL      | Phí vận chuyển                                        |
| `total_amount`    | DECIMAL      | Tổng thanh toán                                       |
| `payment_method`  | ENUM         | cod / bank_transfer / e_wallet / credit_card          |
| `payment_status`  | ENUM         | unpaid / paid / refunded                              |
| `order_status`    | ENUM         | pending / confirmed / shipping / delivered / cancelled / returned |

#### Bảng `categories` — Danh Mục

| Cột              | Kiểu dữ liệu       | Mô tả                          |
|------------------|--------------------|--------------------------------|
| `category_id`    | INT PK AI          | Khóa chính                     |
| `category_name`  | VARCHAR(150)       | Tên danh mục                   |
| `slug`           | VARCHAR(200) UNIQUE| Đường dẫn thân thiện           |
| `parent_id`      | INT FK NULL        | Danh mục cha (hỗ trợ đa cấp)  |
| `description`    | VARCHAR(500)       | Mô tả danh mục                 |
| `image`          | VARCHAR(255)       | Ảnh minh họa danh mục          |

#### Bảng `reviews` — Đánh Giá

| Cột          | Kiểu dữ liệu | Mô tả                              |
|--------------|--------------|-------------------------------------|
| `review_id`  | INT PK AI    | Khóa chính                          |
| `book_id`    | INT FK       | Sách được đánh giá                  |
| `user_id`    | INT FK       | Người viết đánh giá                 |
| `rating`     | TINYINT      | Số sao (1–5)                        |
| `comment`    | TEXT         | Nội dung bình luận                  |

> **Unique constraint**: `(user_id, book_id)` — Mỗi người chỉ đánh giá mỗi sách 1 lần.

#### Bảng `authors` — Tác Giả

| Cột           | Kiểu dữ liệu | Mô tả               |
|---------------|--------------|---------------------|
| `author_id`   | INT PK AI    | Khóa chính          |
| `author_name` | VARCHAR(200) | Tên tác giả         |
| `bio`         | TEXT         | Tiểu sử             |
| `avatar_url`  | VARCHAR(500) | Ảnh tác giả         |

#### Bảng `book_authors` — Liên Kết Sách - Tác Giả (Many-to-Many)

| Cột         | Kiểu dữ liệu | Mô tả             |
|-------------|--------------|-------------------|
| `book_id`   | INT FK       | Khóa ngoại → books |
| `author_id` | INT FK       | Khóa ngoại → authors |

#### Bảng `book_images` — Ảnh Xem Trước

| Cột          | Kiểu dữ liệu | Mô tả                    |
|--------------|--------------|--------------------------|
| `image_id`   | INT PK AI    | Khóa chính               |
| `book_id`    | INT FK       | Sách liên quan           |
| `image_url`  | VARCHAR(500) | Đường dẫn ảnh            |
| `sort_order` | TINYINT      | Thứ tự hiển thị          |

#### Bảng `publishers` — Nhà Xuất Bản

| Cột              | Kiểu dữ liệu  | Mô tả              |
|------------------|---------------|--------------------|
| `publisher_id`   | INT PK AI     | Khóa chính         |
| `publisher_name` | VARCHAR(200)  | Tên NXB            |
| `address`        | VARCHAR(300)  | Địa chỉ            |
| `phone`          | VARCHAR(20)   | Điện thoại         |
| `email`          | VARCHAR(255)  | Email liên hệ      |

### 5.3 Database Views & Triggers

#### View `vw_book_details`
Tổng hợp thông tin sách kèm tên danh mục, nhà xuất bản, và danh sách tác giả (GROUP_CONCAT) để đơn giản hóa truy vấn hiển thị.

#### View `vw_monthly_revenue`
Tổng hợp doanh thu, số đơn giao thành công và số đơn hủy theo từng tháng/năm cho dashboard admin.

#### Trigger `trg_orders_after_update_status`
Khi đơn hàng chuyển sang `delivered`:
- Tự động **trừ** `stock_quantity` của từng sách trong đơn.
- Tự động **cộng** `total_sold` của từng sách.

#### Trigger `trg_reviews_after_insert/update/delete`
Tự động tính lại `avg_rating` của cuốn sách mỗi khi có thay đổi trong bảng `reviews`.

---

## 6. Kiến Trúc Hệ Thống

### 6.1 Mô Hình MVC

```
HTTP Request
    |
    v
public/index.php (Entry Point)
    |
    v
Router.php (Phân tích URL → xác định Controller::Action)
    |
    v
Controller (Xử lý business logic)
    |          |
    v          v
 Model        View
(PDO+MySQL) (HTML Output)
```

### 6.2 Cấu Trúc Thư Mục

```
tnmd_nhom_5/
├── public/                      # Document root (Apache)
│   ├── index.php                # Entry point duy nhất
│   ├── .htaccess                # URL rewrite rules
│   ├── css/
│   │   ├── style.css            # CSS chung & design tokens
│   │   ├── components.css       # Components tái sử dụng
│   │   ├── pages.css            # CSS riêng từng trang
│   │   └── admin.css            # CSS Admin Panel
│   ├── js/
│   │   ├── main.js              # Entry point JS
│   │   └── modules/             # Modules JS tách biệt
│   │       ├── cart.js
│   │       ├── search.js
│   │       ├── auth.js
│   │       ├── payment.js
│   │       ├── product.js
│   │       ├── alerts.js
│   │       ├── animations.js
│   │       └── ui.js
│   └── images/
│       ├── books/               # Ảnh bìa & ảnh xem trước
│       ├── categories/          # Ảnh minh họa danh mục
│       └── avatars/             # Ảnh đại diện người dùng
│
└── app/
    ├── config/
    │   └── database.php         # Cấu hình kết nối MySQL
    ├── core/
    │   ├── Router.php           # Định tuyến URL
    │   ├── Controller.php       # Base Controller
    │   ├── Model.php            # Base Model (PDO)
    │   └── Database.php         # Singleton kết nối DB
    ├── controllers/
    │   ├── HomeController.php
    │   ├── BookController.php
    │   ├── CartController.php
    │   ├── OrderController.php
    │   ├── AuthController.php
    │   ├── AccountController.php
    │   └── AdminController.php
    ├── models/
    │   ├── Book.php
    │   ├── Category.php
    │   ├── Cart.php
    │   ├── Order.php
    │   └── User.php
    └── views/
        ├── layouts/main.php          # Layout chính khách hàng
        ├── home/index.php
        ├── books/{index,detail,search}.php
        ├── cart/index.php
        ├── orders/{checkout,history}.php
        ├── auth/{login,register}.php
        ├── account/dashboard.php
        ├── partials/book_card.php
        ├── errors/404.php
        └── admin/
            ├── layouts/main.php      # Layout Admin Panel
            ├── dashboard.php
            ├── books/{index,form}.php
            ├── categories/{index,form}.php
            ├── orders/{index,detail}.php
            └── users/index.php
```

### 6.3 Luồng Xử Lý Request

```
1. Người dùng gửi HTTP request
2. Apache .htaccess rewrite toàn bộ về public/index.php
3. Router.php phân tích URL, xác định Controller và Action
4. Controller được khởi tạo, gọi Model(s) nếu cần
5. Model thực thi truy vấn PDO với CSDL MySQL
6. Controller truyền dữ liệu vào View tương ứng
7. Layout bao bọc View render HTML hoàn chỉnh
8. Response trả về trình duyệt
```

---

## 7. Giao Diện Người Dùng

### 7.1 Bản Đồ Trang (Sitemap)

#### Giao diện Khách Hàng

| Trang                   | URL                         | Mô tả                           |
|-------------------------|-----------------------------|----------------------------------|
| Trang chủ               | `/`                         | Hero, danh mục, sách nổi bật    |
| Danh sách sách          | `/books`                    | Grid sách với bộ lọc + sắp xếp  |
| Chi tiết sách           | `/book/{slug}`              | Thông tin đầy đủ + đánh giá     |
| Tìm kiếm                | `/books/search?q=...`       | Kết quả tìm kiếm                 |
| Danh mục                | `/category/{id}`            | Sách theo danh mục               |
| Giỏ hàng                | `/cart`                     | Xem & chỉnh sửa giỏ             |
| Thanh toán              | `/checkout`                 | Form đặt hàng                    |
| Lịch sử đơn hàng        | `/orders/history`           | Danh sách đơn hàng               |
| Đăng nhập               | `/login`                    | Form đăng nhập                   |
| Đăng ký                 | `/register`                 | Form đăng ký                     |
| Hồ sơ cá nhân           | `/account`                  | Dashboard người dùng             |

#### Giao diện Quản Trị (Admin)

| Trang                   | URL                           | Mô tả                            |
|-------------------------|-------------------------------|----------------------------------|
| Dashboard               | `/admin`                      | KPI, biểu đồ doanh thu           |
| Quản lý sách            | `/admin/books`                | Danh sách + phân trang           |
| Thêm sách               | `/admin/books/add`            | Form thêm sách mới               |
| Sửa sách                | `/admin/books/edit/{id}`      | Form chỉnh sửa sách              |
| Quản lý danh mục        | `/admin/categories`           | CRUD danh mục                    |
| Thêm danh mục           | `/admin/categories/add`       | Form thêm danh mục               |
| Quản lý đơn hàng        | `/admin/orders`               | Danh sách + lọc trạng thái       |
| Chi tiết đơn hàng       | `/admin/orders/detail/{id}`   | Xem đơn + cập nhật trạng thái   |
| Quản lý người dùng      | `/admin/users`                | Danh sách + khóa/mở tài khoản   |

### 7.2 Nguyên Tắc Thiết Kế UI

- **Color Scheme**: Tông màu chủ đạo xanh đậm (`--primary-color`), hỗ trợ nền sáng và tối.
- **Typography**: Inter/Outfit từ Google Fonts; phân cấp rõ ràng (h1–h4, body, caption).
- **Components**: Card sách hover effect nổi bật, badge HOT/NEW/SALE sinh động.
- **Animation**: Micro-animations mượt mà 0.2–0.3s ease-in-out trên hover và click.
- **Responsive**: Hỗ trợ màn hình từ 768px trở lên (desktop-first).
- **Icon**: Font Awesome 6 cho toàn bộ icon hệ thống.

---

## 8. Ràng Buộc & Giả Định

### 8.1 Ràng Buộc Kỹ Thuật

- Hệ thống chạy trên môi trường LAMP stack cục bộ (Laragon) — chưa deploy production.
- Không tích hợp cổng thanh toán thực tế — phương thức thanh toán chỉ mang tính chọn lựa UI.
- Không có tính năng gửi email xác nhận đơn hàng tự động.
- Bảng `wishlists` đã thiết kế CSDL nhưng chưa triển khai giao diện người dùng.
- Danh mục chỉ hỗ trợ 1 cấp hiển thị — `parent_id` tồn tại trong CSDL nhưng chưa xử lý cây đa cấp.

### 8.2 Giả Định

- Mỗi người dùng chỉ có một vai trò duy nhất: `customer` hoặc `admin`.
- Tài khoản Admin được cấp trực tiếp qua CSDL hoặc nâng cấp từ Admin Panel.
- Phí vận chuyển mặc định: **30.000₫**; miễn phí cho đơn hàng từ **300.000₫**.
- Hệ thống phục vụ thị trường Việt Nam (tiền tệ VND, địa chỉ định dạng Việt Nam).

### 8.3 Phụ Thuộc Hệ Thống

| Phụ thuộc      | Phiên bản  | Ghi chú                                |
|----------------|------------|----------------------------------------|
| PHP            | >= 8.3     | Bắt buộc                               |
| MySQL          | >= 8.0     | Bắt buộc                               |
| Apache         | Bất kỳ     | Cần hỗ trợ `mod_rewrite`              |
| Chart.js       | CDN        | Biểu đồ doanh thu admin                |
| Font Awesome 6 | CDN        | Icons toàn bộ hệ thống                 |
| Google Fonts   | CDN        | Typography (Inter, Outfit)             |

---

*Tài liệu SRS phiên bản 1.0 — Nhóm 5 — Ngày 01/04/2026*
