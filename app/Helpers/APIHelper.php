<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;



class APIHelper
{

    public static function makeAPIResponse($status = true, $message = "Done", $data = null, $status_code = 200)
    {
        $response = [
            "success" => $status,
            "message" => $message,
            "data" => $data,

        ];
        if ($data != null || is_array($data)) {
            $response["data"] = $data;
        }
        return response()->json($response, $status_code);
    }






}
