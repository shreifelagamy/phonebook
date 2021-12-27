<?php
header('Content-Type: application/json; charset=UTF-8');

class PhoneBookController
{
    protected $phonebook_model;

    public function __construct($phonebook_model)
    {
        $this->phonebook_model = $phonebook_model;
    }

    public function index()
    {
        echo json_encode(['data' => $this->phonebook_model->getAll()]);
    }

    public function destory($id)
    {
        $this->phonebook_model->delete($id);

        echo json_encode((['message' => 'phonebook deleted successfully']));
    }

    public function create(array $data)
    {
        $this->validateData($data);

        $name = $this->prepareName($data['name']);
        $phone = $data['phone'];

        $this->phonebook_model->create($name, $phone);

        echo json_encode(['message' => 'phonebook added successfully']);
    }

    private function prepareName(string $name)
    {
        $data = trim($name);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
    }

    private function validateData(array $data)
    {
        extract($data);
        $error = [];


        // check if data are empty
        if (!isset($name) || !isset($phone) || empty($name) || count($phone) === 0) {
            $error[] = "All fields are required";
        } else if (!is_array($phone)) {
            $error[] = "phone numbers should be array";
        } else {
            // check each phone number
            foreach ($phone as $key => $ph) {
                if (!is_numeric($ph)) {
                    $index = $key + 1;
                    $error[] = "Phone number ({$index}) should be numbers only";
                }
            }
        }

        if (count($error)) {
            header('HTTP/1.1 422 Unprocessable Entity');
            die(json_encode(['error' => $error]));
        }
    }
}
