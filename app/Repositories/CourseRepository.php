<?php

namespace app\Repositories;

use PDO;

class CourseRepository
{
    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function insertCourse(array $request)
    {
        $sql = "INSERT INTO courses (name, description) 
        VALUES (:name, :description)";

        $db = $this->connection->prepare($sql);

        return $db->execute([
            ':name' => $request['name'],
            ':description' => $request['description']
        ]);
    }

    public function getCourseById(int $id): array
    {
        $query = "SELECT * FROM courses WHERE id= :id";
        $db = $this->connection->prepare($query);
        $db->bindValue(":id", $id);

        $db->execute();
        return $db->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCourseByName(string $name): array
    {
        $query = "SELECT * FROM courses WHERE name= :name";
        $db = $this->connection->prepare($query);
        $db->bindValue(":name", $name);

        $db->execute();
        return $db->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCourses(array $whereKeys , array $whereValues): array
    {
        $query = "SELECT * FROM courses";
        $db = $this->connection->prepare($query);

        if (!empty($whereKeys)) {
            $query .= " WHERE " . implode(" AND ", $whereKeys);
            foreach ($whereValues as $params => $value) {
                $db->bindValue($params, $value['value'], $value['type']);
            }
        }

        $db->execute();
        return $db->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCoursesPaginated(int $offset, array $whereKeys, array $whereValues): array
{
    $query = "SELECT * FROM courses";

    if (!empty($whereKeys)) {
        $query .= " WHERE " . implode(" AND ", $whereKeys);
    }

    $query .= " ORDER BY name ASC LIMIT 10 OFFSET :offset";

    $db = $this->connection->prepare($query);

    foreach ($whereValues as $param => $value) {
        $db->bindValue($param, $value['value'], $value['type']);
    }
    $db->bindValue(':offset', $offset, PDO::PARAM_INT);

    $db->execute();
    return $db->fetchAll(PDO::FETCH_ASSOC);
}

    public function updateCourse(int $id, array $updateValue, array $params): bool
    {
        $updateString = implode(', ', $updateValue);

        $query = "UPDATE  courses SET $updateString WHERE id= :id";
        $db = $this->connection->prepare($query);

        foreach ($params as $params => $value) {
            $db->bindValue(":$params", $value);
        }
        $db->bindValue(":id", $id);

        return $db->execute();
    }

    public function deleteCourse(int $id): bool
    {
        $query = "DELETE FROM courses WHERE id = :id";

        $db = $this->connection->prepare($query);
        $db->bindValue(':id', $id, PDO::PARAM_INT);

        return $db->execute();
    }

    
    public function countCourses(array $whereKeys = [], array $whereValues = []): int
    {
        $query = "SELECT COUNT(*) FROM courses";
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
