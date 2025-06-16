<?php

namespace app\Controllers;

use app\Services\StudentService;
use app\Utils\FormartResponseHelper;
use app\Validations\StudentValidation;

class StudentController
{
    use FormartResponseHelper;
    private $service;

    public function __construct(StudentService $service)
    {
        $this->service = $service;
    }

    public function insertStudent(): void
    {
        $body = file_get_contents('php://input');
        $data = json_decode($body, true);

        $validation = new StudentValidation;
        $error = $validation->validationRequiredParams($data);
        if ($error) {
            $this->errorResponse("Campos obrigatórios não preenchidos", $error);
        }

        $validation->validationParamsStudent($data);
        $validation->validationMinimumCharacters($data['name']);
        $response = $this->service->insertStudent($data);

        $this->sucessResponse("Aluno inserido com sucesso", $response);
        exit;
    }

    public function getStudents(): void
    {
        $request = $_GET;
        $validation = new StudentValidation;
        $validation->validationTypeGet($request);
        $validation->validationParamsStudent($request);

        $response = $this->service->getStudent($request);
        $this->sucessResponse("", $response);
    }

    public function editStudent(int $id): void
    {
        $body = file_get_contents('php://input');
        $data = json_decode($body, true);

        $validation = new StudentValidation;
        $validation->validationParamsStudent($data);

        $response = $this->service->editStudent($id, $data);
        $this->sucessResponse("Aluno atualizado com sucesso", $response);
    }

    public function removeStudent(int $id): void
    {
        $validation = new StudentValidation;
        $validation->validationParamsStudent(["id" => $id]);

        $this->service->removeStudent($id);
        $this->sucessResponse("Aluno atualizado com sucesso", []);
    }
}
