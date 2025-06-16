<?php

namespace app\Validations;

use app\Utils\FormartResponseHelper;
use Respect\Validation\Validator as validator;

class CourseValidation
{
    use FormartResponseHelper;

    public function validationParamsCourse(array $request)
    {
        if (!validator::optional(validator::intVal())->validate($request['id'] ?? null)) {
            $this->errorResponse(" O campo 'ID' deve ser um inteiro.");
        }

        if (!validator::optional(validator::stringType()->regex('/[a-zA-Z]/'))->validate($request['name'] ?? null)) {
            $this->errorResponse("O campo 'NOME' deve ser uma string");
        }

        if (!validator::optional(validator::stringType()->regex('/[a-zA-Z]/'))->validate($request['description'] ?? null)) {
            $this->errorResponse("O campo 'DESCRICAO' deve ser uma string.");
        }
    }

    public function validationRequiredParams(array $request): array
    {
        $requiredFields = ['name', 'description'];
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
        $validTypes = ['id', 'name','page'];
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
            $this->errorResponse("O campo 'NOME' deve contem no mínimo 3 caracteres.");
        }
    }
}
