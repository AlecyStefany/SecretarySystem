<?php

namespace app\Validations;

use app\Utils\FormartResponseHelper;
use Respect\Validation\Validator as validator;

class StudentValidation
{
    use FormartResponseHelper;

    public function validationParamsStudent(array $request)
    {
        if (!validator::optional(validator::intVal())->validate($request['id'] ?? null)) {
            $this->errorResponse("O campo 'ID' deve ser um inteiro.");
        }

        if (!validator::optional(validator::stringType()->regex('/[a-zA-Z]/'))->validate($request['name'] ?? null)) {
            $this->errorResponse("O campo 'NOME' deve ser uma string");
        }

        if (!validator::optional(validator::cpf())->validate($request['document'] ?? null)) {
            $this->errorResponse("O campo 'DOCUMENTO' não é um CPF válido");
        }

        if (!validator::optional(validator::date('Y-m-d'))->validate($request['birthDate'] ?? null)) {
            $this->errorResponse("O campo 'DATA DE NASCIMENTO' não é uma data válida.");
        }

        if (!validator::optional(validator::intVal())->validate($request['page'] ?? null)) {
            $this->errorResponse("O campo 'PAGE' deve ser um inteiro.");
        }

        $today = date('Y-m-d');
        if ($request['birthDate'] > $today) {
            $this->errorResponse("O campo 'DATA DE NASCIMENTO' não é uma data válida.");
        }
    }

    public function validationRequiredParams(array $request): array
    {
        $requiredFields = ['name', 'document', 'birthDate'];
        $emptyFields = [];

        foreach ($requiredFields as $field) {
            if (empty($request[$field]) && $request[$field] !== '0') {
                $emptyFields[] = $field;
            }
        }
        return $emptyFields ?? [];
    }

    public function validationTypeGet(array $request): void
    {
        $validTypes = ['id', 'name', 'document', 'birthDate', 'page'];
        $requestTypes = array_keys($request);

        foreach ($requestTypes as $type) {
            if (in_array($type, $validTypes)) {
                continue;
            }
            $this->errorResponse("O parâmetro '" . strtoupper($type) . "' é invalido.");
        }
    }

    public function validationMinimumCharacters(string $param): void
    {
        if (!validator::length(3)->validate($param)) {
            $this->errorResponse(" O campo NOME deve contem no mínimo 3 caracteres.");
        }
    }
}
