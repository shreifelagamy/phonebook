<?php

class PhoneBook
{
    protected $servername = "mysql";
    protected $username = "root";
    protected $password = "root";
    protected $database = "phonebook";
    protected $port = 3306;

    protected $conn;

    public function __construct()
    {
        // Create connection
        $this->conn = mysqli_connect(
            $this->servername,
            $this->username,
            $this->password,
            $this->database,
            $this->port
        );

        // Check connection
        if (!$this->conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
    }

    public function getAll()
    {
        $query = "SELECT * FROM users ORDER BY id DESC";
        $result = $this->conn->query($query);

        if ($result->num_rows === 0) {
            return [];
        }

        $data = $result->fetch_all(MYSQLI_ASSOC);

        $result->free();

        return $data;
    }

    public function create(string $name, array $phone)
    {
        $query = "INSERT INTO users (name, phone) VALUES (?, ?)";

        $statment = $this->conn->prepare($query);
        $phone = implode(',', $phone);
        $statment->bind_param('ss', $name, $phone);
        $statment->execute();

        $statment->close();
    }

    public function delete($id)
    {
        $query = "DELETE FROM users WHERE id = ?";
        $statment = $this->conn->prepare($query);
        $statment->bind_param('i', $id);
        $statment->execute();

        $statment->close();
    }

    public function __destruct()
    {
        mysqli_close($this->conn);
    }
}
