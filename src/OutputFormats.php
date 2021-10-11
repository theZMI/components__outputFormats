<?php

/**
 * Class OutputFormats
 *
 * Вывод данных в каком-либо формате (в виде телефона, в виде даты)
 */
class OutputFormats
{
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

    public static function dateTimeRu(int $timestamp, $withSeconds = true): string
    {
        $months    = [
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
        $monthName = $months[date('n', $timestamp)] ?? '-';
        $year      = date('Y', $timestamp);
        $withYear  = date('Y') !== $year;
        $day       = date('d', $timestamp);
        $seconds   = date('s', $timestamp);

        return $timestamp
            ? ("<nobr>{$day} {$monthName} " . ($withYear ? " {$year} г." : "") . date(' в H:i', $timestamp) . ($withSeconds ? " {$seconds}" : "") . "</nobr>")
            : '';
    }

    public static function fromDate(string $date): int
    {
        return $date ? strtotime($date . " UTC") : 0;
    }

    public static function dateForDatePicker(int $timestamp): string
    {
        return $timestamp ? date('d-m-Y', $timestamp) : '';
    }

    public static function amount(float $amount, string $currency = '₽'): string
    {
        $ret = number_format($amount, 2, ',', ' ');
        $ret = "<nobr>{$ret} {$currency}</nobr>";

        return $ret;
    }

    public static function fromAmount(string $amount): float
    {
        $pairs = [
            " " => "",
            "," => ".",
        ];
        $ret   = strtr($amount, $pairs);

        return floatval($ret);
    }

    public static function number(float $value): string
    {
        $ret = number_format($value, 2, '.', '');
        $ret = "<nobr>{$ret}</nobr>";

        return $ret;
    }

    public static function fromNumber(string $value): float
    {
        $pairs = [
            " " => "",
            "," => ".",
        ];
        $ret   = strtr($value, $pairs);

        return floatval($ret);
    }

    public static function mobilePhone(string $phone): string
    {
        $phone  = $phone[0] == '+' ? $phone : "+{$phone}";
        $part_1 = substr($phone, 0, 2);
        $part_2 = substr($phone, 2, 3);
        $part_3 = substr($phone, 5, 3);
        $part_4 = substr($phone, 8, 2);
        $part_5 = substr($phone, 10, 2);

        return "{$part_1}({$part_2}){$part_3}-{$part_4}-{$part_5}";
    }
}
