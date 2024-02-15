<?php

/**
 * Class OutputFormats
 *
 * Вывод данных в каком-либо формате (в виде телефона, в виде даты)
 */
class OutputFormats
{
    public static $months = [
        1  => 'Января',
        2  => 'Февраля',
        3  => 'Марта',
        4  => 'Апреля',
        5  => 'Мая',
        6  => 'Июня',
        7  => 'Июля',
        8  => 'Августа',
        9  => 'Сентября',
        10 => 'Октября',
        11 => 'Ноября',
        12 => 'Декабря',
    ];

    /**
     * Для вывода числа чего-либо с падежом
     *
     * $count = 7;
     * echo $count . ' ' . OutputFormats::byCount($count, 'день', 'дня', 'дней');
     */
    public static function byCount(int $count, string $form1, string $form2, string $form3): string
    {
        $count  = abs($count) % 100;
        $lcount = $count % 10;
        if ($count >= 11 && $count <= 19) {
            return ($form3);
        }
        if ($lcount >= 2 && $lcount <= 4) {
            return ($form2);
        }
        if ($lcount == 1) {
            return ($form1);
        }

        return $form3;
    }

    public static function date(int $timestamp): string
    {
        return $timestamp ? ("<nobr>" . date('d-m-Y', $timestamp) . "</nobr>") : '';
    }

    public static function dateTime(int $timestamp, $withSeconds = true): string
    {
        return $timestamp ? ("<nobr>" . date('d-m-Y H:i' . ($withSeconds ? ':s' : ''), $timestamp) . "</nobr>") : '';
    }

    public static function dateTimeLang(int $timestamp, $withSeconds = true, $langData = NULL): string
    {
        $langData  = array_merge(
            [
                'months'      => self::$months,
                'year_ending' => 'г.',
                'at_time'     => ' в ',
            ],
            $langData ?: []
        );
        $monthName = $langData['months'][date('n', $timestamp)] ?? '-';
        $year      = date('Y', $timestamp);
        $withYear  = date('Y') !== $year;
        $day       = date('d', $timestamp);
        $seconds   = date('s', $timestamp);

        return $timestamp
            ? ("<nobr>{$day} {$monthName} " . ($withYear ? " {$year} {$langData['year_ending']}" : "") . $langData['at_time'] .date("H:i", $timestamp) . ($withSeconds ? ":{$seconds}" : "") . "</nobr>")
            : '';
    }

    public static function dateTimeRu(int $timestamp, $withSeconds = true): string
    {
        return OutputFormats::dateTimeLang($timestamp, $withSeconds);
    }

    public static function fromDate(string $date): int
    {
        return $date ? strtotime($date . " UTC") : 0;
    }

    public static function dateForDatePicker(int $timestamp): string
    {
        return $timestamp ? date('d-m-Y', $timestamp) : '';
    }

    public static function amount(float $amount, string $currency = '₽', $decimals = 2): string
    {
        $ret = number_format($amount, $decimals, '.', ' ');
        return "<nobr>{$ret} {$currency}</nobr>";
    }

    public static function fromAmount(string $amount): float
    {
        return floatval( strtr($amount, [
            " " => "",
            "," => ".",
        ]) );
    }

    public static function number(float $value): string
    {
        $ret = number_format($value, 2, '.', '');
        return "<nobr>{$ret}</nobr>";
    }

    public static function fromNumber(string $value): float
    {
        return floatval( strtr($value, [
            " " => "",
            "," => ".",
        ]) );
    }

    public static function mobilePhone(string $phone): string
    {
        if (strlen($phone) < 11) {
            return '';
        }

        $phone  = $phone[0] == '+' ? $phone : "+{$phone}";
        $part_1 = substr($phone, 0, 2);
        $part_2 = substr($phone, 2, 3);
        $part_3 = substr($phone, 5, 3);
        $part_4 = substr($phone, 8, 2);
        $part_5 = substr($phone, 10, 2);

        return "{$part_1}({$part_2}){$part_3}-{$part_4}-{$part_5}";
    }
}
