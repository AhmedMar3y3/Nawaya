<?php

namespace App\Traits;

trait HttpResponses
{

    public function response($key,$message,$data = [],$statusCode){

        return response()->json([
            'key' => $key,
            'msg' => $message,
            'data' => $data
        ], $statusCode);
    }


    public function successResponse($message = 'تم بنجاح')
    {
        return $this->response('success', $message, [], 200);
    }

    public function successWithDataResponse($data){
        return $this->response('success', 'تم بنجاح', $data, 200);
    }
    public function inactiveUserResponse($data)
    {
        return $this->response('ActivationNeeded', 'يرجي تأكيد الحساب', $data, 200);
    }
    public function incompletedUserResponse($data)
    {
        return $this->response('CompletetionNeeded', 'يرجي إكمال البيانات', $data, 200);
    }

    public function blockedUserResponse()
    {
        return $this->response('blocked', 'المستخدم محظور', [], 403);
    }

    public function unauthenticatedResponse(){
        return $this->response('unauthenticated', 'غير مصرح', [], 401);
    }

    public function failureResponse($message)
    {
        return $this->response('failure', $message, [], 400);
    }
}