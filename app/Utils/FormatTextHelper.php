<?Php

namespace app\Utils;

trait FormatTextHelper
{

    public function normalizeName(string $name): string
    {
        $name = iconv('UTF-8', 'ASCII//TRANSLIT', $name);
        $name=preg_replace('/[^a-zA-Z0-9\s]/u', '', $name);
        $name = ucwords(strtolower($name));
        return $name;
    }

    function documentToString(string $document): string
    {
        return str_replace(['.', '-'], '', $document);
    }

    function formatDocument(string $cpfLimpo): string {
    $cpf = preg_replace('/\D/', '', $cpfLimpo);

    return substr($cpf, 0, 3) . '.' .
           substr($cpf, 3, 3) . '.' .
           substr($cpf, 6, 3) . '-' .
           substr($cpf, 9, 2);
}
}

