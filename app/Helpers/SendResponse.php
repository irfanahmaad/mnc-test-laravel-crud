<?php

namespace App\Helpers;

class SendResponse
{
    public static function default($data, $message, $code, $status){
        $res = $status == 1? 'success' : 'error';
        $response['status']['code'] = $code;
        $response['status']['response'] = $res;
        $response['status']['message'] = $message;
        $response['result'] = $data;
        
        return response()->json($response, 200);
    }
}