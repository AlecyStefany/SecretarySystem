<?php

namespace app\Repositories;

use PDO;

class StudentRepository
{
    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function insertStudent(array $request)
    {
        $sql = "INSERT INTO students (name, document, birthDate) 
        VALUES (:name, :document, :birthDate)";

        $db = $this->connection->prepare($sql);

        return $db->execute([
            ':name' => $request['name'],
            ':document' => $request['document'],
            ':birthDate' => $request['birthDate']
        ]);
    }

    public function getStudentById(int $id): array
    {
        $query = "SELECT * FROM students WHERE id= :id";
        $db = $this->connection->prepare($query);
        $db->bindValue(":id", $id);

        $db->execute();
        return $db->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStudentByDocument(string $document): array
    {
        $query = "SELECT * FROM students WHERE document= :document";
        $db = $this->connection->prepare($query);
        $db->bindValue(":document", $document);

        $db->execute();
        return $db->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStudents(array $whereKeys, array $whereValues): array
    {
        $query = "SELECT * FROM students";
        if (!empty($whereKeys)) {
            $query .= " WHERE " . implode(" AND ", $whereKeys);
        }
        $db = $this->connection->prepare($query);
        foreach ($whereValues as $params => $value) {
            $db->bindValue($params, $value['value'], $value['type']);
        }
        $db->execute();
        return $db->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStudent(int $id, array $updateValue, array $params): bool
    {

        $updateString = implode(', ', $updateValue);

        $query = "UPDATE  students SET $updateString WHERE id= :id";
        $db = $this->connection->prepare($query);

        foreach ($params as $params => $value) {
            $db->bindValue(":$params", $value);
        }
        $db->bindValue(":id", $id);

        return $db->execute();
    }

    public function deleteStudent(int $id): bool
    {
        $query = "DELETE FROM students WHERE id = :id";

        $db = $this->connection->prepare($query);
        $db->bindValue(':id', $id, PDO::PARAM_INT);

        return $db->execute();
    }

    public function getStudentsPaginated(int $offset, array $whereKeys = [], array $whereValues = []): array
    {
        $query = "SELECT * FROM students";

        if (!empty($whereKeys)) {
            $query .= " WHERE " . implode(" AND ", $whereKeys);
        }
        $query .= " LIMIT :limit OFFSET :offset";
        $db = $this->connection->prepare($query);

        foreach ($whereValues as $param => $value) {
            $db->bindValue($param, $value['value'], $value['type']);
        }

        $db->bindValue(':limit',10, PDO::PARAM_INT);
        $db->bindValue(':offset', $offset, PDO::PARAM_INT);
        $db->execute();
        return $db->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countStudents(array $whereKeys = [], array $whereValues = []): int
    {
        $query = "SELECT COUNT(*) FROM students";
        if (!empty($whereKeys)) {
            $query .= " WHERE " . implode(" AND ", $whereKeys);
        }
        $db = $this->connection->prepare($query);

        foreach ($whereValues as $param => $value) {
            $db->bindValue($param, $value['value'], $value['type']);
        }
        $db->execute();
        return (int) $db->fetchColumn();
    }
}
