<?php

class User
{
    public $lastname;
    public $firstname;
    private $conn;
    private $table_name = "users";

    public $id;
    public $email;
    public $password;
    public $role;
    public $created_at;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create()
    {
        $query = "INSERT INTO " . $this->table_name . "
                    SET
                        email = :email,
                        firstname = :firstname,
                        lastname = :lastname,
                        password = :password,
                        role = :role,
                        created_at = :created_at";

        $stmt = $this->conn->prepare($query);

        $this->firstname = htmlspecialchars(strip_tags($this->firstname));
        $this->lastname = htmlspecialchars(strip_tags($this->lastname));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
        $this->role = htmlspecialchars(strip_tags($this->role));
        $this->created_at = date('Y-m-d H:i:s');

        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":firstname", $this->firstname);
        $stmt->bindParam(":lastname", $this->lastname);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":role", $this->role);
        $stmt->bindParam(":created_at", $this->created_at);

        return $stmt->execute();
    }

    public function emailExists()
    {
        $query = "SELECT id, firstname, lastname, password, role
                    FROM " . $this->table_name . "
                    WHERE email = ?
                    LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->password = $row['password'];
            $this->firstname = $row['firstname'];
            $this->lastname = $row['lastname'];
            $this->role = $row['role'];
            return true;
        }
        return false;
    }

    public function read()
    {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function updateRole() {
        $query = "UPDATE " . $this->table_name . " SET role = :role WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $this->role = htmlspecialchars(strip_tags($this->role));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':role', $this->role);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }

}