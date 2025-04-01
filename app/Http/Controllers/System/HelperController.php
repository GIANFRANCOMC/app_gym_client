<?php

namespace App\Http\Controllers\System;

use App\Helpers\System\Utilities;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};
use stdClass;

class HelperController extends Controller {

    public function searchDocumentNumber(Request $request) {

        $bool = false;
        $msg  = "Datos incompletos.";
        $data = [];

        if(Utilities::isDefined($request->document_number) && preg_match('/^\d{8}$/', $request->document_number) && Utilities::isDefined($request->type)) {

            // email => 8874749d9e@emaily.pro
            // fc5516092fbe03b09638c1f569024a2e2c6e5aab928c4fd8e5f483186d1dcc00

            $params = json_encode(["dni" => $request->document_number]);

            $curlApi = curl_init();

            curl_setopt_array($curlApi, array(
                CURLOPT_URL => "https://apiperu.dev/api/dni",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_POSTFIELDS => $params,
                CURLOPT_HTTPHEADER => [
                    "Accept: application/json",
                    "Content-Type: application/json",
                    "Authorization: Bearer fc5516092fbe03b09638c1f569024a2e2c6e5aab928c4fd8e5f483186d1dcc00"
                ],
            ));

            $responseApi = curl_exec($curlApi);
            $errApi = curl_error($curlApi);
            curl_close($curlApi);

            $bool = false;
            $msg  = $errApi;
            $data = [];

            if(!Utilities::isDefined($errApi)) {

                $dataApi = json_decode($responseApi);

                $bool = $dataApi->success;
                $msg  = $bool ? "La búsqueda se ha efectuado correctamente." : $dataApi->message;
                $data = [];

                if($bool) {

                    $data = [
                        "document_number"   => $dataApi->data->numero,
                        "first_name"        => $dataApi->data->nombres,
                        "last_name"         => $dataApi->data->apellido_paterno,
                        "second_last_name"  => $dataApi->data->apellido_materno,
                        "verification_code" => $dataApi->data->codigo_verificacion
                    ];

                }

            }

        }

        return response()->json(["bool" => $bool, "msg" => $msg, "data" => $data], 200);

    }

}
