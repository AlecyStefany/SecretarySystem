<?php

namespace app\Repositories;

use PDO;

class RegistrationRepository
{
    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function insertRegistration(array $request)
    {
        $sql = "INSERT INTO registration (student_id, course_id) 
        VALUES (:student_id, :course_id)";

        $db = $this->connection->prepare($sql);

        return $db->execute([
            ':student_id' => $request['studentId'],
            ':course_id' => $request['courseId'],
        ]);
    }

    public function getRegistrations(?int $id): array
    {
        $query = "SELECT * FROM registration";
        $params = [];
        if ($id !== null) {
            $query    .= " WHERE id = :id";
            $params['id'] = $id;
        }

        $db = $this->connection->prepare($query);
        $db->execute($params);

        return $db->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRegistrationInfo(array $request): array
    {
        $query = "SELECT * FROM registration WHERE student_id= :studentId AND course_id =:courseId";
        $db = $this->connection->prepare($query);
        $db->bindValue(":studentId", $request['studentId'], PDO::PARAM_INT);
        $db->bindValue(":courseId", $request['courseId'], PDO::PARAM_INT);
        $db->execute();
        return $db->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRegistrationStudent(int $studentId): array
    {
        $query = "SELECT * FROM registration WHERE student_id= :studentId";
        $db = $this->connection->prepare($query);
        $db->bindValue(":studentId", $studentId, PDO::PARAM_INT);
        $db->execute();
        return $db->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRegistrationsPaginated(int $offset, array $filters): array
    {
        $whereKeys = [];
        $whereValues = [];

        foreach ($filters as $key => $value) {
            if ($key === 'studentId') {
                $whereKeys[] = "r.student_id = :studentId";
                $whereValues[':studentId'] = ['value' => (int)$value, 'type' => PDO::PARAM_INT];
            } elseif ($key === 'courseId') {
                $whereKeys[] = "r.course_id = :courseId";
                $whereValues[':courseId'] = ['value' => (int)$value, 'type' => PDO::PARAM_INT];
            }
        }

        $query = "
        SELECT 
            r.id AS registration_id,
            s.id AS student_id,
            s.name AS student_name,
            c.id AS course_id,
            c.name AS course_name
        FROM registration r
        INNER JOIN students s ON r.student_id = s.id
        INNER JOIN courses c ON r.course_id = c.id
    ";

        if (!empty($whereKeys)) {
            $query .= " WHERE " . implode(" AND ", $whereKeys);
        }

        $query .= " ORDER BY r.id ASC LIMIT 10 OFFSET :offset";

        $stmt = $this->connection->prepare($query);

        foreach ($whereValues as $param => $val) {
            $stmt->bindValue($param, $val['value'], $val['type']);
        }

        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countRegistrations(array $whereKeys = [], array $whereValues = []): int
    {
        $query = "SELECT COUNT(*) FROM registration";
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
