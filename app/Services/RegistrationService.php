<?php

namespace app\Services;

use app\Repositories\CourseRepository;
use app\Repositories\RegistrationRepository;
use app\Utils\FormartResponseHelper;
use app\Utils\FormatTextHelper;
use PDO;

class RegistrationService
{
    use FormatTextHelper, FormartResponseHelper;
    private $repository;

    public function __construct(RegistrationRepository $repository)
    {
        $this->repository = $repository;
    }

    public function insertRegistration(array $request): array
    {
        $repository = $this->repository;
        $hasRegistration = $repository->getRegistrationInfo($request);
        if ($hasRegistration) {
            $this->errorResponse("Aluno já cadastrado no curso informado.");
        }

        $hasStudentRegistration = $repository->getRegistrationStudent($request['studentId']);
        if ($hasStudentRegistration) {
            $this->errorResponse("Aluno já cadastrado em um curso.");
        }

        try {
            $repository->insertRegistration($request);
        } catch (\PDOException $e) {
            if ($e->getCode() === "23000") {
                $this->errorResponse("Aluno ou curso não encontrado.Verifique os parâmetros informados.");
            }
        }
        return $repository->getRegistrationInfo($request);
    }

    public function getRegistration(array $request): array
    {
        $repository = $this->repository;
        $response = $repository->getRegistrations($request['id']);

        if (empty($response)) {
            $this->errorResponse("Nenhuma matrícula encontrada com os parâmetros informados.");
        }

        return $response;
    }

    public function getRegistrationByCourse(array $request): array
    {
        $repository = $this->repository;

        $filters = [];

        if (!empty($request['courseId'])) {
            $filters['courseId'] = (int)$request['courseId'];
        }

        if (!empty($request['studentId'])) {
            $filters['studentId'] = (int)$request['studentId'];
        }

        $page = isset($request['page']) ? (int)$request['page'] : 1;
        $offset = ($page - 1) * 10;

        $registrations = $repository->getRegistrationsPaginated($offset, $filters);

        if (empty($registrations)) {
            $this->errorResponse("Nenhuma matrícula encontrada com os parâmetros informados.");
        }

        $formattedData = $this->formatedResponseRegistration($registrations);

        return [
            'MATRICULAS' => $formattedData,
            'page' => $page,
        ];
    }

    private function formatedResponseRegistration(array $response): array
    {

        foreach ($response as $registration) {
            $formated[$registration['course_name']][] = [
                "Id do Resgistro" => $registration['registration_id'],
                "Nome do Aluno" => $registration['student_name'],
            ];
        }
        $formated['Quantidade'] = count($formated[$registration['course_name']]);
        return $formated;
    }

    private function calculatePagination(int $page, array $whereKeys = [], array $whereValues = []): array
    {
        $repository = $this->repository;

        $total = $repository->countRegistrations($whereKeys, $whereValues);
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
