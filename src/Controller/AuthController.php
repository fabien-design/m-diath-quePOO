<?php 

namespace App\Controller;

use App\Database\Database;
use App\Model\User;
use App\Router\Router;

final readonly class AuthController
{

    public function login() : void
    {
        include "../src/view/login.php";
    }

    public function checkLogin() : void
    {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $connexion = Database::connect();
        $query = $connexion->prepare("SELECT * FROM user WHERE email = :email");
        $query->execute(['email' => $email]);
        $userDB = $query->fetch();

        if ($userDB && password_verify($password, $userDB['password'])) {
            $roles = ['user'];
            $roles[] = json_decode($userDB['roles']);
            $user = ['id' => $userDB['id'], 'username' => $userDB['username'], 'email' => $userDB['email'], 'roles' => $roles];
            $_SESSION['user'] = $user;
            Router::redirect("welcome");
        } else {
            Router::redirect("login");
        }
    }

    public function register() : void
    {
        include "../src/view/register.php";
    }

    public function create() : void
    {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];

        if ($password !== $confirmPassword) {
            $_GET['error'] = 'password_mismatch';
            Router::redirect("register");
            return;
        }

        // Password validation
        $passwordPattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
        if (!preg_match($passwordPattern, $password) || strpos($password, $username) !== false) {
            $_GET['error'] = 'invalid_password';
            Router::redirect("register");
            return;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $roles = ['user'];

        $connexion = Database::connect();
        $query = $connexion->prepare("SELECT * FROM user WHERE email = :email");
        $query->execute(['email' => $email]);
        $user = $query->fetch();

        if ($user) {
            $_GET['error'] = 'email_exists';
            Router::redirect("register");
            return;
        }

        $query = $connexion->prepare("SELECT * FROM user WHERE username = :username");
        $query->execute(['username' => $username]);
        $user = $query->fetch();
        if ($user) {
            $_GET['error'] = 'username_exists';
            Router::redirect("register");
            return;
        }

        $query = $connexion->prepare("INSERT INTO user (username, email, password, roles) VALUES (:username, :email, :password, :roles)");
        $query->execute(['username' => $username, 'email' => $email, 'password' => $hashedPassword, 'roles' => json_encode($roles)]);

        Router::redirect("login");
    }

    public function logout() : void
    {
        session_destroy();
        Router::redirect("welcome");
    }

}
