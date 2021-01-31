<?php


namespace controller;

use core\AppController;
use core\Repository;
use model\User;


class Auth extends AppController
{
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            return $this->renderView('login');   
        }
        $name = trim(htmlspecialchars($_POST['name']));
        $pass = trim(htmlspecialchars($_POST['pass']));
        $errors = [];
        if (!$name && !$pass) {
            $errors[] = 'Пустые поля';
        }
        $user = null;
        if (empty($errors)) {
            $pass = md5(md5($pass));
            $userRepository = new Repository(User::class);
            $user = $userRepository->getByFields(["name" => $name, "pass" => $pass]);
            if (!$user) {
                $errors[] = 'Неправильное имя пользователя или пароль';
            } else {
                $_SESSION['auth'] = true;
                $_SESSION['user'] = $user->name;
                $_SESSION['role'] = $user->role;
            }
        }
        return $this->renderJson([
            'success' => !empty($errors) ? false : true,
            'errors' => $errors,
        ]);
    }

    public function logout()
    {
        session_destroy();
        header("Location: /");
    }
}