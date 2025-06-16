<?Php

namespace app\Utils;

trait FormartResponseHelper
{

    public function sucessResponse(string $message, array $response, string $field = 'INFO')
    {
        header('Content-Type: application/json');
        http_response_code(200);
        $data = [
            'SUCCESS' => $message,
            $field => $response

        ];
        echo json_encode($data);
        exit;
    }

    public function errorResponse(string $message, array $info=[])
    {
        header('Content-Type: application/json');
        http_response_code(400);
        $data = [
            'ERROR' => $message,
            "INFO" => $info
        ];

        echo json_encode($data);
        exit;
    }
}
