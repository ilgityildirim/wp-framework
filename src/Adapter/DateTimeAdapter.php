<?php

declare(strict_types=1);

namespace TripleBits\WpFramework\Adapter;

use DateTime;

class DateTimeAdapter
{
    public function getDateTimeFormat(): string
    {
        $dateFormat = get_option('date_format');
        $timeFormat = get_option('time_format');

        if (!$dateFormat) {
            $dateFormat = 'Y/m/d';
        }

        if (!$timeFormat) {
            $timeFormat = 'H:i:s';
        }

        return $dateFormat . ' ' . $timeFormat;
    }

    public function transformToWpFormat(DateTime $dateTime): string
    {
        return $dateTime->format($this->getDateTimeFormat());
    }
}
