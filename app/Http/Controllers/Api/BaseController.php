<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    public function sendResponse($result, $message,$code)
    {
        $response = [
            'status' => true,
            'data' => $result,
            'message'    => $message,
        ];
       
        return response()->json($response, $code);
    }
  
    
    public function sendError($errorMessages, $error = [] , $code)
    {

        $response = [
            'status' => false,
            'message' => $errorMessages,
            
        ];
        if(!empty($error)){
            $response['errors'] = $error;
        }else{
            $response['errors'] = "";
        }
  
        return response()->json($response, $code);
    }

    public function sendCommonResponse($error, $type,$data,$message,$token,$code)
    {
        $response = [
            'error' => $error,
            'message'    => $message,
        ];
        if(!empty($type)){
            $response['type'] = $type;
        }
        if(!empty($token)){
            $response['token'] = $token;
        }

        if(!empty($data)){
            $response['data'] = $data;
        }
       
        return response()->json($response, $code);
    }
}