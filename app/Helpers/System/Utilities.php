<?php

namespace App\Helpers\System;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use stdClass;

class Utilities {

    public static function getDefaultViewData() {

        $result = new stdClass();

        // $result->x = $x;

        return $result;

    }

    public static function isDefined($valor) {

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

    public static function convertNumberToWords($number) {

        $units = [
            0 => 'CERO', 1 => 'UNO', 2 => 'DOS', 3 => 'TRES', 4 => 'CUATRO', 5 => 'CINCO',
            6 => 'SEIS', 7 => 'SIETE', 8 => 'OCHO', 9 => 'NUEVE', 10 => 'DIEZ', 11 => 'ONCE',
            12 => 'DOCE', 13 => 'TRECE', 14 => 'CATORCE', 15 => 'QUINCE', 16 => 'DIECISEIS',
            17 => 'DIECISIETE', 18 => 'DIECIOCHO', 19 => 'DIECINUEVE', 20 => 'VEINTE',
            30 => 'TREINTA', 40 => 'CUARENTA', 50 => 'CINCUENTA', 60 => 'SESENTA',
            70 => 'SETENTA', 80 => 'OCHENTA', 90 => 'NOVENTA', 100 => 'CIENTO',
            200 => 'DOSCIENTOS', 300 => 'TRESCIENTOS', 400 => 'CUATROCIENTOS',
            500 => 'QUINIENTOS', 600 => 'SEISCIENTOS', 700 => 'SETECIENTOS',
            800 => 'OCHOCIENTOS', 900 => 'NOVECIENTOS',
        ];

        if ($number < 0) return 'MENOS ' . self::convertNumberToWords(-$number);
        if ($number == 0) return $units[0];

        $whole = floor($number);
        $cents = round(($number - $whole) * 100);
        $result = '';

        if ($whole >= 100) {
            $result .= $units[100 * floor($whole / 100)] . ' ';
            $whole %= 100;
        }
        if ($whole >= 20) {
            $result .= $units[10 * floor($whole / 10)] . ' ';
            $whole %= 10;
        }
        if ($whole > 0) {
            $result .= $units[$whole] . ' ';
        }

        return trim($result) . ($cents > 0 ? " CON " . str_pad($cents, 2, '0', STR_PAD_LEFT) . "/100 SOLES" : " SOLES");
    }

}
