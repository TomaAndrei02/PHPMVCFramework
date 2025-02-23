<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Request;
use PDO;
use PDOException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if ($request->isPost()) {
            // Get database connection
            $dsn = $_ENV['DB_DSN'];
            $dbUser = $_ENV['DB_USER'];
            $dbPassword = $_ENV['DB_PASSWORD'];

            try {
                $pdo = new PDO($dsn, $dbUser, $dbPassword);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Get JSON input from React
                $data = json_decode(file_get_contents("php://input"), true);
                $email = $data['email'] ?? '';
                $password = $data['password'] ?? '';

                // Query database for user
                $stmt = $pdo->prepare("SELECT id, firstname, lastname, email, password FROM users WHERE email = :email");
                $stmt->bindParam(":email", $email);
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user && password_verify($password, $user['password'])) {
                    // Generate a simple token (You can use JWT for better security)
                    $token = bin2hex(random_bytes(16));

                    // Send response
                    echo json_encode([
                        "message" => "Login successful",
                        "user" => [
                            "id" => $user["id"],
                            "firstname" => $user["firstname"],
                            "lastname" => $user["lastname"],
                            "email" => $user["email"]
                        ],
                        "token" => $token
                    ]);
                } else {
                    http_response_code(401);
                    echo json_encode(["error" => "Invalid email or password"]);
                }
            } catch (PDOException $e) {
                http_response_code(500);
                echo json_encode(["error" => "Database error: " . $e->getMessage()]);
            }
        }
    }
}
