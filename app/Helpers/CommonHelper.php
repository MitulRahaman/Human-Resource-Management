<?php
namespace App\Helpers;

use DateTime;

class CommonHelper{
    public static function format_date($date, $input_format = ‘d/m/Y’, $output_format = ‘Y-m-d’)
    {
        $formatted_date = \DateTime::createFromFormat($input_format, $date);
        return $formatted_date->format($output_format);
    }

}
