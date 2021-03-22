<?php

namespace Ca\Framework\Helper;

class Validation
{
    public static function validString($string)
    {
        $string = trim($string);
        $string = strip_tags($string);
        $string = htmlspecialchars($string);
        return $string;
    }

    public static function validInteger($integer)
    {
        return (int) $integer;
    }

    public static function validEmail($email)
    {
        return self::validString(strtolower($email));
    }


}
