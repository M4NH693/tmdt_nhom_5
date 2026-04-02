<?php
/**
 * Entry Point - Front Controller
 * Tất cả request đều đi qua file này
 * Tương thích với Laragon (Apache Virtual Host)
 */

session_start();

// Define base path
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

// Tính BASE_URL tự động cho Laragon
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
define('BASE_URL', rtrim($scriptDir, '/'));

// Autoload core classes
require_once APP_PATH . '/core/Database.php';
require_once APP_PATH . '/core/Model.php';
require_once APP_PATH . '/core/Controller.php';
require_once APP_PATH . '/core/Router.php';
require_once APP_PATH . '/config/database.php';

// Initialize Router
$router = new Router();

// ========== PUBLIC ROUTES ==========
$router->add('', 'HomeController', 'index');
$router->add('home', 'HomeController', 'index');
$router->add('terms', 'HomeController', 'terms');
$router->add('books', 'BookController', 'index');
$router->add('book/{id}', 'BookController', 'detail');
$router->add('book/review/{id}', 'BookController', 'submitReview');
$router->add('category/{id}', 'BookController', 'category');
$router->add('login', 'AuthController', 'login');
$router->add('register', 'AuthController', 'register');
$router->add('logout', 'AuthController', 'logout');
$router->add('cart', 'CartController', 'index');
$router->add('cart/add', 'CartController', 'add');
$router->add('cart/update', 'CartController', 'update');
$router->add('cart/remove', 'CartController', 'remove');
$router->add('checkout', 'OrderController', 'checkout');
$router->add('orders', 'OrderController', 'history');
$router->add('search/ajax', 'BookController', 'ajaxSearch');
$router->add('search', 'BookController', 'search');
$router->add('account', 'AccountController', 'dashboard');
$router->add('account/avatar', 'AccountController', 'updateAvatar');
$router->add('account/password', 'AccountController', 'changePassword');

// ========== ADMIN ROUTES ==========
$router->add('admin', 'AdminController', 'dashboard');
$router->add('admin/dashboard', 'AdminController', 'dashboard');
$router->add('admin/books', 'AdminController', 'books');
$router->add('admin/books/add', 'AdminController', 'addBook');
$router->add('admin/books/edit/{id}', 'AdminController', 'editBook');
$router->add('admin/books/delete/{id}', 'AdminController', 'deleteBook');
$router->add('admin/categories', 'AdminController', 'categories');
$router->add('admin/categories/add', 'AdminController', 'addCategory');
$router->add('admin/categories/edit/{id}', 'AdminController', 'editCategory');
$router->add('admin/categories/delete/{id}', 'AdminController', 'deleteCategory');
$router->add('admin/orders', 'AdminController', 'orders');
$router->add('admin/orders/detail/{id}', 'AdminController', 'orderDetail');
$router->add('admin/orders/update-status/{id}', 'AdminController', 'updateOrderStatus');
$router->add('admin/users', 'AdminController', 'users');
$router->add('admin/users/toggle/{id}', 'AdminController', 'toggleUser');
$router->add('admin/users/delete/{id}', 'AdminController', 'deleteUser');
$router->add('admin/revenue-data', 'AdminController', 'revenueData');

// Dispatch request
$url = isset($_GET['url']) ? $_GET['url'] : '';
$router->dispatch($url);
