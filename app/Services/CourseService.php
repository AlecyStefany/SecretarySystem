<?php

namespace app\Services;

use app\Repositories\CourseRepository;
use app\Utils\FormartResponseHelper;
use app\Utils\FormatTextHelper;
use PDO;

class CourseService
{
    use FormatTextHelper, FormartResponseHelper;
    private $repository;

    public function __construct(CourseRepository $repository)
    {
        $this->repository = $repository;
    }

    public function insertCourse(array $request): array
    {
        $request['name'] = $this->normalizeName($request['name']);

        $repository = $this->repository;
        $hasDocument = $repository->getCourseByName($request['name']);
        if ($hasDocument) {
            $this->errorResponse("Curso já cadastrado no sistema");
        }

        $repository->insertCourse($request);
        return $repository->getCourseByName($request['name']);
    }

public function getCourse(array $request): array
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

    $courses = $repository->getCoursesPaginated(
        $pagination['offset'],
        $whereKeys,
        $whereValues
    );

    if (empty($courses)) {
        $this->errorResponse("Nenhum curso encontrado com os parâmetros informados.");
    }

    return [
        'CURSOS' => $courses,
        'total' => $pagination['total'],
        'page' => $pagination['page'],
        'totalPages' => $pagination['totalPages'],
    ];
}

    public function editCourse(int $id, array $request): array
    {
        $repository = $this->repository;
        $student = $repository->getCourseById($id);
        if (empty($student)) {
            $this->errorResponse("Curso não encontrado com o id informado.");
        }

        $updateFields = [];
        foreach ($request as $key => $value) {
            $updateFields[] = "$key = :$key";
        }
        $update = $repository->updateCourse($id, $updateFields, $request);
        if ($update == false) {
            $this->errorResponse("Erro ao atualizar, por favor tente mais tarde.");
        }
        return $repository->getCourseById($id);
    }

    public function removeCourse(int $id)
    {
        $repository = $this->repository;
        $student = $repository->getCourseById($id);
        if (empty($student)) {
            $this->errorResponse("Aluno não encontrado com o id informado.");
        }

        $delete = $repository->deleteCourse($id);
        if ($delete == false) {
            $this->errorResponse("Erro ao excluir registro, por favor tente mais tarde.");
        }
    }

    private function calculatePagination(int $page, array $whereKeys = [], array $whereValues = []): array
    {
        $repository = $this->repository;

        $total = $repository->countCourses($whereKeys, $whereValues);
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
