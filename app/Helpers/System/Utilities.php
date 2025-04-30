<?php

namespace App\Helpers\System;

use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Support\Str;
use stdClass;

class Utilities {

    public static $per_page_default = 10;
    public static $per_page_max = 1000;

    public static $messages = [
        "422" => "Error al validar."
    ];

    public static $inputs = [
        "maxlength" => 999,
        "round" => 2,
        "minValue" => 0,
        "maxValue" => 99999999
    ];

    public static function getOwnerApp() {

        $data = [
            "commercial_name" => "BLAPOS",
            "web" => "https://blapos.com",
            "color_palette" => [
                "primary" => "#2899E5",
                "secondary" => "#1A1A35",
                "text_by_primary" => "#FFFFFF"
            ],
            "assets" => [
                "img" => [
                    "combination_mark" => "System/assets/img/utils/owner_app/combination_mark.png",
                    "logotype" => "System/assets/img/utils/owner_app/logotype.png"
                ]
            ]
        ];

        return json_decode(json_encode($data));

    }

    public static function getDefaultData() {

        $result = new stdClass();

        $result->env_company_id = env("COMPANY_ID") ?? null;

        return $result;

    }

    public static function isDefined($valor) {

        return isset($valor) && !empty($valor);

    }

    public static function getValues($array, $type, $code) {

        $result = null;

        if(in_array($type, ["all"])) {

            $result = $array;

        }else if(in_array($type, ["first"])) {

            $filter = array_filter($array, function($e) use($code) { return $e["code"] === $code; });
            $result = count($filter) > 0 ? reset($filter) : null;

        }

        return $result;

    }

    public static function round($value, $decimals = 2) {

        return round($value, $decimals);

    }

    public static function convertNumberToWords($number) {

        $phrase = "";

        try {

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

            $phrase = trim($result) . ($cents > 0 ? " CON " . str_pad($cents, 2, '0', STR_PAD_LEFT) . "/100 SOLES" : " SOLES");

        }catch(Exception $e) {

            $phrase = "";

        }

        return $phrase;

    }

    public static function getWordSearch($word, $type = "like") {

        return "%".trim($word ?? "")."%";

    }

    public static function generateCode($length = 12) {

        $characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $randomString = "";

        for($i = 0; $i < $length; $i++) {

            $randomIndex = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$randomIndex];

        }

        return $randomString;

    }

    public static function isValidDateFormat($date, $format = "Y-m-d") {

        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;

    }

}
