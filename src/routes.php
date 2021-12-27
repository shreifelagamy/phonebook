<?php
require_once './controllers/PhoneBookController.php';
require_once './models/PhoneBook.php';

$phonebook_model = new PhoneBook();
$phonebook_controller = new PhoneBookController($phonebook_model);

if (isset($_POST)) {
    if (isset($_POST['action']) && $_POST['action'] === 'create') {
        $phonebook_controller->create($_POST['data']);
    } else if (isset($_POST['action']) && $_POST['action'] === 'DELETE') {

        if (!isset($_POST['data']['id'])) {
            header('HTTP/1.1 422 Unprocessable Entity');
            die(json_encode(['error' => 'ID must be set']));
        }

        $phonebook_controller->destory($_POST['data']['id']);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $phonebook_controller->index();
}