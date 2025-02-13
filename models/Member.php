<?php

class Member
{
    private $conn;
    private $table_name = "members";

    public $id;
    public $first_name;
    public $last_name;
    public $email;
    public $phone;
    public $address;
    public $join_date;
    public $status;
    public $church_id;
    public $cgroup_id;
    public $next_of_kin;
    public $member_number;
    public $number_of_kids;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create()
    {
        $query = "INSERT INTO " . $this->table_name . "
                    SET
                        first_name = :first_name,
                        last_name = :last_name,
                        email = :email,
                        phone = :phone,
                        address = :address,
                        join_date = :join_date,
                        status = :status,
                        church_id=:church_id,
                        cgroup_id=:cgroup_id, 
                        next_of_kin=:next_of_kin, 
                        member_number=:member_number, 
                        number_of_kids=:number_of_kids";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":first_name", $this->first_name);
        $stmt->bindParam(":last_name", $this->last_name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":phone", $this->phone);
        $stmt->bindParam(":address", $this->address);
        $stmt->bindParam(":join_date", $this->join_date);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":church_id", $this->church_id);
        $stmt->bindParam(":cgroup_id", $this->cgroup_id);
        $stmt->bindParam(":next_of_kin", $this->next_of_kin);
        $stmt->bindParam(":member_number", $this->member_number);
        $stmt->bindParam(":number_of_kids", $this->number_of_kids);

        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            $this->generateMemberNumber();

            $updateQuery = "UPDATE " . $this->table_name . "
                        SET member_number = :member_number
                        WHERE id = :id";
            $updateStmt = $this->conn->prepare($updateQuery);
            $updateStmt->bindParam(":member_number", $this->member_number);
            $updateStmt->bindParam(":id", $this->id);
            return $updateStmt->execute();
        }

        return false;
    }


    private function generateMemberNumber()
    {
        // Fetch Diocese code
        $query = "SELECT d.code AS diocese_code, g.code AS group_code, YEAR(NOW()) AS year_registered
                  FROM churches c
                  JOIN parishes p ON c.parish_id = p.id
                  JOIN archdeaconries a ON p.archdeaconry_id = a.id
                  JOIN dioceses d ON a.diocese_id = d.id
                  JOIN cgroups g ON g.id = :cgroup_id
                  WHERE c.id = :church_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":church_id", $this->church_id);
        $stmt->bindParam(":cgroup_id", $this->cgroup_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $diocese_code = $row['diocese_code'];
        $group_code = $row['group_code'];
        $year_registered = $row['year_registered'];

        // Generate member number
        $this->member_number = sprintf("%s-%s-%d-%d", $diocese_code, $group_code, $year_registered, $this->id);
    }

    public function read()
    {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readOne()
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->first_name = $row['first_name'];
        $this->last_name = $row['last_name'];
        $this->email = $row['email'];
        $this->phone = $row['phone'];
        $this->address = $row['address'];
        $this->join_date = $row['join_date'];
        $this->status = $row['status'];
        $this->church_id = $row['church_id'];
        $this->cgroup_id = $row['cgroup_id'];
        $this->next_of_kin = $row['next_of_kin'];
        $this->member_number = $row['member_number'];
        $this->number_of_kids = $row['number_of_kids'];

    }

    public function update()
    {
        $query = "UPDATE " . $this->table_name . "
                    SET
                        first_name = :first_name,
                        last_name = :last_name,
                        email = :email,
                        phone = :phone,
                        address = :address,
                        status = :status
                        church_id=:church_id,
                        cgroup_id=:cgroup_id,
                        next_of_kin=:next_of_kin,
                        member_number=:member_number,
                        number_of_kids=:number_of_kids
                        
                    WHERE
                        id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":first_name", $this->first_name);
        $stmt->bindParam(":last_name", $this->last_name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":phone", $this->phone);
        $stmt->bindParam(":address", $this->address);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":church_id", $this->church_id);
        $stmt->bindParam(":cgroup_id", $this->cgroup_id);
        $stmt->bindParam(":next_of_kin", $this->next_of_kin);
        $stmt->bindParam(":member_number", $this->member_number);
        $stmt->bindParam(":number_of_kids", $this->number_of_kids);

        return $stmt->execute();
    }

    public function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        return $stmt->execute();
    }

    public function readPaginated($limit, $offset)
    {
        $query = "SELECT m.*, c.name as church_name, g.name as group_name
                      FROM " . $this->table_name . " m
                      LEFT JOIN churches c ON m.church_id = c.id
                      LEFT JOIN cgroups g ON m.cgroup_id = g.id
                      ORDER BY m.id DESC
                      LIMIT ?, ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $offset, PDO::PARAM_INT);
        $stmt->bindParam(2, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    public function count()
    {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }
}