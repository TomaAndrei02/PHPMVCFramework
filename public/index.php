<?php

use app\core\Application;
use app\controllers\SiteController;
use app\controllers\AuthController;
use app\models\User;

require_once __DIR__.'/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$config = [
    'userClass' => User::class,
    'db' => [
        'dsn' => $_ENV['DB_DSN'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD'],
    ]
];

$app = new Application(dirname(__DIR__), $config);


$app->router->get('/', [SiteController::class, 'home']);
$app->router->get('/contact', [SiteController::class, 'contact']);
$app->router->post('/contact', [SiteController::class, 'contact']);

$app->router->get('/login', [AuthController::class, 'login']);
$app->router->post('/login', [AuthController::class, 'login']);
$app->router->get('/register', [AuthController::class, 'register']);
$app->router->post('/register', [AuthController::class, 'register']);
$app->router->get('/logout', [AuthController::class, 'logout']);
$app->router->get('/profile', [AuthController::class, 'profile']);

$app->router->get('/api/users', function () {
    $dsn = $_ENV['DB_DSN'];
    $dbUser = $_ENV['DB_USER'];
    $dbPassword = $_ENV['DB_PASSWORD'];

    try {
        // Connect to the database
        $pdo = new PDO($dsn, $dbUser, $dbPassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Query the database
        $stmt = $pdo->query("SELECT id, firstname, lastname, email FROM users");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Return data as JSON
        header("Content-Type: application/json");
        echo json_encode($users);
    } catch (PDOException $e) {
        // Handle errors
        http_response_code(500);
        echo json_encode(["error" => "Database error: " . $e->getMessage()]);
    }
});

$app->router->get('/api/posts', function() {
    return json_encode([
        ['id' => 1, 'title' => 'First Post', 'content' => 'This is the first post.'],
        ['id' => 2, 'title' => 'Second Post', 'content' => 'This is the second post.'],
    ]);
});

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}




$app->run();