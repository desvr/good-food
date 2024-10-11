<?php

namespace App\Helpers\Formatter;

class PhoneFormatter
{
    /**
     * Formatting a phone number into delimiters
     *
     * @param string $phone Unformatted phone number
     *
     * @return string
     */
    public static function phoneFormatterDelimiters(string $phone): string
    {
        return preg_replace(
            '/^(\d)(\d{3})(\d{3})(\d{4})$/',
            '+\1 (\2) \3-\4',
            $phone
        );
    }
}
