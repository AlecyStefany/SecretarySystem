<?php

namespace app\Validations;

use app\Utils\FormartResponseHelper;
use Respect\Validation\Validator as validator;

class RegistrationValidation
{

    use FormartResponseHelper;

    public function validationParamsRegistration(array $request)
    {
        if (!validator::optional(validator::intVal())->validate($request['id'] ?? null)) {
            $this->errorResponse("O campo 'ID' deve ser um inteiro.");
        }

        if (!validator::optional(validator::intVal())->validate($request['studentId'] ?? null)) {
            $this->errorResponse("O campo 'ID DO ALUNO' deve ser um inteiro.");
        }

        if (!validator::optional(validator::intVal())->validate($request['courseId'] ?? null)) {
            $this->errorResponse("O campo 'ID DO CURSO' deve ser um inteiro.");
        }
    }

    public function validationRequiredParams(array $request): array
    {
        $requiredFields = ['studentId', 'courseId'];
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
        $validTypes = ['id', 'courseId','page'];
        $requestTypes = array_keys($request);

        foreach ($requestTypes as $type) {
            if (in_array($type, $validTypes)) {
                continue;
            }
            $this->errorResponse("O parâmetro '" . strtoupper($type) . "' é invalido.");
        }
    }
}
