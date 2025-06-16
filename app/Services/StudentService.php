<?php

namespace app\Services;

use app\Repositories\StudentRepository;
use app\Utils\FormartResponseHelper;
use app\Utils\FormatTextHelper;
use PDO;

class StudentService
{
    use FormatTextHelper, FormartResponseHelper;
    private $repository;

    public function __construct(StudentRepository $repository)
    {
        $this->repository = $repository;
    }

    public function insertStudent(array $request): array
    {
        $request['name'] = $this->normalizeName($request['name']);
        $request['document'] = $this->documentToString($request['document']);

        $repository = $this->repository;
        $hasDocument = $repository->getStudentByDocument($request['document']);
        if ($hasDocument) {
            $this->errorResponse("Aluno já cadastrado no sistema.");
        }

        $repository->insertStudent($request);
        return $repository->getStudentByDocument($request['document']);
    }

    public function getStudent(array $request): array
    {
        $repository = $this->repository;

        $whereKeys = [];
        $whereValues = [];

        foreach ($request as $key => $value) {
            if ($key === 'page' || $key === 'perPage') {
                continue; 
            }
            $whereKeys[] = "$key = :$key";
            if ($key === 'id') {
                $whereValues[":$key"] = ['value' => (int)$value, 'type' => PDO::PARAM_INT];
            } else {
                $whereValues[":$key"] = ['value' => $value, 'type' => PDO::PARAM_STR];
            }
        }

        $page = isset($request['page']) ? (int)$request['page'] : 1;
        $pagination = $this->calculatePagination($page, $whereKeys, $whereValues);

        $students = $repository->getStudentsPaginated(
            $pagination['offset'],
            $whereKeys,
            $whereValues
        );

        if (empty($students)) {
            $this->errorResponse("Nenhum aluno encontrado com os parâmetros informados.");
        }

        return [
            'ALUNOS' => $students,
            'total' => $pagination['total'],
            'page' => $pagination['page'],
            'totalPages' => $pagination['totalPages'],
        ];
    }

    public function editStudent(int $id, array $request): array
    {
        $repository = $this->repository;
        $student = $repository->getStudentById($id);
        if (empty($student)) {
            $this->errorResponse("Aluno não encontrado com o id informado.");
        }

        $updateFields = [];
        foreach ($request as $key => $value) {
            $updateFields[] = "$key = :$key";
        }
        $update = $repository->updateStudent($id, $updateFields, $request);
        if ($update == false) {
            $this->errorResponse("Erro ao atualizar, por favor tente mais tarde.");
        }
        return $repository->getStudentById($id);
    }

    public function removeStudent(int $id)
    {
        $repository = $this->repository;
        $student = $repository->getStudentById($id);
        if (empty($student)) {
            $this->errorResponse("Aluno não encontrado com o id informado.");
        }


        try {
            $repository->deleteStudent($id);
        } catch (\PDOException $e) {
            if ($e->getCode() === "23000") {
                $this->errorResponse("Aluno cadastrado em um curso, não é possivel excluir.");
            }
        }
    }

    private function calculatePagination(int $page, array $whereKeys = [], array $whereValues = []): array
    {
        $repository = $this->repository;

        $total = $repository->countStudents($whereKeys, $whereValues);
        $totalPages = (int) ceil($total / 10);
        $totalPages = $totalPages > 0 ? $totalPages : 1;

        if ($page < 1) {
            $page = 1;
        } elseif ($page > $totalPages) {
               $this->errorResponse("Não há registros na pagina informada.");
        }

        $offset = ($page - 1) * 10;

        return [
            'page' => $page,
            'offset' => $offset,
            'totalPages' => $totalPages,
            'total' => $total,
        ];
    }
}
