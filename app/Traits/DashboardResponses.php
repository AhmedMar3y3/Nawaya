<?php
namespace App\Traits;

trait DashboardResponses
{

    public function response($key, $message, $data = [], $statusCode)
    {

        return response()->json([
            'success' => $key,
            'msg'     => $message,
            'data'    => $data,
        ], $statusCode);
    }

    public function successResponse($message = 'تم بنجاح')
    {
        return $this->response(true, $message, [], 200);
    }

    public function successWithDataResponse($data)
    {
        return $this->response(true, 'تم بنجاح', $data, 200);
    }

    public function successWithMessageAndDataResponse($data, $message)
    {
        return $this->response(true, $message, $data, 200);
    }

    public function failureResponse($message)
    {
        return $this->response(false, $message, [], 500);
    }
}
