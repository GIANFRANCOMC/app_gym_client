<?php

namespace App\Helpers;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use stdClass;

class Utilities {

    public static function getDefaultViewData() {

        $result = new stdClass();

        $tenancy = tenancy();
        $tenant = $tenancy->tenant;
        $result->tenant = $tenant;

        return $result;

    }

    public static function validateVariable($valor) {

        return isset($valor) && !empty($valor);

    }

    public static function parsePadStart($value, $length = 2, $valuePad = "0") {

        $respuesta = "";

        try {

            $respuesta  = str_pad($value, $length, $valuePad, STR_PAD_LEFT);

        }catch(Exception $e) {

        }

        return $respuesta;

    }

    public static function limpiarArray($array) {

        foreach($array as $key => $value) {

            $array[$key] = "";

        }

        return $array;

    }

    // Fechas, Date, Hour
    public static function convertirDateBackToFront($value, $separador = "-", $separadorRespuesta = "-") {

        $respuesta = "";

        try {

            $valueArray = explode($separador, $value);
            $respuesta  = $valueArray[2].$separadorRespuesta.$valueArray[1].$separadorRespuesta.$valueArray[0];

        }catch(Exception $e) {

        }

        return $respuesta;

    }

    public static function parseHora($value) {

        $respuesta = "";

        try {

            $valueArray = explode(":", $value);
            $respuesta  = $valueArray[0].":".$valueArray[1];

        }catch(Exception $e) {

        }

        return $respuesta;

    }

    public static function sumarDiasAFecha($fecha, $dias, ) {

        $respuesta = null;
        $respuestaString = "";
        $respuestaParseString = "";

        try {

            $dateFecha = Carbon::parse($fecha);
            $respuesta = $dateFecha->addDay($dias);
            $respuestaString = Carbon::createFromFormat('Y-m-d H:i:s', $respuesta)->format('Y-m-d');
            $respuestaParseString = self::convertirDateBackToFront($respuestaString, "-", "/");

        }catch(Exception $e) {


        }

        return ["carbon" => $respuesta, "string" => $respuestaString, "parseString" => $respuestaParseString];

    }

    public static function restarFechas($fechaMayor, $fechaMenor) {

        $respuesta = null;

        try {

            $dateFechaMayor = Carbon::parse($fechaMayor);
            $dateFechaMenor = Carbon::parse($fechaMenor);
            $respuesta = $dateFechaMayor->diffInDays($dateFechaMenor);

        }catch(Exception $e) {


        }

        return $respuesta;

    }

}
