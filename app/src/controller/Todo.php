<?php


namespace controller;

use core\Repository;
use model\Task;
use core\AppController;


class Todo extends AppController
{
    public function index() 
    { 
        $this->renderView('index');
    }

    public function list()
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $sorting = [];
        if (isset($_GET['sortDir']) && isset($_GET['sortField'])) {
            $sorting = [
                'sortDir' => trim(htmlspecialchars($_GET['sortDir'])),
                'sortField' => trim(htmlspecialchars($_GET['sortField'])),
            ];
        }

        $taskRepository = new Repository(Task::class);
        $tasks = $taskRepository->getAll(3, $page, $sorting);
        $this->renderJson($tasks);
    }

    public function create()
    {
        $errors = [];
        $email = trim(htmlspecialchars($_POST['email']));
        $name = trim(htmlspecialchars($_POST['name']));
        $text = trim(htmlspecialchars($_POST['text']));
        $isDone = $_POST['isDone'] == "true" ? 1 : 0;
        $errors = $this->getValidateErrors($email, $name, $text);
        if ($isDone && $_SESSION['role'] != 'admin') {
            $errors[] = 'Только администраторы могут проставлять статус';
        }
        $result = [
            'success' => false,
            'errors' => $errors,
        ];
        if (empty($errors)) {
            $task = new Task;
            $task->email = $email;
            $task->name = $name;
            $task->isDone = $isDone;
            $task->text = $text;
            $task->updatedByAdmin = 0;
            $task->save();
            $result['success'] = true;
        }
        $this->renderJson($result);
    }

    public function update()
    {
        $id = (int)$_POST['id'];
        $email = trim(htmlspecialchars($_POST['email']));
        $name = trim(htmlspecialchars($_POST['name']));
        $text = trim(htmlspecialchars($_POST['text']));
        $isDone = $_POST['isDone'] == "true" ? 1 : 0;
        $errors = $this->getValidateErrors($email, $name, $text);
        if (!$id) {
            $errors[] = 'Ошибка, перезагрузите страницу';
        }
        if (empty($errors)) {
            $taskRepository = new Repository(Task::class);
            $task = $taskRepository->getByFields(["id" => $id]);
            if ($task === null) {
                $errors[] = 'Данная задача не найдена';
            }
        }
        if ($_SESSION['role'] != 'admin') {
            $errors[] = 'Только администраторы могут изменять задачи';
        }
        $result = [];
        if (!empty($errors)) {
            $result['success'] = false;  
        } else {
            $task->email = $email;
            $task->name = $name;
            $task->isDone = $isDone;
            $task->text = $text;
            $task->updatedByAdmin = 1;
            $task->save();
            $result['success'] = true;            
        }
        $result['errors'] = $errors;
        return $this->renderJson($result);
    }

    private function getValidateErrors($email, $name, $text): array
    {
        $errors = [];
        if (!$email || !$name || !$text) {
            $errors[] = 'Не все поля заполнены';
        }
        if ($email && !preg_match("/^[a-zA-Z0-9-_]+\@[a-zA-Z0-9-_]+\.[a-zA-Z0-9-_]{2,3}/", $email)) {
            $errors[] = 'email не валиден';
        }
        return $errors;
    }
}

