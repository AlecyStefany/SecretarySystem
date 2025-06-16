<?php

namespace app\Controllers;

use app\Services\CourseService;
use app\Utils\FormartResponseHelper;
use app\Validations\CourseValidation;

class CourseController
{

    use FormartResponseHelper;

    private $service;

    public function __construct(CourseService $service)
    {
        $this->service = $service;
    }

    public function insertCourse(): void
    {
        $body = file_get_contents('php://input');
        $data = json_decode($body, true);

        $validation = new CourseValidation;
        $error = $validation->validationRequiredParams($data);
        if ($error) {
            $this->errorResponse("Campos obrigatórios não preenchidos", $error);
        }
        $validation->validationParamsCourse($data);
        $validation->validationMinimumCharacters($data['name']);
        $response = $this->service->insertCourse($data);

        $this->sucessResponse("Curso inserido com sucesso", $response);
        exit;
    }

    public function getCourses(): void
    {
        $request = $_GET;
        $validation = new CourseValidation;
        $validation->validationTypeGet($request);
        $validation->validationParamsCourse($request);

        $response = $this->service->getCourse($request);
        $this->sucessResponse("", $response);
    }

    public function editCourse(int $id): void
    {
        $body = file_get_contents('php://input');
        $data = json_decode($body, true);

        $validation = new CourseValidation;
        $validation->validationParamsCourse($data);

        $response = $this->service->editCourse($id, $data);
        $this->sucessResponse("Curso atualizado com sucesso", $response);
    }

    public function removeCourse(int $id): void
    {
        $validation = new CourseValidation;
        $validation->validationParamsCourse(["id" => $id]);

        $this->service->removeCourse($id);
        $this->sucessResponse("Curso excluído com sucesso", []);
    }

}
