<?php

namespace app\Controllers;

use app\Services\RegistrationService;
use app\Utils\FormartResponseHelper;
use app\Validations\RegistrationValidation;

class RegistrationController
{
    use FormartResponseHelper;
    private $service;

    public function __construct(RegistrationService $service)
    {
        $this->service = $service;
    }

    public function insertRegistration(): void
    {
        $body = file_get_contents('php://input');
        $data = json_decode($body, true);

        $validation = new RegistrationValidation;
        $error = $validation->validationRequiredParams($data);
        if ($error) {
            $this->errorResponse("Campos obrigatórios não preenchidos", $error);
        }

        $validation->validationParamsRegistration($data);
        $response = $this->service->insertRegistration($data);

        $this->sucessResponse("Aluno cadastrado no curso com sucesso", $response);
        exit;
    }

    public function getRegistration(): void
    {
        $request = $_GET;
        $validation = new RegistrationValidation;
        $validation->validationTypeGet($request);
        $validation->validationParamsRegistration($request);

        $response = $this->service->getRegistration($request);
        $this->sucessResponse("", $response, "MATRICULAS");
    }

    public function getRegistrationByCourse(): void
    {
        $request = $_GET;
        $validation = new RegistrationValidation;
        $validation->validationTypeGet($request);
        $validation->validationParamsRegistration($request);

        $response = $this->service->getRegistrationByCourse($request);
        $this->sucessResponse("", $response);
    }

}
