<?php
// Composer Autoload
require_once __DIR__ . '/../vendor/autoload.php';

// Simple Autoloader (Legacy/Internal)
spl_autoload_register(function ($class) {
    // Prefix for our project classes
    $prefix = 'App\\';
    
    // Base directory for the namespace prefix
    $base_dir = __DIR__ . '/../src/';
    
    // Does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // no, move to the next registered autoloader
        return;
    }
    
    // Get the relative class name
    $relative_class = substr($class, $len);
    
    // Replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    // If the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});

// Parsing the URL
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$scriptName = dirname($_SERVER['SCRIPT_NAME']);

// Remove the script's directory (base path) from the request URI to get the route
// Ensure we handle both / and /index.php endings in script directory
$basePath = str_replace('\\', '/', $scriptName);
if (substr($requestUri, 0, strlen($basePath)) === $basePath) {
    $path = substr($requestUri, strlen($basePath));
} else {
    $path = $requestUri;
}

// Ensure path starts with /
if (empty($path)) {
    $path = '/';
}
$path = '/' . ltrim($path, '/');

// Helper to render views
function view($name, $data = []) {
    extract($data);
    require __DIR__ . "/../src/Views/{$name}.php";
}

// Simple Routing Logic
switch ($path) {
    case '/':
    case '/index.php':
    case '':
        $controller = new \App\Controllers\HomeController();
        $controller->index();
        break;

    case '/feedback/new':
        $controller = new \App\Controllers\FeedbackController();
        $controller->create();
        break;

    case '/feedback/store':
        $controller = new \App\Controllers\FeedbackController();
        $controller->store();
        break;

    case '/feedback/vote':
        $controller = new \App\Controllers\FeedbackController();
        $controller->vote();
        break;

    case '/feedback/view':
        $controller = new \App\Controllers\FeedbackController();
        $controller->show();
        break;

    case '/comments/add':
        $controller = new \App\Controllers\CommentController();
        $controller->store();
        break;

    // Auth Routes
    case '/login':
        $controller = new \App\Controllers\AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->login();
        } else {
            $controller->showLogin();
        }
        break;

    case '/register':
        $controller = new \App\Controllers\AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->register();
        } else {
            $controller->showRegister();
        }
        break;

    case '/logout':
        $controller = new \App\Controllers\AuthController();
        $controller->logout();
        break;

    // Admin Routes
    case '/admin':
        $controller = new \App\Controllers\AdminController();
        $controller->index();
        break;

    case '/admin/update_status':
        $controller = new \App\Controllers\AdminController();
        $controller->updateStatus();
        break;

    case '/admin/delete':
        $controller = new \App\Controllers\AdminController();
        $controller->delete();
        break;

    // Profile Routes
    case '/profile':
        $controller = new \App\Controllers\ProfileController();
        $controller->edit();
        break;

    case '/profile/update':
        $controller = new \App\Controllers\ProfileController();
        $controller->update();
        break;

    // Password Reset Routes
    case '/password/reset':
        $controller = new \App\Controllers\ForgotPasswordController();
        $controller->showLinkRequestForm();
        break;

    case '/password/email':
        $controller = new \App\Controllers\ForgotPasswordController();
        $controller->sendResetLinkEmail();
        break;

    case '/password/reset/form':
        $controller = new \App\Controllers\ForgotPasswordController();
        $controller->showResetForm();
        break;

    case '/password/update':
        $controller = new \App\Controllers\ForgotPasswordController();
        $controller->reset();
        break;

    // Image Route
    case '/image':
        $controller = new \App\Controllers\ImageController();
        $controller->show();
        break;

    // API Routes
    case '/api/feedback':
        $controller = new \App\Controllers\ApiController();
        $controller->storeFeedback();
        break;
        
    default:
        http_response_code(404);
        view('404');
        break;
}
