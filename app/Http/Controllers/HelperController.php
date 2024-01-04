<?php

namespace App\Http\Controllers;

use App\Helpers\Utilities;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HelperController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function consultNumberDocument(Request $request)
    {

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
                'Authorization: Bearer e3f658506679cc15e793f898cc3c1bb4a48a636306fc99c84a2630c830c7cdf9'
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
