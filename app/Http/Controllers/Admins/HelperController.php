<?php

namespace App\Http\Controllers\Admins;

use App\Helpers\Utilities;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HelperController extends Controller {

    /**
     * Consult.
     */
    public function consultNumberDocument(Request $request) {

        $params = json_encode([$request->type => $request->number_document]);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://apiperu.dev/api/$request->type",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Content-Type: application/json',
                'Authorization: Bearer cf12112a823387c0e11e207ab5614d801ae40407c795e4aa6d6e1969e7c81a96'
            ],
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if($err) {

            echo "cURL Error #:" . $err;

        }else {

            echo $response;

        }

    }

}
