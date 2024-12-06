<?php
    namespace App\Helpers;

    use Illuminate\Support\Str;

    class Helper
    {
        public static function dayName($value)
        {
            switch($value) {
                case "Sunday" :
                    $string = 'Sunday';
                break;
                case "Monday" :
                    $string = 'Monday';
                break;
                case "Tuesday" :
                    $string = 'Tuesday';
                break;
                case "Wednesday" :
                    $string = 'Wednesday';
                break;
                case "Thursday" :
                    $string = 'Thursday';
                break;
                case "Friday" :
                    $string = 'Friday';
                break;
                case "Saturday" :
                    $string = 'Saturday';
                break;

                default :
                    $string = '-';
                break;
            }

            return $string;
        }

        public static function monthName($value) {
            switch($value) {
                case "01" :
                    $string = 'January';
                break;
                case "02" :
                    $string = 'February';
                break;
                case "03" :
                    $string = 'March';
                break;
                case "04" :
                    $string = 'April';
                break;
                case "05" :
                    $string = 'May';
                break;
                case "06" :
                    $string = 'June';
                break;
                case "07" :
                    $string = 'July';
                break;
                case "08" :
                    $string = 'August';
                break;
                case "09" :
                    $string = 'September';
                break;
                case "10" :
                    $string = 'October';
                break;
                case "11" :
                    $string = 'November';
                break;
                case "12" :
                    $string = 'December';
                break;

                default :
                    $string = '-';
                break;
            }

            return $string;
        }

        public static function genSSH512($value)
        {
            $salt = Str::random(8);

            return base64_encode(hash('sha512', $value.$salt, true).$salt);
        }

        public static function url()
        {
            $url = "http://localhost:8010/";

            return $url;
        }

        public static function bytesToCheck($bytes)
        {
            for ($i = 0; $bytes > 1024; $i++) {
                $bytes /= 1024;
            }

            return round($bytes, 2);
        }

        public static function bytesToHuman($bytes)
        {
            $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];

            for ($i = 0; $bytes > 1024; $i++) {
                $bytes /= 1024;
            }

            return round($bytes, 2) . ' ' . $units[$i];
        }
    }
?>
